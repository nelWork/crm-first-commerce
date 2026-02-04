<?php

namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\User\Model\Model;

class Statistics extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }

    public function getStatistics(array $conditions = []): array
    {
        $statistic = [];

        if(isset($conditions['period'])){
            $statistic['managerList'] = $this->getManagerList($conditions['period']);
        }
        else{
            $statistic['managerList'] = $this->getManagerList();

        }

        $statistic['plans'] = $this->database->select('types_plan');

        $sumPlan = 0;
        $sumQuantity = 0;

        foreach ($statistic['managerList'] as $key => $manager) {

            if($manager['plan']['id_plan']-1 < 0){
                continue;
            }

            $statistic['managerList'][$key]['planManager'] = $statistic['plans'][$manager['plan']['id_plan']-1];


            $sumPlanROP = 0;
            $sumQuantityROP = 0;

            $sumQuantity += $manager['plan']['quantity'];
            $sumQuantityROP += $manager['plan']['quantity'];
            switch ($manager['plan']['percent']) {
                case 15:
                    $sumPlan += $statistic['managerList'][$key]['planManager']['quantity_min_20'];
                    $sumPlanROP += $statistic['managerList'][$key]['planManager']['quantity_min_20'];
                    break;
                case 20:
                case 25:
                    $sumPlan += $statistic['managerList'][$key]['planManager']['quantity_min_25'];
                    $sumPlanROP += $statistic['managerList'][$key]['planManager']['quantity_min_25'];
                    break;
            }

            if(isset($manager['subordinates'])){
                foreach ($manager['subordinates'] as $keySubordinate => $subordinate) {
                    $statistic['managerList'][$key]['subordinates'][$keySubordinate]['planManager'] =
                        $statistic['plans'][$subordinate['plan']['id_plan']-1] ?? [];
                    $sumQuantity += $subordinate['plan']['quantity'];
                    $sumQuantityROP += $subordinate['plan']['quantity'];
                    switch ($subordinate['plan']['percent']) {
                        case 15:
                            $sumPlan += $statistic['managerList'][$key]['subordinates'][$keySubordinate]['planManager']['quantity_min_20'];
                            $sumPlanROP += $statistic['managerList'][$key]['subordinates'][$keySubordinate]['planManager']['quantity_min_20'];
                            break;
                        case 20:
                        case 25:
                            $sumPlan += $statistic['managerList'][$key]['subordinates'][$keySubordinate]['planManager']['quantity_min_25'];
                            $sumPlanROP += $statistic['managerList'][$key]['subordinates'][$keySubordinate]['planManager']['quantity_min_25'];
                            break;
                    }

                }
                $statistic['managerList'][$key]['sumQuantityROP'] = number_format($sumQuantityROP, 2, '.', ' ');
                $statistic['managerList'][$key]['sumPlanROP'] = number_format($sumPlanROP, 2, '.', ' ');
            }
        }

        $statistic['sumPlan'] = number_format($sumPlan,2,'.',' ');
        $statistic['sumQuantity'] = number_format($sumQuantity,2,'.',' ');




        return $statistic;
    }

    public function getManagerList(array $period = [])
    {
        $managerArray = $this->database->select('users', ['role' => 1, 'active' => 1]);
        $managerList = [];

        $ropList = [];

        foreach ($managerArray as $manager) {

            if($manager['subordinates'] !== '') {
                $manager['subordinates'] = str_replace(
                    '|','',
                    explode(
                        ",",
                        trim($manager['subordinates'],',')
                    )
                );
                $ropList[] = ['id' => $manager['id'], 'subordinates' => $manager['subordinates']];
            }

            $manager['plan'] = $this->getPlanManager($manager['id'],$period);

            if(!empty($manager['plan'])) {

                $applicationWalrus = $this->database->superSelect(
                    'applications',
                    [
                        'id_user' => $manager['id'],
                        'cancelled' => 0,
                        'dateField' => [
                            'name' => 'date',
                            'start' => $manager['plan']['date_start'],
                            'end' => $manager['plan']['date_end']
                        ]
                    ],
                    [],
                    -1,
                    ['SUM(application_walrus)'])[0]['SUM(application_walrus)'] ?? 0;


                $transportationCost = $this->database->superSelect(
                    'applications',
                    [
                        'id_user' => $manager['id'],
                        'cancelled' => 0,
                        'dateField' => [
                            'name' => 'date',
                            'start' => $manager['plan']['date_start'],
                            'end' => $manager['plan']['date_end']
                        ]
                    ],
                    [],
                    -1,
                    ['SUM(transportation_cost_Client)']
                )[0]['SUM(transportation_cost_Client)'] ?? 0;


                if ($applicationWalrus > 0) {
                    $manager['marginality'] =  $applicationWalrus / $transportationCost * 100;

                }
                else
                    $manager['marginality'] = 0;
            }
            else
                $manager['marginality'] = 0;


            unset($manager['subordinates']);

            $managerList[$manager['id']] = $manager;
        }

        foreach ($ropList as $rop) {
            foreach ($rop['subordinates'] as $subordinate) {
                if(isset($managerList[$subordinate]))
                    $managerList[$rop['id']]['subordinates'][] = $managerList[$subordinate];
                unset($managerList[$subordinate]);
            }
        }

        $managerWithSubordinates = [];
        $managerWithoutSubordinates = [];

        foreach ($managerList as $manager) {
            if(isset($manager['subordinates'])) {
                $managerWithSubordinates[] = $manager;
            }
            else{
                $managerWithoutSubordinates[] = $manager;
            }
        }

        foreach ($managerWithoutSubordinates as $manager)
            $managerWithSubordinates[] = $manager;


        return $managerWithSubordinates;

    }

    public function getPlanManager(int $idUser, array $period = [])
    {
        if(count($period)){
            $date_start_plan = $period['date_start'];
            $date_end_plan = $period['date_end'];
        }
        else {
            if (date('d') < 20) {
                $date_start_plan = date("Y-m-20", strtotime("-1 month"));
                $date_end_plan = date("Y-m-19");
            } else {
                $date_start_plan = date("Y-m-20");
                $date_end_plan = date("Y-m-19", strtotime('first day of next month'));
            }
        }

        $plan = $this->database->first(
            'plan_execution_managers',
            [
                'id_user' => $idUser,
                'date_start' => $date_start_plan,
                'date_end' => $date_end_plan
            ]
        );

        if(!$plan) {
            $plan = ['percent' => 0, 'id_plan' => 0, 'quantity' => 0,'date_start' => $date_start_plan, 'date_end' => $date_end_plan];
        }

        return $plan;
    }

}