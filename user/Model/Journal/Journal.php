<?php

namespace App\User\Model\Journal;

use App\Database\DatabaseInterface;
use App\Model\Application\Application;
use App\Model\Application\ApplicationJournal;
use App\Model\PRR\PRRApplicationJournal;
use App\Model\TSApplication\TSApplicationJournal;
use App\User\Model\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Journal extends Model
{

    private string $pathDocTemplate = APP_PATH .'/public/doc/';

    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }

    public function createExcelTableNoProfit(array $conditions = []){
        $listApplications = $this->getListApplication($conditions);


        $data1[] = [
            'Логист',
            '№ заявки, перевозчик',
            // '№ заявки, клиент',
            'Заказчик (Юр. лицо), клиент',
            'Дата заявки',
            'Дата погрузки',
            'Дата разгрузки',
            // 'Актуальная дата разгрузки',
            // 'ТТН',
            // 'ТТН отправлено',
            'Название клиента',
            // 'Номер счета и дата',
            // 'Номер УПД и дата',
            'Общая сумма',
            'Сумма без НДС',
            'НДС',
            // 'Фактическая сумма оплаты',
            'Название перевозчика',
            // 'Номер счета и дата ',
            // 'Номер УПД и дата ',
            'Общая сумма ',
            'Сумма без НДС ',
            'НДС ',
            // 'Фактическая сумма оплаты',
            'Доход',
            'Чистая прибыль',
            'З.П. Логист',
            // 'З.П. РОП',
            // 'З.П. Отдел продаж',
            // 'Налог на прибыль',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            // 'Доп. затраты информация',
            // 'Статус по перевозчику'
        ];

        $data2 = [
        ];

        $data2[] = [
            'Логист',
            '№ заявки, перевозчик',
            // '№ заявки, клиент',
            'Заказчик (Юр. лицо), клиент',
            'Дата заявки',
            'Дата погрузки',
            'Дата разгрузки',
            // 'Актуальная дата разгрузки',
            // 'ТТН',
            // 'ТТН отправлено',
            'Название клиента',
            // 'Номер счета и дата',
            // 'Номер УПД и дата',
            'Общая сумма',
            'Сумма без НДС',
            'НДС',
            // 'Фактическая сумма оплаты',
            'Название перевозчика',
            // 'Номер счета и дата ',
            // 'Номер УПД и дата ',
            'Общая сумма ',
            'Сумма без НДС ',
            'НДС ',
            // 'Фактическая сумма оплаты',
            'Доход',
            'Чистая прибыль',
            'З.П. Логист',
            // 'З.П. РОП',
            // 'З.П. Отдел продаж',
            // 'Налог на прибыль',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            // 'Доп. затраты информация',
            // 'Статус по перевозчику'
        ];

        foreach ($listApplications as $application) {

            if(!$application['application_number_Client'])
                $application['application_number_Client'] = '—';

            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] =  date('d.m.Y', strtotime($application['application_date_actual_unloading']));

            $application['date-loading'] = '';
            $application['date-unloading'] = '';

            foreach ($application['transportation_list'] as $item) {
                if ($item['direction']) {
                    $application['date-loading'] .= $item['date'] . ' ';
                }
                else{
                    $application['date-unloading'] .= $item['date'] . ' ';
                }
            }

            $application['client'] = $application['client_data']['name'] . '(' .$application['client_data']['inn'] .')';
            if($application['client_data']['format_work'])
                $application['client'] .= " (" .$application['client_data']['format_work'] . ')';

            $application['carrier'] = $application['carrier_data']['name'] . ' (' . $application['carrier_data']['inn'] .')';


            $textCustomerClient = 'ООО  Логистика';
            $textCustomerCarrier = 'ООО  Логистика';

            switch ($application['customer_id_Client']){
                case 2:
                    $textCustomerClient =  '(ИП Иванов Иван Иванович)';
                    break;
            }


            $textAdditionalExpenses = '';

            $sumAdditionalExpensesNDS = 0;
            $sumAdditionalExpensesBezNDS = 0;
            $sumAdditionalExpensesNAL = 0;

            foreach ($application['additional_expenses'] as $expenses){
                $textAdditionalExpenses .= $expenses['type_expenses'] ." (" .$expenses['type_payment']
                                        .' - '   .$expenses['sum'] .') ';
                switch ($expenses['type_payment']){
                    case 'С НДС':
                        $sumAdditionalExpensesNDS += (float)$expenses['sum'];
                        break;
                    case 'Б/НДС':
                        $sumAdditionalExpensesBezNDS += (float)$expenses['sum'];
                        break;
                    case 'НАЛ':
                        $sumAdditionalExpensesNAL += (float)$expenses['sum'];
                        break;
                }
            }

            $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'];
            $transportationCostCarrierNDS = '0';

            if($application['taxation_type_Carrier'] == 'С НДС') {
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.2;

                $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;
            }



            $transportationCostClientWithoutNDS = $application['transportation_cost_Client'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type_Client'] == 'С НДС') {
                $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.2;

                $transportationCostClientNDS = $application['transportation_cost_Client'] / 6;

            }



            if($application['application_walrus'] > 0){
                $data1[] = [
                    $application['manager'],
                    $application['application_number'],
                    // $application['application_number_Client'],
                    $textCustomerClient,
                    date('d.m.Y', strtotime($application['date'])),
                    $application['date-loading'],
                    $application['date-unloading'],
                    // $application['application_date_actual_unloading'],
                    // '',
                    // '',
                    $application['client'],
                    // $application['account_number_Client'],
                    // $application['upd_number_Client'],
                    $application['transportation_cost_Client'],
                    $transportationCostClientWithoutNDS,
                    $transportationCostClientNDS,
                    // $application['actual_payment_Client'],
                    $application['carrier'],
                    // $application['account_number_Carrier'],
                    // $application['upd_number_Carrier'],
                    $application['transportation_cost_Carrier'],
                    $transportationCostCarrierWithoutNDS,
                    $transportationCostCarrierNDS,
                    // $application['actual_payment_Carrier'],
                    $application['application_walrus'],
                    $application['application_net_profit'],
                    $application['manager_share'],
                    // $application['manager_share'],
                    // $application['share_for_sales'],
                    // $application['taxation'],
                    $sumAdditionalExpensesNDS,
                    $sumAdditionalExpensesBezNDS,
                    $sumAdditionalExpensesNAL,
                    // $textAdditionalExpenses,
                    // ''
                ];
            }
            else{
                $data2[] = [
                    $application['manager'],
                    $application['application_number'],
                    // $application['application_number_Client'],
                    $textCustomerClient,
                    date('d.m.Y', strtotime($application['date'])),
                    $application['date-loading'],
                    $application['date-unloading'],
                    // $application['application_date_actual_unloading'],
                    // '',
                    // '',
                    $application['client'],
                    // $application['account_number_Client'],
                    // $application['upd_number_Client'],
                    $application['transportation_cost_Client'],
                    $transportationCostClientWithoutNDS,
                    $transportationCostClientNDS,
                    // $application['actual_payment_Client'],
                    $application['carrier'],
                    // $application['account_number_Carrier'],
                    // $application['upd_number_Carrier'],
                    $application['transportation_cost_Carrier'],
                    $transportationCostCarrierWithoutNDS,
                    $transportationCostCarrierNDS,
                    // $application['actual_payment_Carrier'],
                    $application['application_walrus'],
                    $application['application_net_profit'],
                    $application['manager_share'],
                    // $application['manager_share'],
                    // $application['share_for_sales'],
                    // $application['taxation'],
                    $sumAdditionalExpensesNDS,
                    $sumAdditionalExpensesBezNDS,
                    $sumAdditionalExpensesNAL,
                    // $textAdditionalExpenses,
                    // ''
                ];
            }
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Заявки с низкой маржой'], NULL, 'A1');

        $sheet->fromArray($data1, NULL, 'A2');

        // ----------------------------
// 1. Узнаём, где закончилась таблица data1
// ----------------------------
$rowsData1 = count($data1);
$startData2Row = 2 + $rowsData1 + 4; // A2 + data1 + 4 пустые строки

// ----------------------------
// 2. Вторая таблица (data2)
// ----------------------------
$sheet->fromArray(['Заявки в "минус"'], NULL, "A{$startData2Row}");
$startData2Row += 1;
$sheet->fromArray($data2, NULL, "A{$startData2Row}");

// ----------------------------
// 3. Применяем границы к обеим таблицам
// ----------------------------
$styleBorders = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

// --- Первая таблица: границы ---
$lastCol = chr(ord('A') + count($data1[0]) - 1); // вычисляем последнюю колонку
$sheet->getStyle("A2:{$lastCol}" . (1 + $rowsData1))
    ->applyFromArray($styleBorders);

// --- Вторая таблица: границы ---
$rowsData2 = count($data2);
$lastCol2 = chr(ord('A') + count($data2[0]) - 1);
$sheet->getStyle("A{$startData2Row}:{$lastCol2}" . ($startData2Row + $rowsData2 - 1))
    ->applyFromArray($styleBorders);

// ----------------------------
// 4. Автоширина столбцов
// ----------------------------

foreach (range('A', 'Z') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}


        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'journal.xlsx');
    }

    public function getListApplication(array $conditions = [],$isManager = true, string $selectionOperation = 'AND'): array
    {
        $applicationsDatabase = $this->database->superSelect('applications', $conditions,['id' => 'DESC'],-1,['id'],0,$selectionOperation);


//         dd($applicationsDatabase);
        
        if(isset($conditions['application_walrus']) AND $conditions['application_walrus'] == 0){
//            dd($conditions);
            $applicationsDatabase = $this->database->superSelect(
                'applications',
                ['application_walrus' => 0],
                ['id' => 'DESC'],
                -1,
                ['id','cancelled','application_walrus'],
                0,
                $selectionOperation,
                '<='
            );
//            dd($applicationsDatabase);
            foreach($applicationsDatabase as $key => $application){
                if($application['cancelled'])
                    unset($applicationsDatabase[$key]);
            }
        }

        if(! $applicationsDatabase)
            $applicationsDatabase = [];
        $listApplications = [];

        $listUserDB = $this->database->select(
            'users',
            ['role' => 1],[],-1,['id', 'name', 'surname']
        );

        $listUser = [];

        foreach ($listUserDB as $user) {
            $listUser[$user['id']] = $user['name'] . ' ' . $user['surname'];
        }

        foreach ($applicationsDatabase as $applicationDb) {
            $application = new ApplicationJournal($this->database,$isManager,['id' => $applicationDb['id']]);
            $temp = $application->get();

            if(isset($conditions['for_sales'])){
                $temp['manager_share'] = $temp['share_for_sales'];
            }

            $additionalIdDocument = [];

            $counterAdditional = 1;
            if(strtotime($temp['date']) > strtotime('2025-04-01')) {
                foreach ($temp['additional_expenses'] as $expenses) {
                    if ($expenses['type_expenses'] !== 'Страховка' and $expenses['type_expenses'] !== 'Вычет') {
                        $additionalIdDocument[] = 100 + $counterAdditional;
                        $counterAdditional++;
                    }
                }
            }

            $listUser[1] = 'admin';
            $temp['manager'] = $listUser[$temp['id_user']];

            if($temp['application_section_journal'] == 2){
                $listIdDocument = array_merge([1,2,10,13,14], $additionalIdDocument);

                $files = $this->database->superSelect(
                    'files',
                    ['application_id' => $temp['id'], 'document_id' => $listIdDocument]
                ) ?? [];

                $listNameDocument = [
                    0 => 'Оплата от клиента (<b>Б</b>)',
                    1 => 'Подписанная заявка от перевозчика (<b>Л</b>)',
                    2 => 'Фото ТТН/ТН/ЭР с выгрузки (<b>Л</b>)',
                    10 => 'Подписанная заявка от клиента (<b>Л</b>)',
                    13 => 'Счета для клиента (<b>Б</b>)',
                    14 => 'АКТ/УПД для клиента (<b>Б</b>)'
                ];

                $counterAdditional = 1;

                if(strtotime($temp['date']) > strtotime('2025-04-01')) {
                    foreach ($temp['additional_expenses'] as $expenses) {
                        if ($expenses['type_expenses'] !== 'Страховка' and $expenses['type_expenses'] !== 'Вычет') {
                            $listNameDocument[100 + $counterAdditional] = 'Доп затрата - ' . $expenses['type_expenses'] . ' ('
                                . number_format((float)$expenses['sum'], 0, '.', ' ') . ' ₽ '
                                . $expenses['type_payment'] . ') (<b>Л</b>)';
                            $counterAdditional++;
                        }
                    }
                }

                foreach ($files as $file) {
                    foreach ($listIdDocument as $condition) {
                        if($file['document_id'] == $condition){
                            unset($listNameDocument[$condition]);
                        }
                    }
                }

                if($temp['actual_payment_Client'] === $temp['transportation_cost_Client'])
                    unset($listNameDocument[0]);

                $temp['unfulfilledConditions'] = $listNameDocument;

            }

            if($temp['application_section_journal'] == 3){
                $listIdDocument = [18,4,5];

                $files = $this->database->superSelect(
                    'files',
                    ['application_id' => $temp['id'], 'document_id' => $listIdDocument]
                ) ?? [];

                $listNameDocument = [
                    0 => 'Оплата от перевозчика',
                    4 => 'Счет от перевозчика',
                    5 => 'Акт/УПД от перевозчика',
                    18 => 'Сканы ТТН/ТН/ЭР'
                ];

                $listNameCommentDocument = [
                    'Трек-номер отправки',
                    'Комментарий об отправленных документах',
                    'Комментарий о полученных документах для клиента',
                    'Комментарий о полученных документах'
                ];

                foreach ($files as $file) {
                    foreach ($listIdDocument as $condition) {
                        if($file['document_id'] == $condition){
                            unset($listNameDocument[$condition]);
                        }
                    }
                }

                $commentsDocument = $this->database->superSelect(
                    'application_document_comment',
                    ['id_application' => $temp['id'], 'name' => $listNameCommentDocument]
                ) ?? [];


                foreach ($commentsDocument as $comment) {
                    foreach ($listNameCommentDocument as $key => $documentName) {
                        if($comment['name'] == $documentName AND $comment['comment'] != ''){
                            unset($listNameCommentDocument[$key]);
                        }
                    }
                }

                $listNameDocument += $listNameCommentDocument;

                if($temp['actual_payment_Carrier'] === $temp['transportation_cost_Carrier'])
                    unset($listNameDocument[0]);

                $temp['unfulfilledConditions'] = $listNameDocument;


            }

            $temp['type-application'] = '';
            $listApplications[] = $temp;

        }

        return $listApplications;
    }

    public function getListApplicationSearch(array $conditions = []): array
    {
        // dump($conditions);
        $applicationsDatabase = $this->database->superSelect('applications', $conditions,['id' => 'DESC'],-1,['id'],0,'AND','LIKE');
        // dump($applicationsDatabase);

        if(! $applicationsDatabase)
            $applicationsDatabase = [];
        
        $listApplications = [];

        foreach ($applicationsDatabase as $applicationDb) {
            $application = new ApplicationJournal($this->database, false,['id' => $applicationDb['id']] );


            $temp = $application->get();

            $tempUserDB = $this->database->select('users',['id' => $temp['id_user']], [], 1 ,['name','surname']);
            $temp['manager'] = $tempUserDB[0]['name'] .' '.$tempUserDB[0]['surname'];

            $listApplications[] = $temp;
        }
        return $listApplications;
    }
    public function getListPRRApplicationSearch(array $conditions = []): array
    {
        $applicationsDatabase = $this->database->superSelect('prr_application', $conditions,['id' => 'DESC'],-1,['id'],0,'AND','LIKE');

        if(! $applicationsDatabase)
            $applicationsDatabase = [];
        $listApplications = [];

        foreach ($applicationsDatabase as $applicationDb) {
            $application = new PRRApplicationJournal(
                $this->database,
                false,
                ['id' => $applicationDb['id']]
            );


            $temp = $application->get();

            $tempUserDB = $this->database->select('users',['id' => $temp['id_user']], [], 1 ,['name','surname']);
            $temp['manager'] = $tempUserDB[0]['name'] .' '.$tempUserDB[0]['surname'];

            $listApplications[] = $temp;
        }
        return $listApplications;
    }

    public function getListUniqueData($listApplication = []): array
    {
        // dd($listApplication);
        $uniqueData = [
            'manager' => [],
            'application_number' => [],
            'applications_number_client' => [],
            'date' => [],
            'date_loading' => [],
            'date_unloading' => [],
            'date_actual_unloading' => [],
            'ttn' => [],
            'ttn_send' => [],
            'client' => [],
            'cost_client' => [],
            'actual_payment_client' => [],
            'carrier' => [],
            'cost_carrier' => [],
            'actual_payment_carrier' => [],
            'application_walrus' => [],
            'manager_share' => [],
            'net_profit' => []
        ];

        $uniqueData['manager'] = array_unique(array_column($listApplication, 'id_user'));
        $uniqueData['application_number'] = array_unique(array_column($listApplication, 'application_number'));
        $uniqueData['cost_client'] = array_unique(array_column($listApplication, 'transportation_cost_Client'));
        $uniqueData['actual_payment_client'] = array_unique(array_column($listApplication, 'actual_payment_Client'));
        $uniqueData['cost_carrier'] = array_unique(array_column($listApplication, 'transportation_cost_Carrier'));
        $uniqueData['actual_payment_carrier'] = array_unique(array_column($listApplication, 'actual_payment_Carrier'));
        $uniqueData['application_walrus'] = array_unique(array_column($listApplication, 'application_walrus'));
        $uniqueData['net_profit'] = array_unique(array_column($listApplication, 'application_net_profit'));
        $uniqueData['manager_share'] = array_unique(array_column($listApplication, 'manager_share'));
        $uniqueData['marginality'] = array_unique(array_column($listApplication, 'marginality'));


        $uniqueData['date_unloading_str'] = [];
        $uniqueClientsId = array_unique(array_column($listApplication, 'client_id_Client'));
        $uniqueCarriersId = array_unique(array_column($listApplication, 'carrier_id_Carrier'));

        foreach ($listApplication as $key => $application) {
            $listApplication[$key]['date'] = date('d.m.Y', strtotime($application['date']));
            if($application['application_date_actual_unloading'])
                $listApplication[$key]['application_date_actual_unloading'] = date('d.m.Y', strtotime($application['application_date_actual_unloading']));
            else
                $listApplication[$key]['application_date_actual_unloading'] = 'не указана';

            foreach ($uniqueClientsId as $keyClientId =>$clientId) {
                if($application['client_id_Client'] == $clientId) {
                    $uniqueData['client'][] = ['id' => $clientId, 'name' => $application['client_data']['name']];
                     unset($uniqueClientsId[$keyClientId]);
                     break;
                }
            }


            foreach ($uniqueCarriersId as $keyCarrierId =>$carrierId) {
                if($application['carrier_id_Carrier'] == $carrierId) {
                    $uniqueData['carrier'][] = ['id' => $carrierId, 'name' => $application['carrier_data']['name']];
                    unset($uniqueCarriersId[$keyCarrierId]);
                    break;
                }
            }
            $strDateUnload = '';
            foreach ($application['transportation_list'] as $transportation) {
                if(!$transportation['direction']){
                    $strDateUnload .= $transportation['date'].', ';
                }
            }

            $strDateUnload = trim($strDateUnload, ', ');

            $uniqueData['date_unloading_str'][] = $strDateUnload;

        }


        $uniqueData['date'] = array_unique(array_column($listApplication, 'date'));
        $uniqueData['application_date_actual_unloading'] = array_unique(array_column($listApplication, 'application_date_actual_unloading'));


        $uniqueData['date_unloading_str'] = array_unique($uniqueData['date_unloading_str']);


        sort($uniqueData['cost_carrier']);
        sort($uniqueData['date_unloading_str']);
        sort($uniqueData['date']);
        sort($uniqueData['application_date_actual_unloading']);
        sort($uniqueData['marginality']);
        sort($uniqueData['cost_client']);
        sort($uniqueData['application_walrus']);
        sort($uniqueData['actual_payment_client']);
        sort($uniqueData['actual_payment_carrier']);

        return $uniqueData;
    }

    public function createExcelTable(array $conditions = [], array $filter = [])
    {
        $listApplications = $this->getListApplication($conditions);


        $data = [];

        $data[] = [
            'Логист',
            '№ заявки, перевозчик',
            '№ заявки, клиент',
            'Заказчик (Юр. лицо), клиент',
            'Дата заявки',
            'Дата погрузки',
            'Дата разгрузки',
            'Актуальная дата разгрузки',
            'ТТН',
            'ТТН отправлено',
            'Название клиента',
            'Номер счета и дата',
            'Номер УПД и дата',
            'Общая сумма',
            'Сумма без НДС',
            'НДС',
            'Фактическая сумма оплаты',
            'Название перевозчика',
            'Номер счета и дата ',
            'Номер УПД и дата ',
            'Общая сумма ',
            'Сумма без НДС ',
            'НДС ',
            'Фактическая сумма оплаты',
            'Доход',
            'Чистая прибыль',
            'З.П. Логист',
            'З.П. РОП',
            'З.П. Отдел продаж',
            'Налог на прибыль',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            'Доп. затраты информация',
            'Статус по перевозчику'
        ];

        foreach ($listApplications as $application) {

            if($filter['accountFilterNumberClient'] == 1 AND $application['account_number_Client'] != '')
                continue;

            if($filter['accountFilterNumberClient'] == 2 AND $application['account_number_Client'] == '')
                continue;

            if(!$application['application_number_Client'])
                $application['application_number_Client'] = '—';

            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] =  date('d.m.Y', strtotime($application['application_date_actual_unloading']));

            $application['date-loading'] = '';
            $application['date-unloading'] = '';

            foreach ($application['transportation_list'] as $item) {
                if ($item['direction']) {
                    $application['date-loading'] .= $item['date'] . ' ';
                }
                else{
                    $application['date-unloading'] .= $item['date'] . ' ';
                }
            }

            $application['client'] = $application['client_data']['name'] . '(' .$application['client_data']['inn'] .')';
            if($application['client_data']['format_work'])
                $application['client'] .= " (" .$application['client_data']['format_work'] . ')';

            $application['carrier'] = $application['carrier_data']['name'] . ' (' . $application['carrier_data']['inn'] .')';


            $textCustomerClient = 'ООО  Логистика';
            $textCustomerCarrier = 'ООО  Логистика';

            switch ($application['customer_id_Client']){
                case 2:
                    $textCustomerClient =  '(ИП Иванов Иван Иванович)';
                    break;
                
            }


            $textAdditionalExpenses = '';

            $sumAdditionalExpensesNDS = 0;
            $sumAdditionalExpensesBezNDS = 0;
            $sumAdditionalExpensesNAL = 0;

            foreach ($application['additional_expenses'] as $expenses){
                $textAdditionalExpenses .= $expenses['type_expenses'] ." (" .$expenses['type_payment']
                                        .' - '   .$expenses['sum'] .') ';
                switch ($expenses['type_payment']){
                    case 'С НДС':
                        $sumAdditionalExpensesNDS += (float)$expenses['sum'];
                        break;
                    case 'Б/НДС':
                        $sumAdditionalExpensesBezNDS += (float)$expenses['sum'];
                        break;
                    case 'НАЛ':
                        $sumAdditionalExpensesNAL += (float)$expenses['sum'];
                        break;
                }
            }

            $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'];
            $transportationCostCarrierNDS = '0';

            if($application['taxation_type_Carrier'] == 'С НДС') {
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.2;

                $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;

                if(strtotime($application['date']) > strtotime('2026-01-01')){
                    $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.22;

                    $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 122 * 22;
                }
            }



            $transportationCostClientWithoutNDS = $application['transportation_cost_Client'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type_Client'] == 'С НДС') {
                $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.2;

                $transportationCostClientNDS = $application['transportation_cost_Client'] / 6;

                if(strtotime($application['date']) > strtotime('2026-01-01')){
                    $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.22;

                $transportationCostClientNDS = $application['transportation_cost_Client'] / 122 * 22;
                }

            }




            $data[] = [
                $application['manager'],
                $application['application_number'],
                $application['application_number_Client'],
                $textCustomerClient,
                date('d.m.Y', strtotime($application['date'])),
                $application['date-loading'],
                $application['date-unloading'],
                $application['application_date_actual_unloading'],
                '',
                '',
                $application['client'],
                $application['account_number_Client'],
                $application['upd_number_Client'],
                $application['transportation_cost_Client'],
                $transportationCostClientWithoutNDS,
                $transportationCostClientNDS,
                $application['actual_payment_Client'],
                $application['carrier'],
                $application['account_number_Carrier'],
                $application['upd_number_Carrier'],
                $application['transportation_cost_Carrier'],
                $transportationCostCarrierWithoutNDS,
                $transportationCostCarrierNDS,
                $application['actual_payment_Carrier'],
                $application['application_walrus'],
                $application['application_net_profit'],
                $application['manager_share'],
                $application['manager_share'],
                $application['share_for_sales'],
                $application['taxation'],
                $sumAdditionalExpensesNDS,
                $sumAdditionalExpensesBezNDS,
                $sumAdditionalExpensesNAL,
                $textAdditionalExpenses,
                ''
            ];
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'journal.xlsx');
    }

    public function createExcelTableDebitCredit(array $applications, string $nameFile = 'journal.xlsx')
    {
        $listApplications = $applications;
        // var_dump($applications);

        $data = [];

        $data[] = [
            'Логист',
            '№ заявки, перевозчик',
            '№ заявки, клиент',
            'Заказчик (Юр. лицо), клиент',
            'Дата заявки',
            'Дата погрузки',
            'Дата разгрузки',
            'Актуальная дата разгрузки',
            'ТТН',
            'ТТН отправлено',
            'Название клиента',
            'Номер счета и дата',
            'Номер УПД и дата',
            'Общая сумма',
            'Сумма без НДС',
            'НДС',
            'Фактическая сумма оплаты',
            'Название перевозчика',
            'Заказчик (Юр. лицо), перевозчик',
            'Номер счета и дата ',
            'Номер УПД и дата ',
            'Общая сумма ',
            'Сумма без НДС ',
            'НДС ',
            'Фактическая сумма оплаты',
            'Доход',
            'Чистая прибыль',
            'З.П. Логист',
            'З.П. РОП',
            'З.П. Отдел продаж',
            'Налог на прибыль',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            'Доп. затраты информация',
            'Статус по перевозчику'
        ];

        

        foreach ($listApplications as $application) {

            $idUser = $application['id_user'];
            
            $applicationModel = new Application(['id' => $application['id']]);

            // if($filter['accountFilterNumberClient'] == 1 AND $application['account_number_Client'] != '')
            //     continue;

            // if($filter['accountFilterNumberClient'] == 2 AND $application['account_number_Client'] == '')
            //     continue;

            if(!$application['application_number_Client'])
                $application['application_number_Client'] = '—';

            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] =  date('d.m.Y', strtotime($application['application_date_actual_unloading']));

            $application['date-loading'] = '';
            $application['date-unloading'] = '';

            foreach ($application['transportation_list'] as $item) {
                if ($item['direction']) {
                    $application['date-loading'] .= $item['date'] . ' ';
                }
                else{
                    $application['date-unloading'] .= $item['date'] . ' ';
                }
            }

            $application['client'] = $application['client_data']['name'] . '(' .$application['client_data']['inn'] .')';
            if($application['client_data']['format_work'])
                $application['client'] .= " (" .$application['client_data']['format_work'] . ')';

            $application['carrier'] = $application['carrier_data']['name'] . ' (' . $application['carrier_data']['inn'] .')';


            $textCustomerClient = 'ООО  Логистика';
            $textCustomerCarrier = 'ООО  Логистика';


            $textAdditionalExpenses = '';

            $sumAdditionalExpensesNDS = 0;
            $sumAdditionalExpensesBezNDS = 0;
            $sumAdditionalExpensesNAL = 0;

            foreach ($application['additional_expenses'] as $expenses){
                $textAdditionalExpenses .= $expenses['type_expenses'] ." (" .$expenses['type_payment']
                                        .' - '   .$expenses['sum'] .') ';
                switch ($expenses['type_payment']){
                    case 'С НДС':
                        $sumAdditionalExpensesNDS += (float)$expenses['sum'];
                        break;
                    case 'Б/НДС':
                        $sumAdditionalExpensesBezNDS += (float)$expenses['sum'];
                        break;
                    case 'НАЛ':
                        $sumAdditionalExpensesNAL += (float)$expenses['sum'];
                        break;
                }
            }

            $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'];
            $transportationCostCarrierNDS = '0';

            if($application['taxation_type_Carrier'] == 'С НДС') {
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.2;

                $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;

                if(strtotime($application['date']) > strtotime('2026-01-01')){
                    $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.22;

                    $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 122 * 22;
                }
            }

            if($application['taxation_type_Carrier'] == 'НАЛ') {
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 0.75;

                // $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;
            }



            $transportationCostClientWithoutNDS = $application['transportation_cost_Client'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type_Client'] == 'С НДС') {
                $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.2;

                $transportationCostClientNDS = $application['transportation_cost_Client'] / 6;

                if(strtotime($application['date']) > strtotime('2026-01-01')){
                    $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.22;

                    $transportationCostClientNDS = $application['transportation_cost_Client'] / 122 * 22;
                }

            }
            $walrus = $applicationModel->getWalrus();
            $managerShare = $applicationModel->getManagerShare($walrus, 1);
            $ropShare = $applicationModel->getROPShare($walrus);
            $salesShare = $applicationModel->getSalesShare($walrus);
            $netProfit = $applicationModel->getNetProfit($walrus, 1);

            $data[] = [
                $application['manager'].' REPORT',
                $application['application_number'],
                $application['application_number_Client'],
                $textCustomerClient,
                date('d.m.Y', strtotime($application['date'])),
                $application['date-loading'],
                $application['date-unloading'],
                $application['application_date_actual_unloading'],
                '',
                '',
                $application['client'],
                $application['account_number_Client'],
                $application['upd_number_Client'],
                $application['transportation_cost_Client'],
                $transportationCostClientWithoutNDS,
                $transportationCostClientNDS,
                $application['actual_payment_Client'],
                $application['carrier'],
                $textCustomerCarrier,
                $application['account_number_Carrier'],
                $application['upd_number_Carrier'],
                $application['transportation_cost_Carrier'],
                $transportationCostCarrierWithoutNDS,
                $transportationCostCarrierNDS,
                $application['actual_payment_Carrier'],
                $application['application_walrus'],
                $netProfit,
                $managerShare,
                $ropShare,
                $salesShare,
                $application['taxation'],
                $sumAdditionalExpensesNDS,
                $sumAdditionalExpensesBezNDS,
                $sumAdditionalExpensesNAL,
                $textAdditionalExpenses,
                ''
            ];
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .$nameFile);
    }

    public function createExcelTablePLDDS(array $conditions = [], array $filter = [], string $nameFile = 'journal.xlsx')
    {
        $listApplications = $this->getListApplication($conditions);


        $data = [];

        $data[] = [
            'Логист',
            '№ заявки, перевозчик',
            '№ заявки, клиент',
            'Заказчик (Юр. лицо), клиент',
            'Дата заявки',
            'Дата погрузки',
            'Дата разгрузки',
            'Актуальная дата разгрузки',
            'ТТН',
            'ТТН отправлено',
            'Название клиента',
            'Номер счета и дата',
            'Номер УПД и дата',
            'Общая сумма',
            'Сумма без НДС',
            'НДС',
            'Фактическая сумма оплаты',
            'Название перевозчика',
            'Заказчик (Юр. лицо), перевозчик',
            'Номер счета и дата ',
            'Номер УПД и дата ',
            'Общая сумма ',
            'Сумма без НДС ',
            'НДС ',
            'Фактическая сумма оплаты',
            'Доход',
            'Чистая прибыль',
            'З.П. Логист',
            'З.П. РОП',
            'З.П. Отдел продаж',
            'Налог на прибыль',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            'Доп. затраты информация',
            'Статус по перевозчику'
        ];

        

        foreach ($listApplications as $application) {

            $idUser = $application['id_user'];

            $applicationModel = new Application(['id' => $application['id']]);

            // if($filter['accountFilterNumberClient'] == 1 AND $application['account_number_Client'] != '')
            //     continue;

            // if($filter['accountFilterNumberClient'] == 2 AND $application['account_number_Client'] == '')
            //     continue;

            if(!$application['application_number_Client'])
                $application['application_number_Client'] = '—';

            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] =  date('d.m.Y', strtotime($application['application_date_actual_unloading']));

            $application['date-loading'] = '';
            $application['date-unloading'] = '';

            foreach ($application['transportation_list'] as $item) {
                if ($item['direction']) {
                    $application['date-loading'] .= $item['date'] . ' ';
                }
                else{
                    $application['date-unloading'] .= $item['date'] . ' ';
                }
            }

            $application['client'] = $application['client_data']['name'] . '(' .$application['client_data']['inn'] .')';
            if($application['client_data']['format_work'])
                $application['client'] .= " (" .$application['client_data']['format_work'] . ')';

            $application['carrier'] = $application['carrier_data']['name'] . ' (' . $application['carrier_data']['inn'] .')';


            $textCustomerClient = 'ООО  Логистика';
            $textCustomerCarrier = 'ООО Логистика';


            $textAdditionalExpenses = '';

            $sumAdditionalExpensesNDS = 0;
            $sumAdditionalExpensesBezNDS = 0;
            $sumAdditionalExpensesNAL = 0;

            foreach ($application['additional_expenses'] as $expenses){
                $textAdditionalExpenses .= $expenses['type_expenses'] ." (" .$expenses['type_payment']
                                        .' - '   .$expenses['sum'] .') ';
                switch ($expenses['type_payment']){
                    case 'С НДС':
                        $sumAdditionalExpensesNDS += (float)$expenses['sum'];
                        break;
                    case 'Б/НДС':
                        $sumAdditionalExpensesBezNDS += (float)$expenses['sum'];
                        break;
                    case 'НАЛ':
                        $sumAdditionalExpensesNAL += (float)$expenses['sum'];
                        break;
                }
            }

            $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'];
            $transportationCostCarrierNDS = '0';

            if($application['taxation_type_Carrier'] == 'С НДС') {
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.2;

                $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;

                if(strtotime($application['date']) > strtotime('2026-01-01')){
                    $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.22;

                    $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 122 * 22;
                }
            }

            if($application['taxation_type_Carrier'] == 'НАЛ') {
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 0.75;

                // $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;
            }



            $transportationCostClientWithoutNDS = $application['transportation_cost_Client'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type_Client'] == 'С НДС') {
                $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.2;

                $transportationCostClientNDS = $application['transportation_cost_Client'] / 6;

                if(strtotime($application['date']) > strtotime('2026-01-01')){
                    $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.22;

                    $transportationCostClientNDS = $application['transportation_cost_Client'] / 122 * 22;
                }

            }
            $walrus = $applicationModel->getWalrus();
            $managerShare = $applicationModel->getManagerShare($walrus, 1);
            $ropShare = $applicationModel->getROPShare($walrus);
            $salesShare = $applicationModel->getSalesShare($walrus);
            $netProfit = $applicationModel->getNetProfit($walrus, 1);

            $data[] = [
                $application['manager'].' REPORT',
                $application['application_number'],
                $application['application_number_Client'],
                $textCustomerClient,
                date('d.m.Y', strtotime($application['date'])),
                $application['date-loading'],
                $application['date-unloading'],
                $application['application_date_actual_unloading'],
                '',
                '',
                $application['client'],
                $application['account_number_Client'],
                $application['upd_number_Client'],
                $application['transportation_cost_Client'],
                $transportationCostClientWithoutNDS,
                $transportationCostClientNDS,
                $application['actual_payment_Client'],
                $application['carrier'],
                $textCustomerCarrier,
                $application['account_number_Carrier'],
                $application['upd_number_Carrier'],
                $application['transportation_cost_Carrier'],
                $transportationCostCarrierWithoutNDS,
                $transportationCostCarrierNDS,
                $application['actual_payment_Carrier'],
                $application['application_walrus'],
                $netProfit,
                $managerShare,
                $ropShare,
                $salesShare,
                $application['taxation'],
                $sumAdditionalExpensesNDS,
                $sumAdditionalExpensesBezNDS,
                $sumAdditionalExpensesNAL,
                $textAdditionalExpenses,
                ''
            ];
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .$nameFile);
    }


    public function createExcelTableManagerCarrierStat(array $dataUsers = [], string $nameFile = 'journal.xlsx')
    {
        $data = [];

        $data[] = [
            'Логист',
            'Сумма с НДС',
            'Сумма без НДС',
            'Процент НДС',
            'Процент без НДС'
        ];

        // dd($dataUsers);

        $sumNDS = 0;
        $sumWithoutNDS = 0;

        foreach ($dataUsers as $name => $user) {
            // dd($user);
            if($user['sum_with_nds'] == 0 AND $user['sum_without_nds'] == 0)
                continue;
            $data[] = [
                $name,
                $user['sum_with_nds'],
                $user['sum_without_nds'],
                $user['percent_with_nds'] .'%',
                $user['percent_without_nds'] .'%',
            ];
            $sumNDS += $user['sum_with_nds'];
            $sumWithoutNDS += $user['sum_without_nds'];
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, NULL, 'A1');

        $sum = $sumNDS + $sumWithoutNDS;

        $dataStat = [
            [
                'Вид налогообложения',
                'Общая сумма',
            ],
            [
                'С НДС', $sumNDS
            ],
            [
                'Без НДС', $sumWithoutNDS
            ],
            [
                'Итого', $sum
            ]
        ];


        $sheet->fromArray($dataStat, NULL, 'I1');

        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // --- Границы таблиц ---
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        // для основной таблицы
        $lastRowData = count($data);
        $lastColData = 'E'; // поменяй под свою таблицу
        $sheet->getStyle("A1:{$lastColData}{$lastRowData}")
            ->applyFromArray($styleArray);

        // для блока статистики
        $lastRowStat = count($dataStat);
        $sheet->getStyle("I1:J{$lastRowStat}")
            ->applyFromArray($styleArray);


        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .$nameFile);
    }


    public function createExcelTableApplicationPayment(array $conditions = [])
    {
        $listApplications = $this->getListApplication($conditions);


        $data = [];

        $data[] = [
            'Логист',
            '№ заявки',
            'Заказчик (Юр. лицо), клиент',
            'Актуальная дата разгрузки',
            'Название клиента',
            'Номер счета и дата',
            'Номер УПД и дата',
            'Общая сумма',
            'Фактическая сумма оплаты',
            'Остаток оплаты',
            'Условия оплаты',
            'Дата оплаты (клиент)',
            'Название перевозчика',
            'Заказчик (Юр. лицо), перевозчик',
            'Общая сумма ',
            'Фактическая сумма оплаты',
            'Остаток оплаты',
            'Условия оплаты',
            'Дата оплаты (перевозчик)',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            'Доп. затраты информация',
        ];

        foreach ($listApplications as $application) {

            if(!$application['application_number_Client'])
                $application['application_number_Client'] = '—';

            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] =  date('d.m.Y', strtotime($application['application_date_actual_unloading']));

            $application['date-loading'] = '';
            $application['date-unloading'] = '';

            foreach ($application['transportation_list'] as $item) {
                if ($item['direction']) {
                    $application['date-loading'] .= $item['date'] . ' ';
                }
                else{
                    $application['date-unloading'] .= $item['date'] . ' ';
                }
            }

            $application['client'] = $application['client_data']['name'] . '(' .$application['client_data']['inn'] .')';
            if($application['client_data']['format_work'])
                $application['client'] .= " (" .$application['client_data']['format_work'] . ')';

            $application['carrier'] = $application['carrier_data']['name'] . ' (' . $application['carrier_data']['inn'] .')';


            $textCustomerClient = 'ООО  Логистика';
            $textCustomerCarrier = 'ООО  Логистика';

            switch ($application['customer_id_Client']){
                case 2:
                    $textCustomerClient =  '(ИП Иванов Иван Иванович)';
                    break;
                
            }

            $textAdditionalExpenses = '';

            $sumAdditionalExpensesNDS = 0;
            $sumAdditionalExpensesBezNDS = 0;
            $sumAdditionalExpensesNAL = 0;

            foreach ($application['additional_expenses'] as $expenses){
                $textAdditionalExpenses .= $expenses['type_expenses'] ." (" .$expenses['type_payment']
                                        .' - '   .$expenses['sum'] .') ';
                switch ($expenses['type_payment']){
                    case 'С НДС':
                        $sumAdditionalExpensesNDS += (float)$expenses['sum'];
                        break;
                    case 'Б/НДС':
                        $sumAdditionalExpensesBezNDS += (float)$expenses['sum'];
                        break;
                    case 'НАЛ':
                        $sumAdditionalExpensesNAL += (float)$expenses['sum'];
                        break;
                }
            }

            $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'];
            $transportationCostCarrierNDS = '0';

            if($application['taxation_type_Carrier'] == 'С НДС') {
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.2;

                $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;
            }



            $transportationCostClientWithoutNDS = $application['transportation_cost_Client'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type_Client'] == 'С НДС') {
                $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.2;

                $transportationCostClientNDS = $application['transportation_cost_Client'] / 6;

            }

            $datePaymentClient = '';
            $datePaymentCarrier = '';


            if($application['date_payment_Carrier']) {
                $datePaymentCarrier = date('d.m.Y',strtotime($application['date_payment_Carrier']));
            }

            if($application['date_payment_Client']) {
                $datePaymentClient = date('d.m.Y',strtotime($application['date_payment_Client']));
            }

            $data[] = [
                $application['manager'],
                $application['application_number'],
                $textCustomerClient,
                // date('d.m.Y', strtotime($application['date'])),
                // $application['date-loading'],
                // $application['date-unloading'],
                $application['application_date_actual_unloading'],
                $application['client'],
                $application['account_number_Client'],
                $application['upd_number_Client'],
                $application['transportation_cost_Client'],
                // $transportationCostClientWithoutNDS,
                // $transportationCostClientNDS,
                $application['actual_payment_Client'],
                $application['balance_payment_Client'],
                $application['terms_payment_Client'],
                $datePaymentClient,
                $application['carrier'],
                $textCustomerCarrier,
                // $application['account_number_Carrier'],
                // $application['upd_number_Carrier'],
                $application['transportation_cost_Carrier'],
                // $transportationCostCarrierWithoutNDS,
                // $transportationCostCarrierNDS,
                $application['actual_payment_Carrier'],
                $application['balance_payment_Carrier'],
                $application['terms_payment_Carrier'],
                $datePaymentCarrier,

                // $application['application_walrus'],
                // $application['application_net_profit'],
                // $application['manager_share'],
                // $application['taxation'],
                $sumAdditionalExpensesNDS,
                $sumAdditionalExpensesBezNDS,
                $sumAdditionalExpensesNAL,
                $textAdditionalExpenses,
                ''
            ];
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'journal.xlsx');
    }

    public function createExcelTablePRR(array $conditions = [], array $filter = []){
        $listApplications = $this->getListPRRApplication($conditions);

        $data = [];

        $data[] = [
            'Логист',
            '№ заявки, перевозчик',
            '№ заявки, клиент',
            'Заказчик (Юр. лицо), клиент',
            'Дата заявки',
            'Дата погрузки',
            'Дата разгрузки',
            'Актуальная дата разгрузки',
            'ТТН',
            'ТТН отправлено',
            'Название клиента',
            'Номер счета и дата',
            'Номер УПД и дата',
            'Общая сумма',
            'Сумма без НДС',
            'НДС',
            'Фактическая сумма оплаты',
            'Название ПРР компании',
            'Номер счета и дата ',
            'Номер УПД и дата ',
            'Общая сумма ',
            'Сумма без НДС ',
            'НДС ',
            'Фактическая сумма оплаты',
            'Доход',
            'Чистая прибыль',
            'Маржа з.п.',
            'Налог на прибыль',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            'Доп. затраты информация',
        ];

        foreach ($listApplications as $application) {

            if($filter['accountFilterNumberClient'] == 1 AND $application['account_number_Client'] != '')
                continue;

            if($filter['accountFilterNumberClient'] == 2 AND $application['account_number_Client'] == '')
                continue;


            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] =  date('d.m.Y', strtotime($application['application_date_actual_unloading']));

            // $application['date-loading'] = '';
            // $application['date-unloading'] = '';

            // foreach ($application['transportation_list'] as $item) {
            //     if ($item['direction']) {
            //         $application['date-loading'] .= $item['date'] . ' ';
            //     }
            //     else{
            //         $application['date-unloading'] .= $item['date'] . ' ';
            //     }
            // }

            $application['client'] = $application['client_data']['name'] . '(' .$application['client_data']['inn'] .')';
            if($application['client_data']['format_work'])
                $application['client'] .= " (" .$application['client_data']['format_work'] . ')';

            $application['carrier'] = $application['prr_data']['name'] . ' (' . $application['prr_data']['inn'] .')';


            $textCustomerClient = 'ООО  Логистика';
            $textCustomerCarrier = 'ООО  Логистика';

            switch ($application['customer_id_Client']){
                case 2:
                    $textCustomerClient =  '(ИП Иванов Иван Иванович)';
                    break;
            }


            $textAdditionalExpenses = '';

            $sumAdditionalExpensesNDS = 0;
            $sumAdditionalExpensesBezNDS = 0;
            $sumAdditionalExpensesNAL = 0;

            foreach ($application['additional_expenses'] as $expenses){
                $textAdditionalExpenses .= $expenses['type_expenses'] ." (" .$expenses['type_payment']
                                        .' - '   .$expenses['sum'] .') ';
                switch ($expenses['type_payment']){
                    case 'С НДС':
                        $sumAdditionalExpensesNDS += (float)$expenses['sum'];
                        break;
                    case 'Б/НДС':
                        $sumAdditionalExpensesBezNDS += (float)$expenses['sum'];
                        break;
                    case 'НАЛ':
                        $sumAdditionalExpensesNAL += (float)$expenses['sum'];
                        break;
                }
            }


            $transportationCostCarrierWithoutNDS = $application['cost_prr'];
            $transportationCostCarrierNDS = '0';

            if($application['taxation_type_prr'] == 'С НДС') {
                $transportationCostCarrierWithoutNDS = $application['cost_prr'] / 1.2;

                $transportationCostCarrierNDS = $application['cost_prr'] / 6;
            }



            $transportationCostClientWithoutNDS = $application['cost_Client'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type_Client'] == 'С НДС') {
                $transportationCostClientWithoutNDS = $application['cost_Client'] / 1.2;

                $transportationCostClientNDS = $application['cost_Client'] / 6;

            }




            $data[] = [
                $application['manager'],
                $application['application_number'],
                $application['application_number'],
                $textCustomerClient,
//                $textCustomerCarrier,
                date('d.m.Y', strtotime($application['date'])),
                // $application['date-loading'],
                // $application['date-unloading'],
                '',
                '',
                $application['application_date_actual_unloading'],
                '',
                '',
                $application['client'],
                $application['account_number_Client'],
                $application['upd_number_Client'],
                $application['cost_Client'],
                $transportationCostClientWithoutNDS,
                $transportationCostClientNDS,
                $application['actual_payment_Client'],
                $application['carrier'],
                $application['account_number_prr'],
                $application['upd_number_prr'],
                $application['cost_prr'],
                $transportationCostCarrierWithoutNDS,
                $transportationCostCarrierNDS,
                $application['actual_payment_prr'],
                $application['application_walrus'],
                $application['application_net_profit'],
                $application['manager_share'],
                $application['taxation'],
                $sumAdditionalExpensesNDS,
                $sumAdditionalExpensesBezNDS,
                $sumAdditionalExpensesNAL,
                $textAdditionalExpenses,
                ''
            ];
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'journal.xlsx');
    }


    public function createExcelTableTS(array $conditions = [], array $filter = [])
    {
        $listApplications = $this->getListTSApplication($conditions);


        $data = [];

        $data[] = [
            'Логист',
            '№ заявки, перевозчик',
            '№ заявки, клиент',
            'Заказчик (Юр. лицо), клиент',
//            'Заказчик (Юр. лицо), перевозчик',
            'Дата заявки',
            'Дата погрузки',
            'Дата разгрузки',
            'Актуальная дата разгрузки',
            'ТТН',
            'ТТН отправлено',
            'Название клиента',
            'Номер счета и дата',
            'Номер УПД и дата',
            'Общая сумма',
            'Сумма без НДС',
            'НДС',
            'Фактическая сумма оплаты',
            'Доп. затраты С НДС',
            'Доп. затраты Б/НДС',
            'Доп. затраты НАЛ',
            'Доп. затраты информация',
        ];

        foreach ($listApplications as $application) {

            if($filter['accountFilterNumberClient'] == 1 AND $application['account_number'] != '')
                continue;

            if($filter['accountFilterNumberClient'] == 2 AND $application['account_number'] == '')
                continue;

            if(!$application['application_number'])
                $application['application_number'] = '—';

            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] =  date('d.m.Y', strtotime($application['application_date_actual_unloading']));

            $application['date-loading'] = '';
            $application['date-unloading'] = '';

            foreach ($application['transportation_list'] as $item) {
                if ($item['direction']) {
                    $application['date-loading'] .= $item['date'] . ' ';
                }
                else{
                    $application['date-unloading'] .= $item['date'] . ' ';
                }
            }

            // $application['client'] = $application['client_data']['name'] . '(' .$application['client_data']['inn'] .')';
            // if($application['client_data']['format_work'])
            //     $application['client'] .= " (" .$application['client_data']['format_work'] . ')';

            // $application['carrier'] = $application['carrier_data']['name'] . ' (' . $application['carrier_data']['inn'] .')';


            $textCustomerClient = 'ООО  Логистика';

            switch ($application['id_customer']){
                case 2:
                    $textCustomerClient =  '(ИП Иванов Иван Иванович)';
                    break;
                
            }


            $textAdditionalExpenses = '';

            $sumAdditionalExpensesNDS = 0;
            $sumAdditionalExpensesBezNDS = 0;
            $sumAdditionalExpensesNAL = 0;

            foreach ($application['additional_expenses'] as $expenses){
                $textAdditionalExpenses .= $expenses['type_expenses'] ." (" .$expenses['type_payment']
                                        .' - '   .$expenses['sum'] .') ';
                switch ($expenses['type_payment']){
                    case 'С НДС':
                        $sumAdditionalExpensesNDS += (float)$expenses['sum'];
                        break;
                    case 'Б/НДС':
                        $sumAdditionalExpensesBezNDS += (float)$expenses['sum'];
                        break;
                    case 'НАЛ':
                        $sumAdditionalExpensesNAL += (float)$expenses['sum'];
                        break;
                }
            }



            $transportationCostClientWithoutNDS = $application['transportation_cost'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type'] == 'С НДС') {
                $transportationCostClientWithoutNDS = $application['transportation_cost'] / 1.2;

                $transportationCostClientNDS = $application['transportation_cost'] / 6;

            }




            $data[] = [
                $application['manager'],
                $application['application_number'],
                $application['application_number'],
                $textCustomerClient,
//                $textCustomerCarrier,
                date('d.m.Y', strtotime($application['date'])),
                $application['date-loading'],
                $application['date-unloading'],
                $application['application_date_actual_unloading'],
                '',
                '',
                $application['forwarder_data']['name'],
                $application['account_number'],
                $application['upd_number'],
                $application['transportation_cost'],
                $transportationCostClientWithoutNDS,
                $transportationCostClientNDS,
                $application['actual_payment'],
                $sumAdditionalExpensesNDS,
                $sumAdditionalExpensesBezNDS,
                $sumAdditionalExpensesNAL,
                $textAdditionalExpenses,
            ];
        }

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $writer->save($this->pathDocTemplate .'journal.xlsx');


    }

    public function getListPRRApplication(array $conditions = [],$isManager = true, string $selectionOperation = 'AND'): array
    {
        $PRRApplicationsDatabase = $this->database->superSelect('prr_application', $conditions,['id' => 'DESC'],-1,['id'], 0, $selectionOperation);
        if(! $PRRApplicationsDatabase)
            $PRRApplicationsDatabase = [];

        $listPRRApplications = [];

        $listUserDB = $this->database->select(
            'users',
            ['role' => 1],[],-1,['id', 'name', 'surname']
        );

        foreach ($listUserDB as $user) {
            $listUser[$user['id']] = $user['name'] . ' ' . $user['surname'];
        }

        foreach ($PRRApplicationsDatabase as $PRRApplicationDb) {
            $application = new PRRApplicationJournal($this->database,$isManager,['id' => $PRRApplicationDb['id']]);

            $temp = $application->get();


            if(isset($conditions['for_sales'])){
                $temp['manager_share'] = $temp['share_for_sales'];
            }

            if($temp['application_section_journal'] == 2){
                $listIdDocument = [1,2,10,13,14];

                $files = $this->database->superSelect(
                    'prr_files',
                    ['application_id' => $temp['id'], 'document_id' => $listIdDocument]
                ) ?? [];

                $listNameDocument = [
                    0 => 'Оплата от клиента (<b>Б</b>)',
                    1 => 'Скриншот в поле "Подписанная заявка от исполнителя" (<b>Л</b>)',
                    10 => 'Подписанная заявка от клиента (<b>Л</b>)',
                ];


                foreach ($files as $file) {
                    foreach ($listIdDocument as $condition) {
                        if($file['document_id'] == $condition){
                            unset($listNameDocument[$condition]);
                        }
                    }
                }

                if($temp['actual_payment_Client'] === $temp['cost_Client'])
                    unset($listNameDocument[0]);

                $temp['unfulfilledConditions'] = $listNameDocument;

            }



            $temp['manager'] = $listUser[$temp['id_user']];
            $temp['type-application'] = 'prr';


            $listPRRApplications[] = $temp;
        }



        return $listPRRApplications;

    }

    public function getListTSApplication(array $conditions = [],$isManager = true ): array
    {
        $TSApplicationsDatabase = $this->database->superSelect('ts_application', $conditions,['id' => 'DESC'],-1,['id']);
        if(! $TSApplicationsDatabase)
            $TSApplicationsDatabase = [];

        $listTSApplications = [];

        $listUserDB = $this->database->select(
            'users',
            ['role' => 1],[],-1,['id', 'name', 'surname']
        );

        foreach ($listUserDB as $user) {
            $listUser[$user['id']] = $user['name'] . ' ' . $user['surname'];
        }

        foreach ($TSApplicationsDatabase as $TSApplicationDb) {
            $application = new TSApplicationJournal($this->database, $isManager,['id' => $TSApplicationDb['id']]);

            $temp = $application->get();


            
            $temp['manager'] = $listUser[$temp['id_user']];


            $listTSApplications[] = $temp;
        }



        return $listTSApplications;

    }
}