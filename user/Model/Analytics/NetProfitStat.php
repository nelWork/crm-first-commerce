<?php

namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\Model\Application\Application;
use App\User\Model\Model;

class NetProfitStat extends Model
{
    private DatabaseInterface $database;

    public function __construct(DatabaseInterface $database){
        $this->database = $database;
    }

    public function getStatistics(array $conditions = [], int $quantityDay = 1 ,int $idSalary = 1){

        $applicationsList  = $this->database->superSelect(
            'applications',
            $conditions,
            [],
            -1,
            [
                'id',
                // 'id_user',
                'actual_payment_Client',
                // 'client_id_Client',
                // 'date',
                // 'for_sales',
                // 'share_for_sales'
            ]
        );

        // dump($applicationsList);

        $sumActualPayment = 0;
        $sumNetProfit = 0;
        foreach($applicationsList as $application){
            $sumActualPayment += $application['actual_payment_Client'];
            $sumNetProfit += $this->calculateNetProfit($application);
        }

        $textId = '';
        $listId = [];

        foreach($applicationsList as $application){
            $listId[] = $application['id'];
            $textId .= $application['id'].',';
        }

        $salaryList = $this->database->superSelect('salary_user_settings',['id_salary' => $idSalary]);

        $sumFixSalary = 0;

        foreach($salaryList as $salaryData){
            $sumFixSalary += $salaryData['fix_salary'];
        }

        $sumFixSalary = $sumFixSalary * $quantityDay / 30;

        // dump($sumActualPayment,$sumNetProfit);

        $sumResult = $sumNetProfit - $sumFixSalary;
        
        return [
            'listId' => $listId,
            'sumActualPayment' => $sumActualPayment,
            'sumNetProfit' => $sumNetProfit,
            'textId' => $textId,
            'sumFixSalary' => $sumFixSalary,
            'sumResult' => $sumResult

        ];
    }



    private function calculateNetProfit($applicationData = []){
        $netProfit = 0;

        $application = new Application(['id' => $applicationData['id']]);

        $walrus = $application->getWalrus();
        $netProfit = $application->getNetProfit($walrus, 1);
        return $netProfit;
    }



}