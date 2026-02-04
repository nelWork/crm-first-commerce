<?php

namespace App\User\Model\Analytics;

use App\User\Model\Model;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnalyticsDocuments extends Model
{
    private string $pathDocTemplate = APP_PATH .'/public/doc/';
    public function createManagersExcel(array $initialData = [], array $listManager = [], 
        bool $forRop = false, string $nameFile = 'managers-report.xlsx',bool $hasSalary = true)
    {

        $data = [];

        if($hasSalary){
            $data[] = [
                'Имя',
                'Кол-во заявок',
                'ЗП',
                'Чистая прибыль',
                'Маржа',
            ];

            $data[] = [
                'Итого',
                $initialData['countApplication'],
                $initialData['sumSalary'],
                $initialData['netProfit'],
                $initialData['sumWalrus'],
            ];
        }
        else{
            $data[] = [
                'Имя',
                'Кол-во заявок',
                'Чистая прибыль',
                'Маржа',
            ];

            $data[] = [
                'Итого',
                $initialData['countApplication'],
                // $initialData['sumSalary'],
                $initialData['netProfit'],
                $initialData['sumWalrus'],
            ];
        }

        

        if($forRop){
            $data[0] = [
                'Имя',
                'Кол-во заявок',
                'ЗП',
                'Маржа',
            ];

            $data[1] = [
                'Итого',
                $initialData['countApplication'],
                $initialData['sumSalary'],
                $initialData['sumWalrus'],
            ];
        }

        foreach ($initialData['list'] as $managerData) {

            if($forRop){
                $data[] = [
                    $managerData['name'],
                    $managerData['count-application'],
                    $managerData['salary'],
                    $managerData['revenue'],
                ];
            }
            else{
                if($hasSalary){
                    $data[] = [
                        $managerData['name'],
                        $managerData['count-application'],
                        $managerData['salary'],
                        $managerData['net_profit'],
                        $managerData['revenue'],
                    ];
                }
                else{
                    $data[] = [
                        $managerData['name'],
                        $managerData['count-application'],
                        // $managerData['salary'],
                        $managerData['net_profit'],
                        $managerData['revenue'],
                    ];
                }
                
            }



            if(isset($managerData['subordinates'])){

                foreach ($managerData['subordinates'] as $subordinateData) {
                    if($forRop){
                        $data[] = [
                            $subordinateData['name'],
                            $subordinateData['count-application'],
                            $subordinateData['salary'],
                            $subordinateData['revenue'],
                        ];
                    }
                    else {
                        if($hasSalary){
                            $data[] = [
                                $subordinateData['name'],
                                $subordinateData['count-application'],
                                $subordinateData['salary'],
                                $subordinateData['net_profit'],
                                $subordinateData['revenue'],
                            ];
                        }
                        else{
                            $data[] = [
                                $subordinateData['name'],
                                $subordinateData['count-application'],
                                // $subordinateData['salary'],
                                $subordinateData['net_profit'],
                                $subordinateData['revenue'],
                            ];
                        }
                        
                    }
                }

                if(!$forRop){
                    if($hasSalary){
                        $data[] =[
                            'Итоги ОТДЕЛА',
                            $managerData['rop-count-application'],
                            $managerData['sum-salary'],
                            $managerData['net-profit'],
                            $managerData['sum-walrus'],
                        ];
                    }
                    else{
                        $data[] =[
                            'Итоги ОТДЕЛА',
                            $managerData['rop-count-application'],
                            // $managerData['sum-salary'],
                            $managerData['net-profit'],
                            $managerData['sum-walrus'],
                        ];
                    }
                    
                    
                }

            }


        }

        $header = array_shift($data); // Убираем заголовок в отдельную переменную
//        usort($data, function ($a, $b) {
//            return $b[count($b) - 1] <=> $a[count($a) - 1]; // Сортировка по последнему столбцу
//        });

        array_unshift($data, $header);


//        var_dump($data);

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, '-', 'A1');
        $sheet->setTitle('Общая аналитика');


        $startCell = 'A1';
        $colCount = count($data[0]); // Количество колонок
        $rowCount = count($data);    // Количество строк

        $lastColumn = Coordinate::stringFromColumnIndex($colCount);
        $lastRow = $sheet->getHighestRow();
        $range = "A1:{$lastColumn}{$lastRow}"; // Диапазон таблицы

// Применяем границы ко всей таблице
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($styleArray);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $boldRange = "A1:{$lastColumn}2";
        $sheet->getStyle($boldRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        // Добавляем выделение для строк "Итоги ОТДЕЛА"
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $cellValue = $sheet->getCell("A{$rowIndex}")->getValue();

            if ($cellValue === 'Итоги ОТДЕЛА') {
                $range = "A{$rowIndex}:{$lastColumn}{$rowIndex}";
                $sheet->getStyle($range)->getFont()->setBold(true);
            }
        }



        $listSpreadsheet = $this->getListManagerWorksheet($listManager,$forRop);

        foreach ($listSpreadsheet as $index => $sheet) {
            $spreadsheet->addSheet($sheet);
        }

        $writer = new Xlsx($spreadsheet);



        $writer->save($this->pathDocTemplate .$nameFile);
    }

    private function getListManagerWorksheet(array $listManager = [],bool $forRop = false): array
    {
        $spreadsheet = new Spreadsheet(); // Создаём объект Spreadsheet
        $worksheets = []; // Массив для листов Excel

        foreach ($listManager as $index => $managerData) {
            // Создаём новый лист для каждого менеджера
            $sheet = ($index === 0)
                ? $spreadsheet->getActiveSheet() // Первый лист — активный
                : $spreadsheet->createSheet();  // Остальные создаются

            // Устанавливаем название листа как имя менеджера
            $managerName = !empty($managerData['name']) ? $managerData['name'] : 'Неизвестный менеджер';
            $sheet->setTitle(mb_substr($managerName, 0, 31)); // Названия листа ограничены 31 символом

            // Заголовки таблицы клиентов


            $header = [
                'Клиент',
                'Кол-во заявок',
                'ЗП',
                'Чистая прибыль',
                'Маржа',
            ];

            if($forRop){
                $header = [
                    'Клиент',
                    'Кол-во заявок',
                    'ЗП',
                    'Маржа',
                ];
            }

            // Составляем данные для листа
            $data = [$header];
            foreach ($managerData['clients'] as $client) {
                if($forRop){
                    $data[] = [
                        $client['clientName'],
                        $client['countApplication'],
                        $client['sumSalary'],
                        $client['sumWalrus'],
                    ];
                }
                else {
                    $data[] = [
                        $client['clientName'],
                        $client['countApplication'],
                        $client['sumSalary'],
                        $client['netProfit'],
                        $client['sumWalrus'],
                    ];
                }
            }

            // Заносим данные на лист
            $sheet->fromArray($data, null, 'A1');

            // Форматируем таблицу
            $lastColumn = Coordinate::stringFromColumnIndex(count($header));
            $lastRow = count($data);
            $range = "A1:{$lastColumn}{$lastRow}";

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];

            $sheet->getStyle($range)->applyFromArray($styleArray);

            // Устанавливаем авто-ширину для колонок
            foreach (range('A', $lastColumn) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Заголовок делаем жирным
            $headerRange = "A1:{$lastColumn}1";
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);

            // Добавляем лист в массив
            $worksheets[] = $sheet;
        }

        return $worksheets;
    }

    public function createListManagerApplicationExcel(array $listApplication = [])
    {
        $data = [];

        $data[] = [
            'Номер заявки',
            'Номер заявки, клиент',
            'Дата',
            'Статус заявки',
            'Невыполненные условия',
            'Есть в ЗП',
            'Номер счета',
            'Клиент',
            'Стоимость, клиент',
            'Сумма без НДС',
            'НДС',
            'Вид налогообложения, клиент',
            'Факт. оплата',
            'Перевозчик',
            'Стоимость, перевозчик',
            'Сумма без НДС',
            'НДС',
            'Вид налогообложения, перевозчик',
            'Факт. оплата',
            'Доп затраты, С НДС',
            'Доп затраты, Б/НДС',
            'Доп затраты, НАЛ',
            'Доп затраты, текст',
            'Маржа с заявки',
            'ЗП'
        ];

        foreach ($listApplication as $application) {
            $textUnfulfilledConditions = '';

            foreach ($application['unfulfilledConditions'] as $condition) {
                $textUnfulfilledConditions .=  $condition ." \n";
            }
            $status = 'Актуальная';
            switch ($application['application_section_journal']) {
                case 2:
                    $status = 'Завершенная';
                    break;
                case 3:
                    $status = 'Закрыта под расчет';
                    break;
                case 6:
                    $status = 'Закрыта под документы';
                    break;
                case 4:
                case 5:
                    $status = 'Отмененная';

            }
            $data[] = [
                $application['application_number'],
                $application['application_number_client'],
                $application['date'],
                $status,
                $textUnfulfilledConditions,
                $application['into_salary'],
                $application['account_number_Client'],
                $application['client'],
                $application['transportation_cost_Client'],
                $application['transportationCostClientWithoutNDS'],
                $application['transportationCostClientNDS'],
                $application['taxation_type_Client'],
                $application['actual_payment_Client'],
                $application['carrier'],
                $application['transportation_cost_Carrier'],
                $application['transportationCostCarrierWithoutNDS'],
                $application['transportationCostCarrierNDS'],
                $application['taxation_type_Carrier'],
                $application['actual_payment_Carrier'],
                $application['additionalExpensesSumNDS'],
                $application['additionalExpensesSumBezNDS'],
                $application['additionalExpensesSumNAL'],
                $application['additionalExpensesText'],
                $application['application_walrus'],
                $application['manager_share']
            ];
        }




        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, '-', 'A1');


        $startCell = 'A1';
        $colCount = count($data[0]); // Количество колонок
        $rowCount = count($data);    // Количество строк

        $lastColumn = Coordinate::stringFromColumnIndex($colCount);
        $lastRow = $sheet->getHighestRow();
        $range = "A1:{$lastColumn}{$lastRow}"; // Диапазон таблицы

// Применяем границы ко всей таблице
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle($range)->applyFromArray($styleArray);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $boldRange = "A1:{$lastColumn}1";
        $sheet->getStyle($boldRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);



        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'list-manager-applications.xlsx');
    }



}