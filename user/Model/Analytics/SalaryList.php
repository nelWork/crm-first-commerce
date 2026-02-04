<?php

namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\User\Model\Model;

class SalaryList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function salaryUserList(array $conditions = []): array
    {
        $salaryDB = $this->database->superSelect('salary', $conditions,[],-1,['id']);

        $salaryList = [];

        foreach ($salaryDB as $salary) {
            $tempSalary = new Salary(['id' => $salary['id']]);

            $tempSalary->countSalary();
            $tempSalaryData = $tempSalary->get();

            $tempSalaryData['color'] = 0;

            if($tempSalaryData['status'] == 1){
                $tempSalaryData['color'] = 1;
            }

            if(strtotime($tempSalaryData['date_start']) <= strtotime(date('Y-m-d'))
                AND strtotime($tempSalaryData['date_end']) >= strtotime(date('Y-m-d'))){
                $tempSalaryData['color'] = 2;
            }


            $tempSalaryData['salary'] = number_format($tempSalaryData['salary'], 2, '.', ' ');
            $tempSalaryData['advance'] = number_format($tempSalaryData['advance'], 2, '.', ' ');
            $tempSalaryData['official5'] = number_format($tempSalaryData['official5'], 2, '.', ' ');
            $tempSalaryData['official20'] = number_format($tempSalaryData['official20'], 2, '.', ' ');
            $tempSalaryData['fines'] = number_format($tempSalaryData['fines'], 2, '.', ' ');
            $tempSalaryData['date_start'] = date('d.m.Y', strtotime($tempSalaryData['date_start']));
            $tempSalaryData['date_end'] = date('d.m.Y', strtotime($tempSalaryData['date_end']));


            $tempSalaryData['closed_applications'] = str_replace(
                    '|',
                    '' ,
                    str_replace(
                        '|,',
                        ', ',
                        $tempSalaryData['closed_applications']
                    )
            );

            $tempSalaryData['closed_applications'] = trim($tempSalaryData['closed_applications'],', ');

            $salaryList[] = $tempSalaryData;
        }

        if(empty($salaryList)){
            $this->add($conditions);
            return $this->salaryUserList($conditions);
        }


        return  $salaryList;
    }

    private function add(array $conditions = [])
    {
        if($conditions['id_user'] <= 0 or $conditions['id_user'] === null){
            return false;
        }
        // dd($conditions);

        // $tempSalary = new Salary();

        // $tempSalary->edit(
        //     ['dateStart' => '2024-12-20', 'dateEnd' => '2025-01-19', 'idUser' => $conditions['id_user']]
        // );
        // $tempSalary->save();

        for($i = 1; $i <= 12; $i++){

            $monthNow = $i;
            $monthNext = $i + 1;

            if($monthNow < 10){
                $monthNow = '0'.$monthNow;
            }

            if($monthNext < 10){
                $monthNext = '0'.$monthNext;
            }

            

            $date_start = "2025-$monthNow-20";
            $date_end = "2025-$monthNext-19";


            if($conditions['date_start >='] == '2026-01-01'){
                $date_start = "2026-$monthNow-01";
                $date_end = "2026-$monthNow-30";

                if($monthNow == 2){
                    $date_end = "2026-$monthNow-28";
                }

                if(in_array($monthNow,[1,3,5,7,8,10,12])){
                    $date_end = "2026-$monthNow-31";
                }
                
            }

            $tempSalary = new Salary();

            $tempSalary->edit(
                ['dateStart' => $date_start, 'dateEnd' => $date_end, 'idUser' => $conditions['id_user']]
            );

            $tempSalary->save();
            // dd($tempSalary);
        }

    }


}