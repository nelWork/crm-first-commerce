<?php

namespace App\User\Model\Base;

use App\User\Model\Model;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BaseDocuments extends Model
{
    private string $pathDocTemplate = APP_PATH .'/public/doc/';
    public function createListClientExcel(array $listClients = [])
    {
        $data = [];

        $data[] = [
            'Название',
            'ИНН',
            'Формат работы',
            'Отсрочка платежа',
            'Логист',
            'Кол-во заявок'
        ];

        foreach ($listClients as $client) {

            $managersText = '';

            foreach ($client['users'] as $manager) {
                $managersText .=  $manager['surname'] .' ' .$manager['name'] . ', ';
            }

            $data[] = [
                $client['name'],
                $client['inn'],
                $client['format_work'],
                $client['payment_deferment'],
                $managersText,
                $client['quantity']
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

        $writer->save($this->pathDocTemplate .'list-clients.xlsx');
    }
}