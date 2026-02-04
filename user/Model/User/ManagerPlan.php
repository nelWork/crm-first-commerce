<?php

namespace App\User\Model\User;

use App\Database\DatabaseInterface;
use App\Model\Application\Application;
use App\User\Model\Model;

class ManagerPlan extends Model
{

    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }

    public function hasPlanExecutionManager(array $data = [])
    {


        if($data === [])
            return false;
        $date_start = $data['date_start'];
        $date_end = $data['date_end'];
        $quantity = $data['quantity'];
        $id_user = $data['id_user'];
        $id_plan = $data['id_plan'];
        $percent = $data['percent'];


        $hasInTable = $this->database->first('plan_execution_managers',
            ['date_start' => $date_start, 'date_end' => $date_end, 'id_user' => $id_user]) ?? [];


        if(! empty($hasInTable)){
            $this->database->update('plan_execution_managers',
                [
                    'quantity' => $quantity,
                    'id_plan' => $id_plan,
                    'percent' => $percent,
                ],
                [
                    'id' => $hasInTable['id']
                ]
            );
        }
        else{
            $this->database->insert('plan_execution_managers',
                $data
            );
        }


    }

    public function checkPlanExecutionManager(int $idManager = 0)
    {



        if(date('d') < 20){
            $date_start_plan = date("Y-m-20", strtotime("-1 month"));
            $date_end_plan = date("Y-m-19");
        }
        else{
            $date_start_plan = date("Y-m-20");
            $date_end_plan = date("Y-m-19", strtotime('first day of next month'));
        }


        $plans = $this->database->select('types_plan');

        $arrayUser = [];
        foreach ($plans as $plan) {
            $date_start = date('Y-m-d', strtotime($plan['date_start']));
            $date_end = date('Y-m-d', strtotime($plan['date_end']));
            switch ($plan['type']):
                case 'Основной план':
                    $date_start = '2024-06-06';
                    break;
            endswitch;
            if($idManager > 0){
                $managers = $this->database->superSelect('users',
                    ['role' => 1,
                        'dateField' => [
                            'name' => 'registr_date',
                            'start' => $date_start,
                            'end' => $date_end
                        ],
                        'id' => $idManager
                    ],
                    [],
                    -1,
                    ['id', 'name', 'surname']
                );
            }
            else {

                $managers = $this->database->superSelect('users',
                    ['role' => 1,
                        'dateField' => [
                            'name' => 'registr_date',
                            'start' => $date_start,
                            'end' => $date_end
                        ],
                    ],
                    [],
                    -1,
                    ['id', 'name', 'surname']
                );
                dd($managers);
            }

            $arrayUser[$plan['id']] = $managers;

            foreach ($managers as $keyManager => $manager ) {
                $id_user = $manager['id'];

                $applications = $this->database->superSelect('applications',
                    [
                        'dateField' => [
                            'name' => 'date',
                            'start' => $date_start_plan,
                            'end' => $date_end_plan . ' 23:59:59'
                        ],
                        'id_user' => $id_user,
                        'cancelled' => 0
                    ],
                    [],
                    -1,
                );

                $prrApplications = $this->database->superSelect('prr_application',
                    [
                        'dateField' => [
                            'name' => 'date',
                            'start' => $date_start_plan,
                            'end' => $date_end_plan . ' 23:59:59'
                        ],
                        'id_user' => $id_user,
                        'cancelled' => 0
                    ],
                    [],
                    -1,
                    ['sum(application_walrus)']
                );

                $sumWalrusApplication = 0;

                foreach ($applications as $application) {
                    if($application['application_walrus'] <= 0)
                        continue;
                    $sumWalrusApplication += $application['application_walrus'];
                }

                if(empty($prrApplications[0]['sum(application_walrus)']))
                    $prrApplications[0]['sum(application_walrus)'] = 0;

                if($sumWalrusApplication + $prrApplications[0]['sum(application_walrus)'] >= 0) {
                    $sum = $sumWalrusApplication + $prrApplications[0]['sum(application_walrus)'];

                    $percent = 15;

                    if($sum > $plan['quantity_max_15'])
                        $percent = 20;

                    if($sum > $plan['quantity_max_20'] OR $plan['id'] > 2 )
                        $percent = 25;




                    $data = [
                        'date_start' => $date_start_plan,
                        'date_end' => $date_end_plan,
                        'quantity' => $sum,
                        'id_plan' => $plan['id'],
                        'percent' => $percent,
                        'id_user' => $id_user,
                    ];

                    $this->hasPlanExecutionManager($data);

                    unset($managers[$keyManager]);

                }
            }


        }



    }

    public function changeApplicationManagerShare(int $id_user, string $date_start_plan, string $date_end_plan)
    {
        $applicationsId = $this->database->superSelect('applications',
            [
                'dateField' => [
                    'name' => 'date',
                    'start' => $date_start_plan,
                    'end' => $date_end_plan
                ],
                'id_user' => $id_user
            ],
            [],
            -1,
            ['id']
        );

        foreach ($applicationsId as $applicationId) {
            $tempApplication = new Application(['id' => $applicationId['id']]);

            $tempApplication->save();
        }
    }
}