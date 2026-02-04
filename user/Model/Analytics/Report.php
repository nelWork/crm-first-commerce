<?php

namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\User\Model\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Model\Application\ApplicationJournal;
use App\Model\PRR\PRRApplicationJournal;
use DateTime;


class Report extends Model
{
    private DatabaseInterface $database;

    private array $weeksPL = [
        ['2025-11-01', '2025-11-09'],
        ['2025-11-10', '2025-11-16'],
        ['2025-11-17', '2025-11-24'],
        ['2025-11-25', '2025-11-30'],
        ['2025-12-01', '2025-12-07'],
        ['2025-12-08', '2025-12-14'],
        ['2025-12-15', '2025-12-21'],
        ['2025-12-22', '2025-12-28'],
    ];

    private array $dataForReport = [
        '2025-12' => [
            'office' => 284940,
            'officeAUP' => 262840,
            'days' => '31'
        ],
    ];

    private string $pathDocTemplate = APP_PATH .'/public/doc/';


    public function __construct(DatabaseInterface $database){
        $this->database = $database;
    }


    public function styleTable($sheet, $startCell, $rowsCount, $colsCount = 2)
    {
        // Определяем координаты
        $start = $startCell; // например 'C3'
        [$col, $row] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($start);

        $startRow = $row;
        $endRow   = $row + $rowsCount - 1;

        $startColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($col);
        $endColIndex   = $startColIndex + $colsCount - 1;

        $startCol = $col;
        $endCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endColIndex);

        $range = "$startCol$startRow:$endCol$endRow";

        // 🔹 Внешняя граница таблицы
        $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // 🔹 Внутренние границы — ТОНКИЕ
        $sheet->getStyle($range)->getBorders()->getInside()->setBorderStyle(Border::BORDER_THIN);

        // 🔸 Цвет для первой строки
        $sheet->getStyle("$startCol$startRow:$endCol$startRow")
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFA500'); // оранжевый

        // жирный шрифт
        $sheet->getStyle("$startCol$startRow:$endCol$startRow")->getFont()->setBold(true);

        // 🔸 Цвет для последней строки
        $sheet->getStyle("$startCol$endRow:$endCol$endRow")
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFA500'); // оранжевый

        // жирный шрифт
        $sheet->getStyle("$startCol$endRow:$endCol$endRow")->getFont()->setBold(true);


        // 📌 Автоматическая ширина колонок
        for ($i = $startColIndex; $i <= $endColIndex; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }


    public function getReportTypeFR(array $condition = []){
        $application = $this->database->superSelect(
            'applications',
            $condition,
            [],
            -1,
            [
                'COUNT(id)',
                'SUM(transportation_cost_Carrier)',
                'SUM(transportation_cost_Client)',
                'SUM(application_net_profit)'
            ]
        )[0];

        $applicationsId  = $this->database->superSelect(
            'applications',
            $condition,
            [],
            -1,
            [
                'id'
            ]
        );

        $listIdApplication = [];

        foreach($applicationsId as $id){
            $listIdApplication[] = $id['id'];
        }

        $applicationPRR = $this->database->superSelect(
            'prr_application',
            $condition,
            [],
            -1,
            [
                'COUNT(id)',
                'SUM(cost_Prr)',
                'SUM(cost_Client)',
                'SUM(application_net_profit)'
            ]
        )[0];

        $applicationsPRRId  = $this->database->superSelect(
            'prr_application',
            $condition,
            [],
            -1,
            [
                'id'
            ]
        );

        $listIdApplicationPRR = [];

        foreach($applicationsPRRId as $id){
            $listIdApplicationPRR[] = $id['id'];
        }

        $report = [];

        $report['sum'] = [
            'quantity' => $application['COUNT(id)'] + $applicationPRR['COUNT(id)'],
            'income_amount' => $application['SUM(transportation_cost_Client)']
                + $applicationPRR['SUM(cost_Client)'],
            'expense_amount' => $application['SUM(transportation_cost_Carrier)'] +
                $applicationPRR['SUM(cost_Prr)'],
            'application_net_profit' => $application['SUM(application_net_profit)'] +
                $applicationPRR['SUM(application_net_profit)']
        ];



        $report['applications'] = $application;
        $report['applicationPRR'] = $applicationPRR;

        foreach ($report as $reportItemIndex => $reportItem) {
            foreach ($reportItem as $key => $value) {
                if($key != 'quantity' AND $key != 'COUNT(id)')
                    $report[$reportItemIndex][$key] = number_format(
                        (float)$value,
                        2,
                        ',',
                        ' '
                    );
            }
        }

        $report['listIdApplication'] = $listIdApplication;
        $report['listIdApplicationPRR'] = $listIdApplicationPRR;
        return $report;
    }

    public function createDebtorCreditReportExcel(array $data = []){
        if(empty($data))
            return false;

        $debtor = $data['debtor'];
        $credit = $data['creditor'];

        $dataTableDebtor = [
            ['','Дебиторка',''],
            ['Наименование клиента','Кол-во заявок','Сумма'],
        ];


        $dataTableCredit = [
            ['','Кредиторка',''],
            ['Наименование перевозчика/ПРР','Кол-во заявок','Сумма']
        ];

        foreach($debtor['list'] as $nameClient => $clientData){
            $dataTableDebtor[] = [
                $nameClient,
                count($clientData['items']),
                $clientData['sum']
            ];
        }


        foreach($credit['list'] as $nameCarrier => $carrierData){
            $dataTableCredit[] = [
                $nameCarrier,
                count($carrierData['items']),
                $carrierData['sum']
            ];
        }

        $dataTableCredit[] = [
            'Итого', $credit['quantityApplication'],$credit['sum']
        ];
        $dataTableDebtor[] = [
            'Итого', $debtor['quantityApplication'],$debtor['sum']
        ];

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->fromArray($dataTableDebtor, NULL, 'A1');
        $this->styleTable($sheet, 'A2', count($dataTableDebtor) - 1, count($dataTableDebtor[0]));

        $sheet->fromArray($dataTableCredit, NULL, 'F1');
        $this->styleTable($sheet, 'F2', count($dataTableCredit) - 1, count($dataTableCredit[0]));



        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'journal.xlsx');
    }
    public function createPLReportExcel(array $condition = []){
        $start = DateTime::createFromFormat('Y-m-d', trim($condition['dateField']['start']));
        $end   = DateTime::createFromFormat('Y-m-d H:i:s', trim($condition['dateField']['end']));

        $startYM = date('Y-m', strtotime($condition['dateField']['start']));

        // dd($condition, $start,$end);

        // 3. Проверяем длительность интервала
        $diffDays = $end->diff($start)->days + 1;
        $isLongRange = $diffDays > 7;


        $matchedWeeks = [];
        var_dump($condition);

        foreach ($this->weeksPL as $week) {
            $wStart = new DateTime($week[0]);
            $wEnd   = new DateTime($week[1]);

            // проверяем пересечение диапазонов
            if ($start <= $wEnd && $end >= $wStart) {
                $matchedWeeks[] = $week;
            }
        }

        $dataIncomeTable = [
            ['ДОХОДЫ',''],
            ['ЭКСПЕДИРОВАНИЕ',''],
            ['ПРР',''],
            ['',''],
            ['',''],
            ['',''],
            ['ВЫРУЧКА','']
        ];

        $sumIncomeApplications = 0;
        $sumIncomeApplicationsPrr = 0;


        $dataExpensesTable = [
            ['РАСХОДЫ',''],
            ['Транспортные услуги',''],
            ['Доп. расходы ПРР и прочие',''],
            ['Бонусы',''],
            ['Страхование',''],
            ['Расходы ПРР',''],
            ['Доп.затраты ПРР',''],
            ['',''],
            ['',''],
            ['KPI отдела продаж',''],
            ['KPI логисты',''],
            ['ИТОГО',''],
            ['МАРЖА','']
        ];

        $sumExpensesApplications = 0;
        $sumExpensesAdditionalExpenses = 0;
        $sumExpensesBonus = 0;
        $sumExpensesInsurance = 0;
        $sumExpensesApplicationsPrr = 0;
        $sumExpensesAdditionalExpensesPrr = 0;
        $sumExpensesSalesKPI = 0;
        $sumExpensesManagersKPI = 0;
        $sumWalrus = 0;
        // var_dump($startYM);
        $dataForReport = $this->dataForReport[$startYM];
        // var_dump($dataForReport);
        $office = $dataForReport['office'] * $diffDays / $dataForReport['days'];
        $officeAUP = $dataForReport['officeAUP'] * $diffDays / $dataForReport['days'];

        var_dump($diffDays);


        $dataTable3 = [
            ['РАСХОДЫ ОФИС',''],
            ['Аренда офиса',''],
            ['ЗП, СВ ФОТ отдела продаж', ''],
            ['Итого',''],
            ['Валовая прибыль',''],
        ];

        $dataTable4 = [
            ['РАСХОДЫ ОФИС, УАП',''],
            ['',''],
            ['ЗП, СВ ФОТ УАП', ''],
            ['Коммерческие, маркетинг',''],
            ['Итого',''],
            ['Операционная прибыль',''],
        ];

        $dataTable5 = [
            ['',''],
            ['Налог на прибыль',''],
            ['Кредиты, РКО',''],
            ['Прочие 120 000,00 (связь и содерж оф)',''],
            ['Итого',''],
            ['Чистая прибыль',''],
        ];
        $sumTaxationApplication = 0;
        $sumTaxationApplicationPrr = 0;


        foreach($matchedWeeks as $week){
            $condition['dateField']['start'] = $week[0];
            $condition['dateField']['end'] = date('Y-m-d', strtotime($week[1] .'+1 day'));
            $dataTable3[2][] = $dataForReport['office'] * 7 / $dataForReport['days'];
            $dataTable4[2][] = $dataForReport['officeAUP'] * 7 / $dataForReport['days'];

            $report = $this->getReportTypePL($condition);

            // $applicationJournal = new ApplicationJournal($this->database,false,['id' => ]);

            $applicationList = $report['income']['applications']['list'];
            $sumTaxationApplicationWeek = 0;
            $sumTaxationApplicationPrrWeek = 0;

            foreach($applicationList as $application){
                $applicationJournal = new ApplicationJournal($this->database,false,['id' => $application['id']]);
                $sumTaxationApplicationWeek += $applicationJournal->get()['taxation'];
            }

            $applicationPrrList = $report['income']['applicationsPrr']['list'];

            foreach($applicationPrrList as $applicationPrr){
                $applicationJournal = new PRRApplicationJournal($this->database,false,['id' => $applicationPrr['id']]);
                $sumTaxationApplicationWeek += $applicationJournal->get()['taxation'];
            }

            $dataTable5[1][] = $sumTaxationApplicationWeek;
            $sumTaxationApplication += $sumTaxationApplicationWeek;

            $date = date('d.m.Y', strtotime($week[0])) .' - '. date('d.m.Y', strtotime($week[1]));
            $dataIncomeTable[0][] = $date;

            $dataIncomeTable[1][] = $report['income']['applications']['sum'];
            $dataIncomeTable[2][] = $report['income']['applicationsPrr']['sum'];
            $dataIncomeTable[6][] = $report['income']['applications']['sum'] + $report['income']['applicationsPrr']['sum'];
            $sumIncome = $report['income']['applications']['sum'] + $report['income']['applicationsPrr']['sum'];

            $sumIncomeApplications += $report['income']['applications']['sum'];
            $sumIncomeApplicationsPrr += $report['income']['applicationsPrr']['sum'];


            $dataExpensesTable[0][] = $date;
            $dataExpensesTable[1][] = $report['expenses']['applications']['sum'];
            $dataExpensesTable[2][] = $report['expenses']['additionalExpenses']['sum'];
            $dataExpensesTable[3][] = $report['expenses']['bonus']['sum'];
            $dataExpensesTable[4][] = $report['expenses']['insurance']['sum'];
            $dataExpensesTable[5][] = $report['expenses']['applicationsPrr']['sum'];
            $dataExpensesTable[6][] = $report['expenses']['additionalExpensesPrr']['sum'];
            $dataExpensesTable[9][] = $report['expenses']['salesKPI']['sum'];
            $dataExpensesTable[10][] = $report['expenses']['managersKPI']['sum'];
            $sumExpenses = $report['expenses']['applications']['sum'] 
                                        + $report['expenses']['applicationsPrr']['sum']
                                        + $report['expenses']['additionalExpenses']['sum']
                                        + $report['expenses']['additionalExpensesPrr']['sum']
                                        + $report['expenses']['bonus']['sum']
                                        + $report['expenses']['insurance']['sum']
                                        + $report['expenses']['salesKPI']['sum']
                                        + $report['expenses']['managersKPI']['sum'];
            $dataExpensesTable[11][] = $sumExpenses;

            $sumExpensesApplications += $report['expenses']['applications']['sum'];
            $sumExpensesAdditionalExpenses += $report['expenses']['additionalExpenses']['sum'];
            $sumExpensesBonus += $report['expenses']['bonus']['sum'];
            $sumExpensesInsurance += $report['expenses']['insurance']['sum'];
            $sumExpensesApplicationsPrr += $report['expenses']['applicationsPrr']['sum'];
            $sumExpensesAdditionalExpensesPrr += $report['expenses']['additionalExpensesPrr']['sum'];
            $sumExpensesSalesKPI += $report['expenses']['salesKPI']['sum'];
            $sumExpensesManagersKPI += $report['expenses']['managersKPI']['sum'];

            $dataExpensesTable[12][] = $sumIncome - $sumExpenses;

            $sumWalrus += $sumIncome - $sumExpenses;

            $dataTable3[0][] = $date;
            $dataTable4[0][] = $date;
            $dataTable5[0][] = $date;
        }

        
        $dataIncomeTable[0][] = 'ИТОГО';
        $dataIncomeTable[1][] = $sumIncomeApplications;
        $dataIncomeTable[2][] = $sumIncomeApplicationsPrr;
        $dataIncomeTable[6][] = $sumIncomeApplications + $sumIncomeApplicationsPrr;

        // dd($dataIncomeTable);

        $dataExpensesTable[0][] = 'ИТОГО';
        $dataExpensesTable[1][] = $sumExpensesApplications;
        $dataExpensesTable[2][] = $sumExpensesAdditionalExpenses;
        $dataExpensesTable[3][] = $sumExpensesBonus;
        $dataExpensesTable[4][] = $sumExpensesInsurance;
        $dataExpensesTable[5][] = $sumExpensesApplicationsPrr;
        $dataExpensesTable[6][] = $sumExpensesAdditionalExpensesPrr;
        $dataExpensesTable[9][] = $sumExpensesSalesKPI;
        $dataExpensesTable[10][] = $sumExpensesManagersKPI;
        $dataExpensesTable[11][] = $sumExpensesApplications 
                                + $sumExpensesAdditionalExpenses 
                                + $sumExpensesBonus 
                                + $sumExpensesInsurance 
                                + $sumExpensesApplicationsPrr 
                                + $sumExpensesAdditionalExpensesPrr 
                                + $sumExpensesSalesKPI 
                                + $sumExpensesManagersKPI;

        $dataExpensesTable[12][] = $sumWalrus;

        $dataTable3[2][] = $office;
        $dataTable4[2][] = $officeAUP;
        $dataTable5[1][] = $sumTaxationApplication;

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->fromArray($dataIncomeTable, NULL, 'C3');
        $this->styleTable($sheet, 'C3', count($dataIncomeTable), count($dataIncomeTable[0]));

        $sheet->fromArray($dataExpensesTable, NULL, 'C11');
        $this->styleTable($sheet, 'C11', count($dataExpensesTable), count($dataExpensesTable[0]));

        $sheet->fromArray($dataTable3, NULL, 'C25');
        $this->styleTable($sheet, 'C25', count($dataTable3), count($dataTable3[0]) + 1);

        $sheet->fromArray($dataTable4, NULL, 'C31');
        $this->styleTable($sheet, 'C31', count($dataTable4), count($dataTable4[0]) + 1);

        $sheet->fromArray($dataTable5, NULL, 'C38');
        $this->styleTable($sheet, 'C38', count($dataTable5), count($dataTable5[0]) + 1);


        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'journal.xlsx');

    }
    public function getReportTypePL(array $condition = []){
        $dataPL = [
            'income' => [
                'applications'=> [
                    'sum' => 0,
                    'list' => []
                ],
                'applicationsPrr' => [
                    'sum' => 0,
                    'list' => []
                ]
            ],
            'expenses' => [
                'applications'=> [
                    'sum' => 0,
                    'list' => []
                ],
                'additionalExpenses' => [
                    'sum' => 0,
                    'list' => []
                ],
                'bonus' => [
                    'sum' => 0,
                    'list' => []
                ],
                'insurance' => [
                    'sum' => 0,
                    'list' => []
                ],
                'applicationsPrr' => [
                    'sum' => 0,
                    'list' => []
                ],
                'additionalExpensesPrr' => [
                    'sum' => 0,
                    'list' => []
                ],
                'salesKPI' => [
                    'sum' => 0,
                    'list' => []
                ],
                'managersKPI' => [
                    'sum' => 0,
                    'list' => []
                ],
                'walrus' => [
                    'sum' => 0,
                    'list' => []
                ],
                'tax' => [
                    'sum' => 0,
                    'list' => []
                ]
            ]
        ];

        $applications = $this->database->superSelect(
            'applications',
            $condition,
        );

        // dd($condition);

        foreach($applications as $application){
            // income
            $costClient = $application['transportation_cost_Client'];
            $taxationTypeClient = $application['taxation_type_Client'];

            $costClientWithoutTaxation = $this->getWithoutTaxation($costClient, $taxationTypeClient, true);

            $dataPL['income']['applications']['sum'] += $costClientWithoutTaxation;

            $dataPL['income']['applications']['list'][] = [
                'id' => $application['id'],
                'application_number' => $application['application_number'],
                'transportation_cost' => number_format($costClient, 2, ',', ' '),
                'cost_without_taxation' => number_format($costClientWithoutTaxation, 2, ',', ' '),
                'taxation_type' => $taxationTypeClient,
                'client' => $this->getClientName($application['client_id_Client'])
            ];


            // expenses
            $costCarrier = $application['transportation_cost_Carrier'];
            $taxationTypeCarrier = $application['taxation_type_Carrier'];

            $costCarrierWithoutTaxation = $this->getWithoutTaxation($costCarrier, $taxationTypeCarrier);

            $dataPL['expenses']['applications']['sum'] += $costCarrierWithoutTaxation;
            $dataPL['expenses']['applications']['list'][] = [
                'id' => $application['id'],
                'application_number' => $application['application_number'],
                'transportation_cost' => number_format($costCarrier,2,',',' '),
                'cost_without_taxation' => number_format($costCarrierWithoutTaxation, 2, ',', ' '),
                'taxation_type' => $taxationTypeCarrier,
                'carrier' => $this->getCarrierName($application['carrier_id_Carrier'])
            ];

            // additionalExpensesApplication
            $additionalExpensesApplication = $this->getAdditionalExpensesApplication($application['id']);
            $dataPL['expenses']['additionalExpenses']['sum'] += $additionalExpensesApplication['sum'];

            foreach($additionalExpensesApplication['list'] as $additionalExpenses){
                $additionalExpenses['application_number'] = $application['application_number'];
                $dataPL['expenses']['additionalExpenses']['list'][] = $additionalExpenses;
            }

            // insuranceApplication
            $insuranceApplication = $this->getInsuranceApplication($application['id']);

            $dataPL['expenses']['insurance']['sum'] += $insuranceApplication['sum'];
            foreach($insuranceApplication['list'] as $insurance){
                $insurance['application_number'] = $application['application_number'];
                $dataPL['expenses']['insurance']['list'][] = $insurance;
            }

            // bonusApplication
            $bonusApplication = $this->getBonusApplication($application['id']);
            $dataPL['expenses']['bonus']['sum'] += $bonusApplication['sum'];

            foreach($bonusApplication['list'] as $bonus){
                $bonus['application_number'] = $application['application_number'];
                $dataPL['expenses']['bonus']['list'][] = $bonus;
            }
            
            $dataPL['expenses']['salesKPI']['sum'] += $application['share_for_sales'];
            $dataPL['expenses']['managersKPI']['sum'] += $application['manager_share'];

            $dataPL['expenses']['tax']['sum'] += $application['application_walrus'] * 0.05;
        }

        // dd($dataPL);

        $applicationsPrr = $this->database->superSelect(
            'prr_application',
            $condition,
        );
        // dd($applicationsPrr);

        foreach($applicationsPrr as $application){
            // income
            $costClient = $application['cost_Client'];
            $taxationTypeClient = $application['taxation_type_Client'];

            $costClientWithoutTaxation = $this->getWithoutTaxation($costClient, $taxationTypeClient,true);

            $dataPL['income']['applicationsPrr']['sum'] += $costClientWithoutTaxation;

            $dataPL['income']['applicationsPrr']['list'][] = [
                'id' => $application['id'],
                'application_number' => $application['application_number'],
                'cost' => $costClient,
                'cost_without_taxation' => $costClientWithoutTaxation,
                'taxation_type' => $taxationTypeClient,
                'client' => $this->getClientName($application['client_id_Client'])
            ];

            // expenses
            $costPrr = $application['cost_Prr'];
            $taxationTypePrr = $application['taxation_type_Prr'];

            $costPrrWithoutTaxation = $this->getWithoutTaxation($costPrr, $taxationTypePrr);

            $dataPL['expenses']['applicationsPrr']['sum'] += $costPrrWithoutTaxation;
            $dataPL['expenses']['applicationsPrr']['list'][] = [
                'id' => $application['id'],
                'application_number' => $application['application_number'],
                'cost' => $costPrr,
                'cost_without_taxation' => $costPrrWithoutTaxation,
                'taxation_type' => $taxationTypePrr,
                'prr' => $this->getPrrName($application['prr_id_Prr'])

            ];

            //additionalExpensesPrr
            $additionalExpensesPrr = $this->getAdditionalExpensesApplication($application['id'],'additional_expenses_prr');
            $dataPL['expenses']['additionalExpensesPrr']['sum'] += $additionalExpensesPrr['sum'];

            foreach($additionalExpensesPrr['list'] as $additionalExpenses){
                $additionalExpenses['application_number'] = $application['application_number'];
                $dataPL['expenses']['additionalExpensesPrr']['list'][] = $additionalExpenses;
            }

            $dataPL['expenses']['salesKPI']['sum'] += $application['share_for_sales'];
            $dataPL['expenses']['managersKPI']['sum'] += $application['manager_share'];

            $dataPL['expenses']['tax']['sum'] += $application['application_walrus'] * 0.05;


        }
        // dd($dataPL);
        return $dataPL;
    }

    public function getClientName(int $clientId): string
    {
        return $this->database->first('clients',['id' => $clientId],['name'])['name'];
    }

    public function getCarrierName(int $carrierId): string{
        return $this->database->first('carriers',['id' => $carrierId],['name'])['name'];
    }

    public function getPrrName(int $prrId): string{
        return $this->database->first('prr_company',['id' => $prrId],['name'])['name'];
    }

    public function getInsuranceApplication(int $idApplication): array{
        $data = [
            'sum' => 0,
            'list' => []
        ];

        $additionalExpenses = $this->database->select(
            'additional_expenses', 
            [
                'id_application' => $idApplication,
                'type_expenses' => 'Страховка'
            ]
        );


        foreach($additionalExpenses as $expense){
            $data['list'][] = $expense;
            $data['sum'] += $this->getWithoutTaxation($expense['sum'], $expense['type_payment']);
        }

        return $data;
    }

    public function getBonusApplication(int $idApplication): array
    {

        $data = [
            'sum' => 0,
            'list' => []
        ];
        $additionalExpenses = $this->database->select(
            'additional_expenses', 
            [
                'id_application' => $idApplication,
                'type_expenses' => 'Вычет'
            ]
        );


        foreach($additionalExpenses as $expense){
            $data['list'][] = $expense;
            $data['sum'] += $this->getWithoutTaxation($expense['sum'], $expense['type_payment']);
        }

        return $data;
    }

    public function getAdditionalExpensesApplication(int $idApplication, string $nameTable = 'additional_expenses'): array
    {
        $data = [
            'sum' => 0,
            'list' => [
            ]
        ];

        $additionalExpenses = $this->database->select(
            $nameTable, 
            [
                'id_application' => $idApplication
            ]
        );

        foreach($additionalExpenses as $expense){
            if($expense['type_expenses'] == 'Вычет' OR $expense['type_expenses'] == 'Страховка') continue;

            $data['list'][] = $expense;
            $data['sum'] += $this->getWithoutTaxation($expense['sum'], $expense['type_payment']);
        }

        return $data;
    }

    public function getWithoutTaxation(float $cost, string $taxationType = '', bool $forClient = false): float{

        switch ($taxationType) {
            case 'Б/НДС':
                return $cost;
                break;
            case 'С НДС':
                return $cost / 1.2;
                break;
            case 'НАЛ':
                if($forClient)
                    return $cost;
                return $cost / 0.85 / 1.2;
                break;
        }

        return $cost;
    }

    public function getReportTypeDDS(array $condition = []){
        $condition['side'] = 1;
        $historyPaymentCarrier = $this->database->superSelect(
            'history_payment',
            $condition,
            [],
            -1,
            ['sum(quantity)']
        )[0];

        $condition['side'] = 0;

        $historyPaymentClient = $this->database->superSelect(
            'history_payment',
            $condition,
            [],
            -1,
            ['sum(quantity)']
        )[0];

        return ['carrier' => $historyPaymentCarrier, 'client' => $historyPaymentClient];

    }



}