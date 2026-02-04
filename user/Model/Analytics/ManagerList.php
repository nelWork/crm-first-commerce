<?php

namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\Model\User\User;
use App\User\Model\Model;

class ManagerList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }

    public function ManagerList(array $condition = [],array $conditionUser = [])
    {

        $conditionUser['role'] = 1;
        $usersId = $this->database->superSelect('users', $conditionUser,[],-1,['id']);
        $list = [];

        $sumSalary = 0;
        $sumWalrus = 0;
        $sumNetProfit = 0;

        $countApplication = 0;

        foreach ($usersId as $userId){
            $user = new User(['id' => $userId['id']]);

            $condition['id_user'] = $user->id();
            $data = $this->calculateRevenue($condition);

            $tempData = [];

            $link = '';

            foreach ($data['id-applications'] as $item){
                $link .= $item['id'] .',';
            }

            $link = substr($link, 0, -1);

            $tempData['id'] = $user->id();
            $tempData['name'] = $user->FIO();
            $tempData['link'] = $link;
            $tempData['count-application'] = $data['count_application'];
            $countApplication += $data['count_application'];
            $tempData['salary'] = $data['manager_share'];
            $sumSalary += $data['manager_share'];
            $tempData['revenue'] = $data['application_walrus'];

            $tempData['net_profit'] = $data['application_net_profit'];

            $sumWalrus += $data['application_walrus'];

            $sumNetProfit += $data['application_net_profit'];

            $list['list'][] = $tempData;

        }
        $list['countApplication'] = $countApplication;
        $list['sumSalary'] = $sumSalary;
        $list['sumWalrus'] = $sumWalrus;

        $list['netProfit'] = $sumNetProfit;

        return $list;
    }

    public function ROPManagerList(array $condition = [],array $conditionUser = [])
    {
        $conditionUser['role'] = 1;
        $conditionUser['active'] = 1;

        $managerArray = $this->database->superSelect('users', $conditionUser);
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

        $sumSalary = 0;
        $sumWalrus = 0;
        $sumNetProfit = 0;

        $countApplication = 0;
        $list = [];


        foreach ($managerWithSubordinates as $userData) {

            $user = new User(['id' => $userData['id']]);

            $condition['id_user'] = $user->id();
            $data = $this->calculateRevenue($condition);

            $tempData = [];

            $tempData['link-journal'] = $data['id-applications'];

            $tempData['id'] = $user->id();
            $tempData['name'] = $user->FIO();
            $tempData['count-application'] = $data['count_application'];

            $countApplication += $data['count_application'];
            $tempData['salary'] = $data['manager_share'];
            $sumSalary += $data['manager_share'];
            $tempData['revenue'] = $data['application_walrus'];

            $tempData['net_profit'] = $data['application_net_profit'];

            $sumWalrus += $data['application_walrus'];

            $sumNetProfit += $data['application_net_profit'];

            if(isset($userData['subordinates'])) {
                $tempData['subordinates'] = [];

                $tempData['sum-salary'] = 0;
                $tempData['sum-walrus'] = 0;
                $tempData['net-profit'] = 0;
                $tempData['rop-count-application'] = 0;


                foreach ($userData['subordinates'] as $subordinate) {
                    $userSubordinates = new User(['id' => $subordinate['id'],'active' => 1]);

                    if($userSubordinates->id() == 0)
                        continue;

                    $condition['id_user'] = $userSubordinates->id();
                    $data = $this->calculateRevenue($condition);

                    $tempDataSubordinates = [];

                    $tempDataSubordinates['id'] = $userSubordinates->id();
                    $tempDataSubordinates['name'] = $userSubordinates->FIO();
                    $tempDataSubordinates['count-application'] = $data['count_application'];
                    $countApplication += $data['count_application'];
                    $tempData['rop-count-application'] += $data['count_application'];
                    $tempDataSubordinates['salary'] = $data['manager_share'];

                    $sumSalary += $data['manager_share'];
                    $tempData['sum-salary'] += $data['manager_share'];

                    $tempDataSubordinates['revenue'] = $data['application_walrus'];

                    $tempDataSubordinates['net_profit'] = $data['application_net_profit'];

                    $sumWalrus += $data['application_walrus'];
                    $tempData['sum-walrus'] += $data['application_walrus'];

                    $sumNetProfit += $data['application_net_profit'];
                    $tempData['net-profit'] += $data['application_net_profit'];

                    $tempData['subordinates'][] = $tempDataSubordinates;
                }
            }


            $list['list'][] = $tempData;
        }

        $list['countApplication'] = $countApplication;
        $list['sumSalary'] = $sumSalary;
        $list['sumWalrus'] = $sumWalrus;

        $list['netProfit'] = $sumNetProfit;



        return $list;
    }

    private function calculateRevenue(array $condition = []): array
    {
        $data = [];
        $applicationDB = $this->database->superSelect(
            'applications',
            $condition,
            [],
            -1,
            ['application_walrus','manager_share','application_net_profit']
        );

        $manager_share = 0;
        $application_walrus = 0;
        $application_net_profit = 0;

        foreach ($applicationDB as $application){
            $manager_share += $application['manager_share'];
            $application_walrus += $application['application_walrus'];
            $application_net_profit += $application['application_net_profit'];
        }

        $applicationPRRDB = $this->database->superSelect(
            'prr_application',
            $condition,
            [],
            -1,
            ['application_walrus','manager_share','application_net_profit']
        );

        foreach ($applicationPRRDB as $application){
            $manager_share += $application['manager_share'];
            $application_walrus += $application['application_walrus'];
            $application_net_profit += $application['application_net_profit'];
        }

        $data['manager_share'] = $manager_share;
        $data['application_walrus'] = $application_walrus;
        $data['application_net_profit'] = $application_net_profit;
        $data['count_application'] = count($applicationDB) + count($applicationPRRDB);

        $data['id-applications'] = $this->database->superSelect(
            'applications',
            $condition,
            [],
            -1,
            ['id']
        );

        return $data;
    }

    public function managersIndividualData(array $condition = [] ,array $conditionUser = [])
    {
        $conditionUser['role'] = 1;
        $conditionUser['active'] = 1;
        $usersId = $this->database->superSelect('users', $conditionUser,[],-1,['id']);
        $list = [];

        foreach ($usersId as $userId){
            $user = new User(['id' => $userId['id']]);

            $tempData = [];

            $tempData['id'] = $user->id();
            $tempData['name'] = $user->FIO();

            $condition['id_user'] = $user->id();

            $applicationDB = $this->database->superSelect(
                'applications',
                $condition,
                [],
                -1,
                ['client_id_Client','application_walrus','manager_share','application_net_profit']
            );

            $tempData['clients'] = $this->groupDataByClient($applicationDB);

            $list[] = $tempData;
        }

        return $list;
    }

    private function groupDataByClient(array $rawData): array
    {
        $groupedData = [];

        foreach ($rawData as $entry) {
            $clientId = $entry['client_id_Client'];

            // Если клиент ещё не существует в итоговом массиве, инициализируем его
            if (!isset($groupedData[$clientId])) {
                $clientName = $this->database->first('clients', ['id' => $clientId], ['name'])['name'] ?? '';
                $groupedData[$clientId] = [
                    'clientName' => $clientName,
                    'countApplication' => 0,
                    'sumSalary' => 0,
                    'netProfit' => 0,
                    'sumWalrus' => 0,
                ];
            }

            // Увеличиваем счётчик заявок для клиента
            $groupedData[$clientId]['countApplication']++;
            // Сумма зарплат (manager_share)
            $groupedData[$clientId]['sumSalary'] += $entry['manager_share'];
            // Сумма чистой прибыли
            $groupedData[$clientId]['netProfit'] += $entry['application_net_profit'];
            // Сумма маржи (application_walrus)
            $groupedData[$clientId]['sumWalrus'] += $entry['application_walrus'];
        }

        // Переводим ассоциативный массив $groupedData в список для удобства
        return array_values($groupedData);
    }
}