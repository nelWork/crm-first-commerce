<?php

namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\Model\User\User;
use App\User\Model\Model;

class Declaration extends Model
{
    private DatabaseInterface $database;

    public function __construct(DatabaseInterface $database){
        $this->database = $database;
    }


    public function getDeclaration(array $condition = [])
    {
        $salaryDB = $this->database->select('salary', $condition);

        $declaration = [];
        $sumSalary = 0;
        $sumFixSalary = 0;
        $sumPercentApplications = 0;
        $sumPercentPrrApplications = 0;

        $declaration['managerList'] = $this->getManagerList();


        foreach ($declaration['managerList'] as $key => $manager) {
            $condition['id_user'] = $manager['id'];
            $salaryModel = new Salary($condition);
            $salary = $salaryModel->countSalary();
            
            $declaration['managerList'][$key]['sumSalary'] = $salary;

            $declaration['managerList'][$key]['salary'] = number_format($salary,2,'.',' ');
            $declaration['managerList'][$key]['percentApplications'] = number_format($salaryModel->getPercentApplications(),2,'.',' ');
            $declaration['managerList'][$key]['percentPrrApplications'] = number_format($salaryModel->getPercentPrrApplications(),2,'.',' ');

            $sumPercentApplications += $salaryModel->getPercentApplications();
            $sumPercentPrrApplications += $salaryModel->getPercentPrrApplications();

            $declaration['managerList'][$key]['hrefSalary'] = $salaryModel->get()['id'];

            if(strtotime($condition['date_start']) >= strtotime('2025-11-01')){
                $salaryData = $this->database->first(
                    'salary_user_settings',
                    $condition
                );

                if(!empty($salaryData)){
                    $declaration['managerList'][$key]['fix_salary'] = number_format(
                        $salaryData['fix_salary'], 2, '.', ' '
                    );
                    $declaration['managerList'][$key]['additional_salary'] = number_format(
                        $salaryData['additional_salary'], 2, '.', ' '
                    );
                    $sumFixSalary += $salaryData['fix_salary'];
                    $salary = $salaryData['fix_salary'] + $salaryData['additional_salary'] + $salaryModel->getPercentApplications() + $salaryModel->getPercentPrrApplications();
                    $declaration['managerList'][$key]['salary'] = number_format($salary,2,'.',' ');
                }
            }
            $sumSalary += $salary;
            if(isset($manager['subordinates'])){
                foreach ($manager['subordinates'] as $keySub => $subordinate) {
                    $condition['id_user'] = $subordinate['id'];
                    $salaryModelSub = new Salary($condition);
                    $salarySub = $salaryModelSub->countSalary();
                    $declaration['managerList'][$key]['subordinates'][$keySub]['salary'] =
                        number_format($salarySub,2,'.',' ');

                    $declaration['managerList'][$key]['subordinates'][$keySub]['hrefSalary'] = $salaryModelSub->get()['id'];
                    $declaration['managerList'][$key]['subordinates'][$keySub]['percentApplications'] = number_format($salaryModelSub->getPercentApplications(),2,'.',' ');
                    $declaration['managerList'][$key]['subordinates'][$keySub]['percentPrrApplications'] = number_format($salaryModelSub->getPercentPrrApplications(),2,'.',' ');

                    $sumPercentApplications += $salaryModelSub->getPercentApplications();
                    $sumPercentPrrApplications += $salaryModelSub->getPercentPrrApplications();

                    $declaration['managerList'][$key]['sumSalary'] += $salarySub;

                    if(strtotime($condition['date_start']) >= strtotime('2025-11-01')){
                        $salaryData = $this->database->first(
                            'salary_user_settings',
                            $condition
                        );

                        if(!empty($salaryData)){
                            $declaration['managerList'][$key]['subordinates'][$keySub]['fix_salary'] = number_format(
                                $salaryData['fix_salary'], 2, '.', ' '
                            );

                            $declaration['managerList'][$key]['subordinates'][$keySub]['additional_salary'] = number_format(
                                $salaryData['additional_salary'], 2, '.', ' '
                            );
                            $sumFixSalary += $salaryData['fix_salary'];

                            $salarySub = $salaryData['fix_salary'] + $salaryData['additional_salary'] + $salaryModelSub->getPercentApplications() + $salaryModelSub->getPercentPrrApplications();
                            $declaration['managerList'][$key]['subordinates'][$keySub]['salary'] = number_format($salarySub,2,'.',' ');

                            $sumSalary += $salarySub;
                        }

                        
                    }
                    else{
                        $sumSalary += $salarySub;
                    }

            }
            $declaration['managerList'][$key]['sumSalary'] =
                number_format($declaration['managerList'][$key]['sumSalary'],2,'.',' ');
            }
        }


        $declaration['sumSalary'] = number_format($sumSalary, 2, '.', ' ');
        $declaration['sumFixSalary'] = number_format($sumFixSalary, 2, '.', ' ');
        $declaration['sumPercentApplications'] = number_format($sumPercentApplications, 2, '.', ' ');
        $declaration['sumPercentPrrApplications'] = number_format($sumPercentPrrApplications, 2, '.', ' ');

        return $declaration;
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
}