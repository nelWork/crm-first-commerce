<?php

namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\Model\User\User;
use App\User\Model\Application\Plan;
use App\User\Model\Model;
use mysql_xdevapi\Exception;

class SalaryStatistics extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }

    public function getSalaryStatistics(int $idSalary): array
    {
        $statisticsArray = [
            'listApplications' => [],
            'listPrrApplications' => [],
            'listFineManager' => [],
            'listPaymentsManager' => [],
            'sumPaymentsManager' => 0,
            'sumFineManager' => 0,
            'sumApplicationsManagerShare' => 0,
            'sumPrrApplicationsManagerShare' => 0,
            'sumApplicationsData' => 0

        ];

        $salary = new Salary(['id' => $idSalary]);

        $salaryData = $salary->get();


        $statisticsArray['salaryData'] = $salaryData;
        $statisticsArray['salaryData']['date_start'] = date('d.m.Y', strtotime($salaryData['date_start']));
        $statisticsArray['salaryData']['date_end'] = date('d.m.Y', strtotime($salaryData['date_end']));
        $statisticsArray['salary'] = number_format(
            $salaryData['salary'],
            2,
            '.',
            ' '
        );

        $statisticsArray['listPaymentsManager'] = $this->getPaymentsManagerList($idSalary);

        $statisticsArray['listFineManager'] = $this->getFinesManagerList($idSalary);


        $Manager = new User(['id' => $salaryData['id_user']]);

        $managerData = $Manager->get();



        $statisticsArray['managerData'] = $managerData;

        $closedApplications = trim(str_replace(
            '|',
            '' ,
            str_replace(
                '|,',
                ', ',
                $salary->get()['closed_applications']
            )
        ),', ');
        //dd($salary->get());

        $numberApplicationsList = explode(',', $closedApplications);

        


        $listApplications = $this->database->superSelect(
            'applications',
            ['application_number' => $numberApplicationsList],
            [],
            -1,
            [
                'id', 'application_number',
                'manager_share','application_walrus', 'transportation_cost_Client','transportation_cost_Carrier',
                'date', 'application_closed_date', 'id_user', 'customer_id_Client','account_number_Client','share_for_sales','for_sales'

            ]
        );

        //dd(['application_number' => $numberApplicationsList]);

        $sumApplications = [
            'application_walrus' => 0,
            'application_walrus_without_tax' => 0,
            'manager_share' => 0,
        ];
        foreach ($listApplications as $application) {
            if($salaryData['id_user'] == 55){
                $application['manager_share'] = $application['share_for_sales'];
            }
            $tempApplicationData = $application;
            $dayIntApplication = (int)date('d', strtotime($application['date']));

            if($dayIntApplication > 19){
                $dateStartPlanForApplication = date("Y-m-20", strtotime($application['date']));
                $dateEndPlanForApplication = date("Y-m-19", strtotime('first day of next month', strtotime($application['date'])));
            }
            else{
                $dateStartPlanForApplication = date("Y-m-20", strtotime('-1 month', strtotime($application['date'])));
                $dateEndPlanForApplication = date("Y-m-19", strtotime($application['date']));
            }


            if(strtotime($application['date']) > strtotime('2025-09-01 00:00:00') AND 
                strtotime($application['date']) < strtotime('2025-11-01 00:00:00')){
                $dateStartPlanForApplication = date("2025-09-20");
                $dateEndPlanForApplication = date("2025-10-31");
            }


            if(strtotime($application['date']) > strtotime('2025-11-01 00:00:00')){
                $dateStartPlanForApplication = date("Y-m-01",strtotime($application['date']));
                $dateEndPlanForApplication = date("Y-m-t",strtotime($application['date']));
            }


            $planExecutionManagerForApplication = $this->database->superSelect(
                'plan_execution_managers',
                [
                    'id_user' => $application['id_user'],
                    'date_start' => $dateStartPlanForApplication,
                    'date_end' => $dateEndPlanForApplication,
                ],
            );

            $tempApplicationData['percent_sales_client'] = 15;

            if(! $planExecutionManagerForApplication){
                $tempApplicationData['percent_manager'] = $managerData['procent'];
            }
            else
                $tempApplicationData['percent_manager'] = $planExecutionManagerForApplication[0]['percent'];

            if(in_array($application['id_user'], [2,7,17])){
                $tempApplicationData['percent_manager'] = 35;
            }
            if(in_array($application['id_user'], [2,7,17,31])){
                $tempApplicationData['percent_sales_client'] = 20;
            }
            // dump($planExecutionManagerForApplication);

            if(strtotime($tempApplicationData['date']) >= strtotime('2025-11-01')){
                $salarySetting = $this->database->first('salary_user_settings',[
                    'date_start' => $planExecutionManagerForApplication[0]['date_start'],
                    'date_end' => $planExecutionManagerForApplication[0]['date_end'],
                    'id_user' => $application['id_user']
                ]);
                // dump($salarySetting);
                $tempApplicationData['percent_manager'] = $salarySetting['percent_client'];
                $tempApplicationData['percent_sales_client'] = $salarySetting['percent_sales_client_first'];
                // dd($tempApplicationData);
            }

            if($application['application_walrus'] >= 0):

                if($application['customer_id_Client'] == 1){
                    $tempApplicationData['application_walrus_without_tax'] = $application['application_walrus'] * 0.86;
                }
                else{
                    $tempApplicationData['application_walrus_without_tax'] = $application['application_walrus'] * 0.93;
                }
            else:
                $tempApplicationData['application_walrus_without_tax'] = 0;
            endif;

            $tempApplicationData['for_sales'] = $application['for_sales'];

            if($application['transportation_cost_Client'] > 0)
                $tempApplicationData['marginality'] = $application['application_walrus'] / $application['transportation_cost_Client'] * 100;
            else
                $tempApplicationData['marginality'] = 0;

            
            $tempApplicationData['date'] = date('d.m.Y', strtotime($tempApplicationData['date']));
            $tempApplicationData['application_closed_date'] = date(
                'd.m.Y',
                strtotime($tempApplicationData['application_closed_date'])
            );

            $sumApplications['application_walrus'] += $tempApplicationData['application_walrus'];
            $sumApplications['manager_share'] +=  $tempApplicationData['manager_share'];

            $tempApplicationData['account_number_Client'] = $application['account_number_Client'];

            $tempApplicationData['manager_share'] = number_format(
                $tempApplicationData['manager_share'],
                2,
                '.',
                ' '
            );
            $tempApplicationData['application_walrus'] = number_format(
                $tempApplicationData['application_walrus'],
                2,
                '.',
                ' '
            );

            $tempApplicationData['marginality'] = number_format(
                $tempApplicationData['marginality'],
                2,
                '.',
                ' '
            );

            $sumApplications['application_walrus_without_tax'] += $tempApplicationData['application_walrus_without_tax'];


            $strangeApplication = false;

            $sumAdditionalExpenses = $this->database->first(
                'additional_expenses',
                ['id_application' => $application['id']],
                ['SUM(sum) as sum']
            )['sum'] ?? 0;

            $sumAdditionalProfit = $this->database->first(
                'additional_profit',
                ['id_application' => $application['id']],
                ['SUM(sum) as sum']
            )['sum'] ?? 0;

            $revenueInApplication = $application['transportation_cost_Client'] + $sumAdditionalProfit -
            $application['transportation_cost_Carrier'] - $sumAdditionalExpenses;

            if($application['application_walrus'] > $revenueInApplication AND $application['application_walrus'] > 0){
                $strangeApplication = true;
            }

            $tempApplicationData['strangeApplication'] = $strangeApplication;

            $tempApplicationData['application_walrus_without_tax'] = number_format(
                $tempApplicationData['application_walrus_without_tax'],
                2,
                '.',
                ' '
            );

            $statisticsArray['listApplications'][] = $tempApplicationData;
            $statisticsArray['sumApplicationsManagerShare'] += $application['manager_share'];
        }


        if($salary->get()['subordinates_closed_applications'] != ''){

            $closedApplicationsSubordinates = trim(str_replace(
                '|',
                '' ,
                str_replace(
                    '|,',
                    ', ',
                    $salary->get()['subordinates_closed_applications']
                )
            ),', ');

            $numberApplicationsListSubordinates = explode(',', $closedApplicationsSubordinates);

            $listApplicationsSubordinates = $this->database->superSelect(
                'applications',
                ['application_number' => $numberApplicationsListSubordinates,'cancelled' => 0],
                ['id' => 'DESC'],
                -1,
                [
                    'id', 'application_number',
                    'manager_share','application_walrus', 'transportation_cost_Client',
                    'date', 'application_closed_date', 'id_user', 'customer_id_Client','account_number_Client'

                ]
            );

            $sumApplicationsSubordinates = [
                'application_walrus' => 0,
                'application_walrus_without_tax' => 0,
                'manager_share_rop' => 0,
            ];

            foreach ($listApplicationsSubordinates as $application) {

                if($application['id'] == 37) continue;
                $tempApplicationData = $application;
                $dayIntApplication = (int)date('d', strtotime($application['date']));

                if($dayIntApplication > 19){
                    $dateStartPlanForApplication = date("Y-m-20", strtotime($application['date']));
                    $dateEndPlanForApplication = date("Y-m-19", strtotime('first day of next month', strtotime($application['date'])));
                }
                else{
                    $dateStartPlanForApplication = date("Y-m-20", strtotime('-1 month', strtotime($application['date'])));
                    $dateEndPlanForApplication = date("Y-m-19", strtotime($application['date']));
                }


                $planExecutionManagerForApplication = $this->database->superSelect(
                    'plan_execution_managers',
                    [
                        'id_user' => $application['id_user'],
                        'date_start' => $dateStartPlanForApplication,
                        'date_end' => $dateEndPlanForApplication,
                    ],
                );

                $ManagerSubordinates = new User(['id' => $application['id_user']]);

                $managerDataSubordinates = $ManagerSubordinates->get();

                $tempApplicationData['fio_subordinates'] = $managerDataSubordinates['surname'] . ' ' . $managerDataSubordinates['name'];

                if(! $planExecutionManagerForApplication){
                    $tempApplicationData['percent_manager'] = $managerDataSubordinates['procent'];
                }
                else
                    $tempApplicationData['percent_manager'] = $planExecutionManagerForApplication[0]['percent'];

                if($application['application_walrus'] >= 0):
                    if($application['customer_id_Client'] == 1){
                        $tempApplicationData['application_walrus_without_tax'] = $application['application_walrus'] * 0.86;
                    }
                    else{
                        $tempApplicationData['application_walrus_without_tax'] = $application['application_walrus'] * 0.93;
                    }
                else:
                    $tempApplicationData['application_walrus_without_tax'] = 0;
                endif;

                $tempApplicationData['marginality'] = $application['application_walrus'] / $application['transportation_cost_Client'] * 100;

                $tempApplicationData['date'] = date('d.m.Y', strtotime($tempApplicationData['date']));
                $tempApplicationData['application_closed_date'] = date(
                    'd.m.Y',
                    strtotime($tempApplicationData['application_closed_date'])
                );

                $sumApplicationsSubordinates['application_walrus'] += $tempApplicationData['application_walrus'];
                $sumApplicationsSubordinates['manager_share_rop']  += $tempApplicationData['application_walrus_without_tax'] * 0.05;



//                echo ($tempApplicationData['application_walrus_without_tax'] * 0.05) .' - '  .$sumApplicationsSubordinates['manager_share_rop'] .'<br>';


                $tempApplicationData['account_number_Client'] = $application['account_number_Client'];

                $tempApplicationData['manager_share_rop'] = number_format(
                    $tempApplicationData['application_walrus_without_tax'] * 0.05,
                    2,
                    '.',
                    ' '
                );
                $tempApplicationData['application_walrus'] = number_format(
                    $tempApplicationData['application_walrus'],
                    2,
                    '.',
                    ' '
                );

                $tempApplicationData['marginality'] = number_format(
                    $tempApplicationData['marginality'],
                    2,
                    '.',
                    ' '
                );

                $sumApplicationsSubordinates['application_walrus_without_tax'] +=
                    $tempApplicationData['application_walrus_without_tax'];

                $tempApplicationData['application_walrus_without_tax'] = number_format(
                    $tempApplicationData['application_walrus_without_tax'],
                    2,
                    '.',
                    ' '
                );

                $statisticsArray['listApplicationsSubordinates'][] = $tempApplicationData;
//                $statisticsArray['sumApplicationsManagerShare'] += $application['manager_share'];
            }


            foreach ($sumApplicationsSubordinates as $key => $sumApplication) {
                $sumApplicationsSubordinates[$key] = number_format(
                    $sumApplicationsSubordinates[$key],
                    2,
                    '.',
                    ' '
                );
            }

            $statisticsArray['sumApplicationsSubordinates'] = $sumApplicationsSubordinates;

        }

//        PRR APPLICATION
        $closedPrrApplications = trim(str_replace(
            '|',
            '' ,
            str_replace(
                '|,',
                ',',
                $salary->get()['closed_prr_applications']
            )
        ),', ');

        $numberPrrApplicationsList = explode(',', $closedPrrApplications);


        $listPrrApplications = $this->database->superSelect(
            'prr_application',
            ['application_number' => $numberPrrApplicationsList],
            [],
            -1,
            [
                'id', 'application_number',
                'manager_share','application_walrus', 'cost_Client','cost_Prr',
                'date', 'application_closed_date', 'id_user', 'customer_id_Client','account_number_Client'

            ]
        );

//        dd($numberPrrApplicationsList);

        $sumPrrApplications = [
            'application_walrus' => 0,
            'application_walrus_without_tax' => 0,
            'manager_share' => 0,
        ];
        foreach ($listPrrApplications as $application) {
            $tempApplicationData = $application;
            $dayIntApplication = (int)date('d', strtotime($application['date']));

            if($dayIntApplication > 19){
                $dateStartPlanForApplication = date("Y-m-20", strtotime($application['date']));
                $dateEndPlanForApplication = date("Y-m-19", strtotime('first day of next month', strtotime($application['date'])));
            }
            else{
                $dateStartPlanForApplication = date("Y-m-20", strtotime('-1 month', strtotime($application['date'])));
                $dateEndPlanForApplication = date("Y-m-19", strtotime($application['date']));
            }


            $planExecutionManagerForApplication = $this->database->superSelect(
                'plan_execution_managers',
                [
                    'id_user' => $application['id_user'],
                    'date_start' => $dateStartPlanForApplication,
                    'date_end' => $dateEndPlanForApplication,
                ],
            );


            if(! $planExecutionManagerForApplication){
                $tempApplicationData['percent_manager'] = $managerData['procent'];
            }
            else
                $tempApplicationData['percent_manager'] = $planExecutionManagerForApplication[0]['percent'];

            if(in_array($application['id_user'], [2,7,17]))
                $tempApplicationData['percent_manager'] = 35;

            if($application['application_walrus'] >= 0):

                if($application['customer_id_Client'] == 1){
                    $tempApplicationData['application_walrus_without_tax'] = $application['application_walrus'] * 0.86;
                }
                else{
                    $tempApplicationData['application_walrus_without_tax'] = $application['application_walrus'] * 0.93;
                }
            else:
                $tempApplicationData['application_walrus_without_tax'] = 0;
            endif;

            $tempApplicationData['marginality'] = $application['application_walrus'] / $application['cost_Client'] * 100;

            $tempApplicationData['date'] = date('d.m.Y', strtotime($tempApplicationData['date']));
            $tempApplicationData['application_closed_date'] = date(
                'd.m.Y',
                strtotime($tempApplicationData['application_closed_date'])
            );

            $sumPrrApplications['application_walrus'] += $tempApplicationData['application_walrus'];
            $sumPrrApplications['manager_share'] +=  $tempApplicationData['manager_share'];

            $tempApplicationData['account_number_Client'] = $application['account_number_Client'];

            $tempApplicationData['manager_share'] = number_format(
                $tempApplicationData['manager_share'],
                2,
                '.',
                ' '
            );
            $tempApplicationData['application_walrus'] = number_format(
                $tempApplicationData['application_walrus'],
                2,
                '.',
                ' '
            );

            $tempApplicationData['marginality'] = number_format(
                $tempApplicationData['marginality'],
                2,
                '.',
                ' '
            );

            $sumPrrApplications['application_walrus_without_tax'] += $tempApplicationData['application_walrus_without_tax'];


            $strangeApplication = false;

//            $sumAdditionalExpenses = $this->database->first(
//                'additional_expenses',
//                ['id_application' => $application['id']],
//                ['SUM(sum) as sum']
//            )['sum'] ?? 0;
//
//            $sumAdditionalProfit = $this->database->first(
//                'additional_profit',
//                ['id_application' => $application['id']],
//                ['SUM(sum) as sum']
//            )['sum'] ?? 0;
//
//            $revenueInApplication = $application['transportation_cost_Client'] + $sumAdditionalProfit -
//                $application['transportation_cost_Carrier'] - $sumAdditionalExpenses;
//
//            if($application['application_walrus'] > $revenueInApplication AND $application['application_walrus'] > 0){
//                $strangeApplication = true;
//            }

            $tempApplicationData['strangeApplication'] = $strangeApplication;

            $tempApplicationData['application_walrus_without_tax'] = number_format(
                $tempApplicationData['application_walrus_without_tax'],
                2,
                '.',
                ' '
            );

            $statisticsArray['listPrrApplications'][] = $tempApplicationData;
            $statisticsArray['sumPrrApplicationsManagerShare'] += $application['manager_share'];
        }






        $statisticsArray['sumFineManager'] = number_format(
            $statisticsArray['sumFineManager'],
            2,
            '.',
            ' '
        );
        $statisticsArray['sumApplicationsManagerShare'] = number_format(
            $statisticsArray['sumApplicationsManagerShare'],
            2,
            '.',
            ' '
        );
        $statisticsArray['sumPrrApplicationsManagerShare'] = number_format(
            $statisticsArray['sumPrrApplicationsManagerShare'],
            2,
            '.',
            ' '
        );
        $statisticsArray['sumPaymentsManager'] = number_format(
            $statisticsArray['sumPaymentsManager'],
            2,
            '.',
            ' '
        );

        foreach ($sumPrrApplications as $key=>$sumApplication) {
            $sumPrrApplications[$key] = number_format($sumPrrApplications[$key], 2, '.', ' ');
        }

        foreach ($sumApplications as $key=>$sumApplication) {
            $sumApplications[$key] = number_format($sumApplications[$key], 2, '.', ' ');
        }

        $statisticsArray['sumApplicationsData'] = $sumApplications;
        $statisticsArray['sumPrrApplicationsData'] = $sumPrrApplications;

        $planModel = new Plan($this->database ,$salaryData['id_user']);
        $planExecution = $planModel->getInfoFromDB($salaryData['date_start'], $salaryData['date_end']);
        if(!$planExecution){
            $statisticsArray['KPI'] = '0 из 0' ;
            $statisticsArray['KPI_percent'] = 0;
            return $statisticsArray;
        }
        $typePlan = $this->database->first('types_plan',['id' => $planExecution['id_plan']]) ?? [];

        $statisticsArray['planExecution'] = $planExecution;
        $statisticsArray['typePlan'] = $typePlan;


        $statisticsArray['fixSalary'] = number_format($salary->getFixSalary(), 2, '.', ' ');


        $statisticsArray['KPI'] =
            number_format(
                $statisticsArray['planExecution']['quantity'],
                0,
                '.',
                ' ') . ' из '
            .number_format(
                $statisticsArray['typePlan']['quantity_min_25'],
                0,
                '.',
                ' '
            );

        $statisticsArray['KPI_percent'] = $statisticsArray['planExecution']['percent'];
        $statisticsArray['applicationListKPI'] = $this->getApplicationListKPI(
            $salaryData['id_user'],
            $salaryData['date_start'],
            $salaryData['date_end']
        );

        $statisticsArray['applications_new_client_sales'] = [];

        if($idSalary == 491){
            $salesNewClientApplicaitons = $this->database->superSelect('applications',['id' => [
                222, 241, 256, 330, 357, 360
            ]]);

            foreach ($salesNewClientApplicaitons as $key => $application){
                $salesNewClientApplicaitons[$key]['client'] = $this->database->first('clients', ['id' => $application['client_id_Client']]);
            }

            $statisticsArray['applications_new_client_sales'] = $salesNewClientApplicaitons;
        }


//        dd($statisticsArray);

        return $statisticsArray;

    }

    private function getApplicationListKPI(int $idManager, string $dateStart, string $dateEnd){
        $applicationList = $this->database->superSelect(
            'applications',
            [
                'id_user' => $idManager,
                'dateField' => [
                    'name' => 'date',
                    'start' => $dateStart,
                    'end' => $dateEnd . ' 23:59:59',
                ],
                'cancelled' => 0,
            ],
            [],
            -1,
            ['id','date','transportation_cost_Client', 'application_walrus', 'application_number']
        );

        $sum = 0;

        foreach ($applicationList as $key => $application){
            if($application['transportation_cost_Client'] > 0)
            $applicationList[$key]['marginality'] =
                $application['application_walrus'] / $application['transportation_cost_Client'] * 100;
            else
                $applicationList[$key]['marginality'] = 0;


            $sum += $application['application_walrus'];

            $applicationList[$key]['marginality'] = number_format(
                $applicationList[$key]['marginality'],
                2,
                '.',
                ' '
            );

            $applicationList[$key]['application_walrus'] = number_format(
                $application['application_walrus'],
                2,
                '.',
                ' '
            );
            $applicationList[$key]['date'] = date('d.m.Y', strtotime($application['date']));
        }

        $sum = number_format($sum, 2, '.', ' ');



        return ['sum' => $sum, 'list' => $applicationList];
    }

    private function getFinesManagerList(int $idSalary): array
    {
        $fineManagerList = ['list' => [], 'sumFineManager' => 0 ];
        $fineManagerListId = $this->database->select(
            'fines_manager',
            ['id_salary' => $idSalary],
            [],
            -1,
            ['id']
        );


        foreach($fineManagerListId as $fineManager){
            $tempFine = new FineManager(['id' => $fineManager['id']]);
            $tempFineData = $tempFine->get();
            $fineManagerList['sumFineManager'] += $tempFineData['quantity'];
            $tempFineData['date_create'] = date('d.m.Y', strtotime($tempFineData['date_create']));
            $tempFineData['quantity'] = number_format(
                $tempFineData['quantity'],
                2,
                '.' ,
                ' '
            );

            $fineManagerList['list'][] = $tempFineData;
        }



        return $fineManagerList;
    }

    private function getPaymentsManagerList(int $idSalary): array
    {
        $paymentsManagerList = ['list' => [], 'sumPaymentsManager' => 0 ];
        $paymentsManagerListId = $this->database->select(
            'payments_managers',
            ['id_salary' => $idSalary],
            [],
            -1,
            ['id']
        );

        foreach($paymentsManagerListId as $paymentsManager){
            $tempPaymentsManager = new PaymentsManager(['id' => $paymentsManager['id']]);
            $tempPaymentsManagerData = $tempPaymentsManager->get();
            $paymentsManagerList['sumPaymentsManager'] += $tempPaymentsManagerData['quantity'];
            $tempPaymentsManagerData['date_create'] = date(
                'd.m.Y',
                strtotime($tempPaymentsManagerData['date_create'])
            );
            $tempPaymentsManagerData['quantity'] = number_format(
                $tempPaymentsManagerData['quantity'],
                2,
                '.',
                ' '
            );
            $paymentsManagerList['list'][] = $tempPaymentsManagerData;
        }


        return $paymentsManagerList;
    }
}