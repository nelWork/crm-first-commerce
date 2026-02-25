<?php

namespace App\User\Contoller\Common;

use App\Model\AdditionalExpenses\AdditionalExpenses;
use App\Model\AdditionalExpenses\PaymentHistoryAdditionalExpenses;
use App\Model\Application\Application;
use App\Model\Application\ApplicationJournal;
use App\Model\Carrier\Carrier;
use App\Model\File\File;
use App\Model\File\FIleBankStatement;
use App\Model\PRR\PRRApplication;
use App\Model\PRR\PRRApplicationJournal;
use App\Model\TSApplication\TSApplication;
use App\Model\User\User;
use App\User\Contoller\Controller;
use App\User\Model\Application\ApplicationChangeStatus;
use App\User\Model\Carrier\CarrierList;
use App\User\Model\Client\ClientList;
use App\User\Model\Journal\Journal;
use App\User\Model\Journal\ParseTXT;
use App\User\Model\PRR\PRRApplicationChangeStatus;
use App\User\Model\User\UserList;
use mysql_xdevapi\Exception;
use App\User\Model\RegisterPayment\RegisterPaymentApplicationList;


class JournalController extends Controller
{

    public function registerApplicationConsideration(){

        $condition = ['application_section_journal' => 0];
    // dd($condition);

        $model = new Journal($this->database);

        $listApplication = $model->getListApplication($condition);
    // dd($listApplication);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Реестр заявок на проверке',
            'listApplication' => $listApplication,
            'activeHeaderItem' => 17,
        ]);

        $this->view('Journal/register-application-consideration');
    }

    public function ajaxApplicationConsiderationAccepted(){
        $idApplication = $this->request->input('id');

        $application = new Application(['id' => $idApplication]);

        $application->edit([
            'application_section_journal' => 1,
            'application_status' => 'В работе'
        ]);

        if ($application->save())
            print json_encode(['status' => true]);
        else
            print json_encode(['status' => false]);
    }

    public function registerApplicationPayment() {
        $start = microtime(true);

        $condition = [
            // 'application_section_journal' => [1,2,3,6],
            'actual_payment_Client !=' => 'transportation_cost_Client',
            'actual_payment_Carrier !=' => 'transportation_cost_Carrier',
        ];

        // dd($condition);

        $model = new Journal($this->database);

        $modelListUser = new UserList($this->database);
        $modelClientList = new ClientList($this->database);
        $modelCarrierList = new CarrierList($this->database);


        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        $listApplication = $model->getListApplication($condition, false, 'OR');
        // dd($listApplication);

        foreach ($listApplication as $key => $application) {
            if($application['application_section_journal'] == 4 OR $application['application_section_journal'] == 5) {
                unset($listApplication[$key]);
            }
        }

        $conditionFilter = $condition;


        

        // dd($listApplication);

        $accessChangePayment = false;

        if (in_array($this->auth->user()->id(), [22, 25, 1, 26])) {
            $accessChangePayment = true;
        }

        $eventsPrr = $this->database->select('prr_manager_journal_event');
        $eventsTs = $this->database->select('ts_manager_journal_event');

        $modelListApplication = new RegisterPaymentApplicationList($this->database);

        // dd($modelListApplication);

        $listInRegisterPayment = $modelListApplication->listApplication();
        // dd($listInRegisterPayment);
        $condition = [
            'actual_payment_Prr !=' => 'cost_Prr',
            'actual_payment_Client !=' => 'cost_Client'
        ];

        $prrApplicationList = $model->getListPRRApplication($condition, false, 'OR');

        foreach ($prrApplicationList as $key => $application) {
            if($application['application_section_journal'] == 4 OR $application['application_section_journal'] == 5) {
                unset($prrApplicationList[$key]);
            }
        }


        // dd($prrApplicationList);
        
        // $listApplication = array_merge($prrApplicationList,$listApplication);
        
        // dump($listApplication);

        // dd($listInRegisterPayment);

        // Получаем список всех id заявок из $listInRegisterPayment, где type = 1
        $idsInRegister = array_column(
            array_filter($listInRegisterPayment, fn($item) => $item['type'] == 1),
            'id'
        );

        // Теперь проходим по $listApplication и добавляем флаг in_register
        foreach ($listApplication as $key => $application) {
            if (in_array($application['id'], $idsInRegister)) {
                $listApplication[$key]['in_register'] = 1;
            } else {
                $listApplication[$key]['in_register'] = 0;
            }
        }

        // dd($listApplication);


        $idsInRegisterPrr = array_column(
            array_filter($listInRegisterPayment, fn($item) => $item['type'] == 2),
            'id'
        );

        // Теперь проходим по $listApplication и добавляем флаг in_register
        foreach ($prrApplicationList as $key => $application) {
            if (in_array($application['id'], $idsInRegisterPrr)) {
                $prrApplicationList[$key]['in_register'] = 1;
            } else {
                $prrApplicationList[$key]['in_register'] = 0;
            }
        }
        // dd($listApplication);

        $listApplication = array_merge($this->transformPrrToNormalApplication($prrApplicationList),$listApplication);

        foreach($listApplication as $key => $application){

            if($application['type-application'] == 'prr'){
                $lastRegisterPayment = $this->database->select(
                    'prr_register_payment_history',
                    [
                        'id_application' => $application['id'],
                        'date' => date('Y-m-d'),
                    ],
                    ['id' => 'DESC']
                );
            }
            else{
                $lastRegisterPayment = $this->database->select(
                    'register_payment_history',
                    [
                        'id_application' => $application['id'],
                        'date' => date('Y-m-d'),
                    ],
                    ['id' => 'DESC']
                );
            }

            $listApplication[$key]['last_register_payment_comment'] = '';

            if($lastRegisterPayment){
                $listApplication[$key]['last_register_payment_comment'] = $lastRegisterPayment[0]['comment'];
            }
        }
        $uniqueData = $model->getListUniqueData($listApplication);
        // dd($listApplication);
        $uniqueData['last_register_payment_comment'] = array_unique(array_column($listApplication, 'last_register_payment_comment'));
        
        foreach($listApplication as $key => $application){
            if($application['type-application'] == 'prr')
                continue;

            $listCommentDB = $this->database->select(
                'register_payment_application_comment',
                ['id_application' => $application['id']],
                ['id' => 'DESC']
            );

            $listComment = [];

            if($listCommentDB){
                foreach ($listCommentDB as $comment) {
                    $tempComment = $comment;

                    $tempUser = $this->database->first('users', ['id' => $comment['id_user']]);

                    $tempComment['user'] = $tempUser['name'] . ' ' . $tempUser['surname'];
                    $tempComment['datetime'] = date('d.m.Y', strtotime($comment['datetime_comment']));

                    $listComment[] = $tempComment;
                }
            }

            $listApplication[$key]['list_comment'] = $listComment;
        }

        // dd($listApplication);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Реестр оплаты заявок',
            'listApplication' => $listApplication,
            'listPRRApplication' => [],
            'listTSApplication' => [],
            'uniqueData' => $uniqueData,
            'linkForExcelTable' => $this->request->server['REDIRECT_QUERY_STRING'] ?? '',
            'condition' => $conditionFilter,
            'listManager' => $modelListUser->listManagerJournal(),
            'listClients' => $modelClientList->simpleListClients(),
            'listCarriers' => $modelCarrierList->simpleListCarriers(),
            'activeHeaderItem' => 15,
            'accessChangePayment' => $accessChangePayment,
            'eventsPrr' => $eventsPrr,
            'eventsTs' => $eventsTs
        ]);

        $this->view('Journal/register-application-payment');
    }
    public function ajaxSetSessionFilter()
    {
        $type = $this->request->input('type');
        echo $type;
        switch ($type) {
            case 'customer':
                $this->session->set('journalCustomer', $this->request->input('idCustomer'));
                break;
            case 'section':
                $this->session->set('journalSection', $this->request->input('section'));
                break;
            case 'type-app':
                $this->session->set('journalTypeApp', $this->request->input('type-app'));
                break;
        }
    }
    public function ajaxGetListApplicationAdditionalExpenses()
    {
        $idApplication = $this->request->input('id');
        $typeApp = $this->request->input('typeApp');

        $nameTable = 'additional_expenses';

        if($typeApp == 'prr')
            $nameTable = 'additional_expenses_prr';

        $additionalExpensesList = $this->database->select($nameTable, ['id_application' => $idApplication]);

        print json_encode($additionalExpensesList);
    }
    public function pluckUniqueSorted(array $array, string $keyPath, bool $skipNulls = true): array {
        $result = [];
        $keyParts = explode('.', $keyPath);

        foreach ($array as $item) {
            $value = $item;
            foreach ($keyParts as $part) {
                if (is_array($value) && array_key_exists($part, $value)) {
                    $value = $value[$part];
                } else {
                    $value = null;
                    break;
                }
            }

            if (!$skipNulls || $value !== null) {
                $result[] = $value;
            }
        }

        // Удаляем дубликаты
        $unique = array_unique($result);

        // Сортировка от меньшего к большему
        sort($unique); // по возрастанию, с учётом типа (строки, числа и т.д.)

        return array_values($unique);
    }



    public function controlPayment()
    {

        $condition = [
            'dateField' => [
                'name' => 'date',
                'start' => date('Y-m-01'),
                'end' => date('Y-m-28')
            ]
        ];

        $period = date('01.m.Y') .' - ' . date('28.m.Y');

        if($this->request->input('period') !== null AND $this->request->input('period') !== '') {
            $period = $this->request->input('period');

            $date = explode(' - ', $period);

            if(isset($date['1']))
                $condition['dateField']  = [
                    'name' => 'date',
                    'start' => date('Y-m-d', strtotime($date[0])),
                    'end' => date('Y-m-d 23:59:59', strtotime($date[1])),
                ];
            else
                $condition['dateField']  = [
                    'name' => 'date',
                    'start' => date('Y-m-d', strtotime($date[0])),
                    'end' => date('Y-m-d 23:59:59', strtotime($date[0])),
                ];


        }

        $listHistoryPayment = $this->database->superSelect('history_payment',
            $condition,
            ['id' => 'DESC']
        );
        $listHistoryPaymentClient = [];

        $listHistoryPaymentCarrier = [];

        $sumClient = 0;
        $sumCarrier = 0;

        foreach ($listHistoryPayment as $item){

            $item['application_data'] = $this->database->first(
                'applications',
                ['id' => $item['id_application']],
                [
                    'application_number',
                    'client_id_Client',
                    'carrier_id_Carrier',
                    'account_number_Client',
                    'customer_id_Client',
                    'customer_id_Carrier',
                    'taxation_type_Client',
                    'taxation_type_Carrier',
                ]
            );

            if(! $item['application_data']) continue;

            $item['application_data']['TYPE_APPLICATION'] = 1;

            $item['application_data']['customer_Client'] = 'ООО Логистика';
            $item['application_data']['customer_Carrier'] = 'ООО Логистика';

            switch ($item['application_data']['customer_id_Client']) {
                case 4:
                    $item['application_data']['customer_Client'] = '';
                    break;
            }


            switch ($item['application_data']['customer_id_Carrier']) {
                case 4:
                    $item['application_data']['customer_Carrier'] = '';
                    break;
            }


            if($item['side'] == 0){
                $item['application_data']['client'] = $this->database->first(
                    'clients',['id' => $item['application_data']['client_id_Client']],['name'])['name'];
                $listHistoryPaymentClient[] = $item;
                $sumClient += $item['quantity'];
            }
            else{
                $item['application_data']['carrier'] = $this->database->first(
                    'carriers',['id' => $item['application_data']['carrier_id_Carrier']],['name'])['name'];
                $listHistoryPaymentCarrier[] = $item;
                $sumCarrier += $item['quantity'];
            }
        }

        $listHistoryPayment = $this->database->superSelect('prr_history_payment',
            $condition,
            ['id' => 'DESC']
        );
        //        $listHistoryPaymentClient = [];
        //
        //        $listHistoryPaymentCarrier = [];

        foreach ($listHistoryPayment as $item){

            $item['application_data'] = $this->database->first(
                'prr_application',
                ['id' => $item['id_application']]
                , [
                    'application_number',
                    'client_id_Client',
                    'prr_id_Prr',
                    'account_number_Client',
                    'customer_id_Client',
                    'customer_id_Prr',
                    'taxation_type_Prr',
                    'taxation_type_Client'
                ]
            );

            if(! $item['application_data']) continue;

            $item['application_data']['TYPE_APPLICATION'] = 2;

            $item['application_data']['customer_Client'] = 'ООО  Логистика';
            $item['application_data']['customer_Carrier'] = 'ООО  Логистика';

            switch ($item['application_data']['customer_id_Client']) {
                case 4:
                    $item['application_data']['customer_Client'] = 'ООО ';
                    break;
            }


            switch ($item['application_data']['customer_id_Prr']) {
                case 4:
                    $item['application_data']['customer_Carrier'] = 'ООО ';
                    break;
            }


            if($item['side'] == 0){
                $item['application_data']['client'] = $this->database->first(
                    'clients',['id' => $item['application_data']['client_id_Client']],['name'])['name'];
                $listHistoryPaymentClient[] = $item;
                $sumClient += $item['quantity'];
            }
            else{
                $item['application_data']['carrier'] = $this->database->first(
                    'prr_company',['id' => $item['application_data']['prr_id_Prr']],['name'])['name'];
                $item['application_data']['customer_id_Carrier'] = $item['application_data']['customer_id_Prr'];
                $item['application_data']['taxation_type_Carrier'] = $item['application_data']['taxation_type_Prr'];

                $listHistoryPaymentCarrier[] = $item;
                $sumCarrier += $item['quantity'];

            }
        }

        $condition = [
            'dateField' => [
                'name' => 'datetime',
                'start' => date('Y-m-01'),
                'end' => date('Y-m-28')
            ]
        ];

        $period = date('01.m.Y') .' - ' . date('28.m.Y');

        if($this->request->input('period') !== null AND $this->request->input('period') !== '') {
            $period = $this->request->input('period');

            $date = explode(' - ', $period);

            if(isset($date['1']))
                $condition['dateField']  = [
                    'name' => 'datetime',
                    'start' => date('Y-m-d', strtotime($date[0])),
                    'end' => date('Y-m-d 23:59:59', strtotime($date[1])),
                ];
            else
                $condition['dateField']  = [
                    'name' => 'datetime',
                    'start' => date('Y-m-d', strtotime($date[0])),
                    'end' => date('Y-m-d 23:59:59', strtotime($date[0])),
                ];
        }

        $listHistoryPayment = $this->database->superSelect('payment_history_additional_expenses',
            $condition,
            ['id' => 'DESC']
        );

        foreach ($listHistoryPayment as $item){

            $item['id_application'] = $item['application_id'];
            $item['date'] = $item['datetime'];
            $item['quantity'] = $item['sum'];

            $item['application_data'] = $this->database->first(
                'applications',
                ['id' => $item['id_application']],
                [
                    'application_number',
                    'customer_id_Carrier',
                ]
            );

            if(! $item['application_data']) continue;

            $item['application_data']['TYPE_APPLICATION'] = 3;

            $item['application_data']['customer_Carrier'] = 'ООО  Логистика';

            switch ($item['application_data']['customer_id_Carrier']) {
                case 4:
                    $item['application_data']['customer_Carrier'] = 'ООО ';
                    break;
            }

            $comment = $this->database->first('additional_expenses',
                ['id' => $item['additional_expense_id']],
                ['comment']
            );

            $item['application_data']['carrier'] = ($item['additional_expenses_name'] ?? '').(($comment['comment'] ?? '') !== '' ? ' ('.$comment['comment'].')' : '');
            $item['application_data']['taxation_type_Carrier'] = $item['taxation_type'];
            $listHistoryPaymentCarrier[] = $item;
            $sumCarrier += $item['quantity'];

        }


        $this->extract([
            'controller' => $this,
            'period' => $period,
            'listHistoryPaymentClient' => $listHistoryPaymentClient,
            'listHistoryPaymentCarrier' => $listHistoryPaymentCarrier,
            'sumClient' => $sumClient,
            'sumCarrier' => $sumCarrier,
            'titlePage' => 'Контроль выписки',
            'activeHeaderItem' => 12,
        ]);

        $this->view('Journal/control-payment');
    }
    public function index(){
        $start = microtime(true);

        $condition = [];

        $model = new Journal($this->database);

        $modelListUser = new UserList($this->database);
        $modelClientList = new ClientList($this->database);
        $modelCarrierList = new CarrierList($this->database);

        $date_start = $this->request->input('date-start') ?? date('Y-m-d' , strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

        if($this->auth->user()->id() == 22){
            $date_start = $this->request->input('date-start') ?? '2025-01-01';
        }

        if(date('d', strtotime($date_end)) == '01' AND !$this->request->input('date-end'))
            $date_start = $this->request->input('date-start') ?? date('Y-m-01', strtotime("-1 months"));


        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));


        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        if(!empty($clientFilter))
            $condition['client_id_Client'] = $clientFilter;

        if(!empty($carrierFilter))
            $condition['carrier_id_Carrier'] = $carrierFilter;

        if(!empty($logistFilter))
            $condition['id_user'] = $logistFilter;

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];

        $listApplication = $model->getListApplication($condition, false);

        $conditionFilter = $condition;

        $conditionFilter['dateField']['end'] = $date_end;

        $conditionFilter['client_id_Client'] = $clientFilter;
        $conditionFilter['carrier_id_Carrier'] = $carrierFilter;
        $conditionFilter['id_user'] = $logistFilter;


        $uniqueData = $model->getListUniqueData($listApplication);

        $accessChangePayment = false;

        if (in_array($this->auth->user()->id(), [22, 25, 1, 26])) {
            $accessChangePayment = true;
        }

        $eventsPrr = $this->database->select('prr_manager_journal_event');
        $eventsTs = $this->database->select('ts_manager_journal_event');

        

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Бухг. журнал',
            'listApplication' => $listApplication,
            'listPRRApplication' => $model->getListPRRApplication($condition),
            'listTSApplication' => $model->getListTSApplication($condition),
            'uniqueData' => $uniqueData,
            'linkForExcelTable' => $this->request->server['REDIRECT_QUERY_STRING'] ?? '',
            'condition' => $conditionFilter,
            'listManager' => $modelListUser->listManagerJournal(),
            'listClients' => $modelClientList->simpleListClients(),
            'listCarriers' => $modelCarrierList->simpleListCarriers(),
            'activeHeaderItem' => 5,
            'accessChangePayment' => $accessChangePayment,
            'eventsPrr' => $eventsPrr,
            'eventsTs' => $eventsTs,
            'customers' => $this->database->select('customers')
        ]);

        $this->view('Journal/index');
    }

    public function ajaxAdditionalExpenseChangeIsPaid($id = 0, $newStatus = 1, $typeApp = '',$date = '')
    {
        if($id > 0){

        }
        else{
            $id = (int) $this->request->input('id');
            $newStatus = $this->request->input('status') == 'pay' ? 1 : 0 ;
            $typeApp =  $this->request->input('typeApp');
        }

//        var_dump($id,$newStatus,$typeApp);

//        var_dump($typeApp);
        try {

            $additionalExpense = new AdditionalExpenses(['id' => $id]);
            if($typeApp == 'prr')
                $additionalExpense = new AdditionalExpenses(['id' => $id], 'additional_expenses_prr');

            $additionalExpense->edit([
                'isPaid' => $newStatus
            ]);

//            var_dump($additionalExpense->save());


            if($additionalExpense->save() && $newStatus > 0){
                $additionalExpenseData = $additionalExpense->get();

                PaymentHistoryAdditionalExpenses::addPaymentHistoryItem(
                    $additionalExpenseData['sum'],
                    $additionalExpenseData['id_application'],
                    $additionalExpenseData['id'],
                    $additionalExpenseData['type_payment'],
                    $additionalExpenseData['type_expenses'],
                    $this->auth->user()->id(),
                    $typeApp,
                    $date
                );
            }

            echo json_encode([
                'status' => true
            ]);
        }
        catch (\Exception $e){
            echo json_encode([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function transformPrrToNormalApplication(array $applicationsPrr = []) : array {
        foreach($applicationsPrr as $key => $application){
            $applicationsPrr[$key]['application_number_Client'] = '';
            $applicationsPrr[$key]['transportation_list'] = $application['prr_place_list'];
            $applicationsPrr[$key]['transportation_cost_Client'] = $application['cost_Client'];
            $applicationsPrr[$key]['transportation_cost_Carrier'] = $application['cost_prr'];
            $applicationsPrr[$key]['carrier_data'] = $application['prr_data'];
            $applicationsPrr[$key]['taxation_type_Carrier'] = $application['taxation_type_prr'];
            $applicationsPrr[$key]['actual_payment_Carrier'] = $application['actual_payment_prr'];
            $applicationsPrr[$key]['full_payment_date_Carrier'] = $application['full_payment_date_prr'];
            $applicationsPrr[$key]['history_payment_Carrier'] = $application['history_payment_prr'];
            $applicationsPrr[$key]['additional_expenses_sum_Carrier'] = $application['additional_expenses_sum'];
            $applicationsPrr[$key]['carrier_id_Carrier'] = $application['prr_id_prr'];
            $applicationsPrr[$key]['events_application'] = [];
            $applicationsPrr[$key]['strange_application'] = false;
            

            if(isset($application['balance_payment_prr'])){
                $applicationsPrr[$key]['balance_payment_Carrier'] = $applicationsPrr[$key]['balance_payment_prr'];
                $applicationsPrr[$key]['terms_payment_Carrier'] = $applicationsPrr[$key]['terms_payment_prr'];
                $applicationsPrr[$key]['date_payment_Carrier'] = $applicationsPrr[$key]['date_payment_prr'];
                $applicationsPrr[$key]['date_receipt_all_documents_Carrier'] = $applicationsPrr[$key]['application_date_actual_unloading'];
                $applicationsPrr[$key]['customer_id_Carrier'] = $applicationsPrr[$key]['customer_id_prr'];
            }

            
            // dd($application);
        }
        
        return $applicationsPrr;
    }

    public function indexList(){

        // dd($_POST);        

        $condition = [];

        $model = new Journal($this->database);

        $modelListUser = new UserList($this->database);
        $modelClientList = new ClientList($this->database);
        $modelCarrierList = new CarrierList($this->database);

        $date_start = $this->request->input('date-start') ?? date('Y-m-d' , strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

        if(date('d', strtotime($date_end)) == '01' AND !$this->request->input('date-end'))
            $date_start = $this->request->input('date-start') ?? date('Y-m-01', strtotime("-1 months"));


        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));


        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        if(!empty($clientFilter))
            $condition['client_id_Client'] = $clientFilter;

        if(!empty($carrierFilter))
            $condition['carrier_id_Carrier'] = $carrierFilter;

        if(!empty($logistFilter))
            $condition['id_user'] = $logistFilter;

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];

        $conditionFilter = $condition;

        if($this->request->input('app-id')){
            $listId = trim($this->request->input('app-id'),',');
            $listId = explode(',', $listId);
            $condition = ['id' => $listId];
        }

        if($this->request->input('client-id')){
            $condition = ['client_id_Client' => $this->request->input('client-id')];
            $condition['cancelled']  = 0;
        }

        if($this->request->input('carrier-id')){
            $condition = ['carrier_id_Carrier' => $this->request->input('carrier-id')];
            $condition['cancelled']  = 0;
        }
        $noProfit = false;
        if($this->request->input('no-profit') == 1){
            $condition = ['application_walrus <=' => 4000, 'cancelled' => 0];
            if($this->request->input('date-start') AND $this->request->input('date-end')){
                $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];
            }
            $noProfit = true;
        }

        if($this->request->input('not-in-salary') == 'Вадим'){
            $condition = [
                'id_user' => 19,
                'cancelled' => 0,
                'application_section_journal' => [1,2]
            ];
        }

        $listApplication = $model->getListApplication($condition, false);

        $conditionFilter['dateField']['end'] = $date_end;

        $conditionFilter['client_id_Client'] = $clientFilter;
        $conditionFilter['carrier_id_Carrier'] = $carrierFilter;
        $conditionFilter['id_user'] = $logistFilter;


        $uniqueData = $model->getListUniqueData($listApplication);

        $accessChangePayment = false;

        if (in_array($this->auth->user()->id(), [22, 25, 26, 1])) {
            $accessChangePayment = true;
        }

        $nameFile = '';

        if($this->request->input('name')){
            $nameFile = $this->request->input('name');
        }

        $dataPost = $this->request->post['app-id'] ?? '';

        $listTSApplication = [];

        if($this->request->input('type') == 'ts'){
            unset($condition['dateField']);
            $listTSApplication = $model->getListTsApplication($condition);
        }


        $listPRRApplication = [];

        if($this->request->input('type') == 'prr'){
            unset($condition['dateField']);
            $listPRRApplication = $model->getListPRRApplication($condition);
        }
        $dataPrrPost = $this->request->post['prr-app-id'] ?? '';
        if($this->request->input('prr-app-id')){
            $listId = trim($this->request->input('prr-app-id'),',');
            $listId = explode(',', $listId);
            $condition = ['id' => $listId];

            $listPRRApplication = $model->getListPRRApplication($condition);
            $listPrrToNormalApplication = $this->transformPrrToNormalApplication($listPRRApplication);

            $listPRRApplication = [];

            $listApplication = array_merge($listApplication,$listPrrToNormalApplication);

        }

        $eventsTs = $this->database->select('ts_manager_journal_event');
        $eventsPrr = $this->database->select('prr_manager_journal_event');
    //    dd($listApplication);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Бухг. журнал',
            'listApplication' => $listApplication,
            'uniqueData' => $uniqueData,
            'listTSApplication' => $listTSApplication,
            'listPRRApplication' => $listPRRApplication,
            'linkForExcelTable' => $this->request->server['REDIRECT_QUERY_STRING'] ?? '',
            'condition' => $conditionFilter,
            'dataPost' => $dataPost,
            'dataPrrPost' => $dataPrrPost,
            'listManager' => $modelListUser->listManagerJournal(),
            'listClients' => $modelClientList->simpleListClients(),
            'listCarriers' => $modelCarrierList->simpleListCarriers(),
            'activeHeaderItem' => 5,
            'accessChangePayment' => $accessChangePayment,
            'nameFile' => $nameFile,
            'eventsTs' => $eventsTs,
            'eventsPrr' => $eventsPrr,
            'noProfit' => $noProfit
        ]);

        $this->view('Journal/index-list');
    }

    public function ajaxChangeApplicationAdditionalExpense(){
        $id = $this->request->input('id');
        $info = $this->request->input('info');
        $date = $this->request->input('date');

        $additionalExpense = $this->database->first(
            'additional_expenses',
            [
                'id_application' => $id,
                'sum' => $info
            ]
        );

        $idAdditionalExpense = $additionalExpense['id'];

        // $this->database->update('additional_expenses', ['is_paid' => 1], ['id' => $idAdditionalExpense]);

        $this->ajaxAdditionalExpenseChangeIsPaid($idAdditionalExpense,1,'', date('Y-m-d H:i:s', strtotime($date)));
    }

    public function ajaxChangeApplicationInfo()
    {
        $id = $this->request->input('id');
        $name = $this->request->input('nameInfo');
        $info = $this->request->input('info');
        $typeApp = $this->request->input('typeApp');
        $event = false;
        $cost = false;

        $date = $this->request->input('date');
    //    var_dump([$id, $name, $info, $typeApp, $date]);

        // Устанавливаем таблицу для основных данных, стоимости, истории и событий
        $table = 'applications';
        $fieldCost = 'transportation_cost';
        $historyTable = 'history_payment'; // Таблица для записи истории по умолчанию
        $eventTable = 'manager_journal_event'; // Таблица для записи событий по умолчанию

        if ($typeApp === 'prr') {
            $table = 'prr_application'; // Таблица для prr
            $fieldCost = 'cost'; // Используем "cost" вместо "transportation_cost"
            $historyTable = 'prr_history_payment'; // Таблица для записи истории для prr
            $eventTable = 'prr_manager_journal_event'; // Таблица для записи событий для prr
        }

        if ($typeApp === 'ts') {
            $table = 'ts_application'; // Таблица для ts
            $fieldCost = 'transportation_cost';
            $historyTable = 'ts_history_payment'; // Таблица для записи истории для ts
            $eventTable = 'ts_manager_journal_event'; // Таблица для записи событий для ts
        }

        if ($name === 'actual_payment_Client' || $name === 'actual_payment_Carrier' || $name === 'actual_payment_Prr') {
            $side = 0;
            $nameInTable = 'Client';
            $nameEvent = 'client';

            // Логика выбора между Carrier и Prr
            if ($name === 'actual_payment_Carrier' || $name === 'actual_payment_Prr') {
                if ($typeApp === 'prr' && $name === 'actual_payment_Prr') {
                    $nameInTable = 'Prr'; // Заменяем Carrier на Prr
                    $nameEvent = 'prr';
                } else {
                    $nameInTable = 'Carrier';
                    $nameEvent = 'carrier';
                }
                $side = 1;
            }

            $quantity = (float)str_replace([' ', '.'], '', $info);

            $nameCost = $fieldCost . '_Client'; // Поле для Client
            if ($side) {
                $nameCost = $fieldCost . '_' . $nameInTable; // Например, cost_Prr или transportation_cost_Carrier
            }

            if ($typeApp === 'ts') {
                $name = 'actual_payment';
                $nameCost = 'transportation_cost';
            }

            // Получаем данные из основной таблицы для расчета
            $actual_payment = $this->database->select($table, ['id' => $id], [], 1, [$name, $nameCost])[0];

            $info = $quantity + $actual_payment[$name];

            if ($info == (float)$actual_payment[$nameCost]) {
                $this->database->insert(
                    $eventTable, // Используем динамически выбранную таблицу для событий
                    [
                        'application_id' => $id,
                        'event' => $nameEvent . '_payment_status'
                    ]
                );
                $event = true;
            }
            $cost = $info;
        }

        $dateHistory = date('Y-m-d H:i:s');

        if($date)
            $dateHistory = date('Y-m-d', strtotime($date));

        // Обновляем данные в основной таблице (динамически выбранной)

        if ($this->database->update($table, [$name => $info], ['id' => $id])) {
            
            if ($name === 'account_number_Client') {
                $this->database->update($table, ['upd_number_Client' => $info], ['id' => $id]);
            }

            if ($name === 'actual_payment_Client' || $name === 'actual_payment_Carrier' || $name === 'actual_payment_Prr') {
                // Вставляем данные в историю платежей (динамически выбранную таблицу)


                $this->database->insert(
                    $historyTable, // Таблица для истории определяется динамически
                    [
                        'id_user' => $this->auth->user()->id(),
                        'id_user_application' => $this->database->first(
                            $table,
                            ['id' => $id],
                            ['id_user']
                        )['id_user'],
                        'id_application' => $id,
                        'side' => $side,
                        'quantity' => $quantity,
                        'date' => $dateHistory
                    ]
                );
                // Обновляем дату полной оплаты в основной таблице
                $this->database->update($table, ['full_payment_date_' . $nameInTable => $dateHistory], ['id' => $id]);
            }

            print json_encode(['status' => true, 'event' => $event, 'cost' => $cost]);
        } else {
            print json_encode(['status' => false, 'cost' => $cost]);
        }

        $modelChangeApplicationStatus = new ApplicationChangeStatus($this->database);

        $modelChangeApplicationStatus->checkPossibleChangeStatus($id);

        if($typeApp == 'prr'){
            $modelChangeApplicationStatus = new PRRApplicationChangeStatus($this->database);

            $modelChangeApplicationStatus->checkPossibleChangeStatus($id);
        }
    }

    public function ajaxChangeApplicationAccountNumber()
    {
        $idApplication = $this->request->input('id');
        $applicationType = $this->request->input('typeApp');
        $value = $this->request->input('value');

        switch ($applicationType) {
            case 'prr':
                $application = new PRRApplication(['id' => $idApplication]);
                break;
            case 'ts':
                $application = new TSApplication(['id' => $idApplication]);
                break;
            default:
                $application = new Application(['id' => $idApplication]);
                break;
        }
        $application->edit(['account_number' => $value]);

        if ($application->save())
            print json_encode(['status' => true, 'number' => $value]);
        else
            print json_encode(['status' => false]);
    }

    public function ajaxChangePaymentDate()
    {
        $id = $this->request->input('id');
        $date = $this->request->input('value');
        $side = $this->request->input('side');

        $type = $this->request->input('type') ?? '';

        $nameTable = 'applications';

        if($type === 'prr'){
            $nameTable = 'prr_application';
        }

        if($this->database->update(
            $nameTable,
            ['full_payment_date_' . $side => $date],
            ['id' => $id]
        ))
            print json_encode(['status' => true, 'date' => date('d.m.Y', strtotime($date))]);
        else
            print json_encode(['status' => false]);
    }

    public function ajaxChangePaymentDateHistory()
    {
        $id = $this->request->input('id');
        $date = $this->request->input('value');

        $type = $this->request->input('type') ?? '';

        $nameTable = 'history_payment';
        if($type === 'prr'){
            $nameTable = 'prr_history_payment';
        }

        if($this->database->update(
            $nameTable,
            ['date' => $date],
            ['id' => $id]
        ))
            print json_encode(['status' => true, 'date' => date('d.m.Y', strtotime($date))]);
        else
            print json_encode(['status' => false]);
    }

    public function ajaxSearchApplicationNumberManager(){
        $application_search = $this->request->input('search');
        $model = new Journal($this->database);
        // dump($application_search);

        $user = $this->auth->user();

        $condition = [
            'application_number' => $application_search,
        ];

        if(count($user->getSubordinatesList()) > 0){
            $condition['id_user'] = array_merge([$user->id()], $user->getSubordinatesList());
        }
        else{
            $condition['id_user'] = $user->id();
        }

        $applications = $model->getListApplication($condition) ?? [];

        // dump($applications);

        $settings = $this->auth->user()->getUserSetting();

        $dopSetting = [];
        $dopSetting['carrier-email'] = $settings['journal_email'];
        $dopSetting['gruz'] = $settings['journal_gruz'];
        $dopSetting['carrier-ati'] = $settings['journal_ati'];
        $dopSetting['driver-car'] = $settings['journal_driver_car'];
        $dopSetting['driver-number'] = $settings['journal_driver_number'];

        $this->extract([
            'controller' => $this,
            'listApplication' => $applications,
            'fullCRMAccess' => $this->auth->user()->fullCRM(),
            'isROPJournal' => false,
            'dopSetting' => $dopSetting,
            'isClosedJournal' => false
        ]);


        if(count($applications) > 0){
            $this->view('Journal/manager-application-template');
            
        }

        $listApplications = [];
    }

    public function ajaxSearchApplicationNumber()
    {
        $application_search = $this->request->input('search');
        $field = $this->request->input('field');
        $model = new Journal($this->database);

        if($field == 'application_number_client' OR $field == 'account_number_Client')
            $applications = $model->getListApplicationSearch([$field => '%' .$application_search .'%']) ?? [];
        else
            $applications = $model->getListApplication([$field => $application_search]) ?? [];



        $listApplications = [];


        foreach($applications as $application){

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

            $application['date'] = date('d.m.Y', strtotime($application['date']));

            $application['transportation_cost_Client_normal'] = $application['transportation_cost_Client'];


            $application['actual_payment_Client_normal'] = $application['actual_payment_Client'];
            $application['transportation_cost_Carrier_normal'] = $application['transportation_cost_Carrier'];
            $application['actual_payment_Carrier_normal'] = $application['actual_payment_Carrier'];
            $application['application_walrus_normal'] =  $application['application_walrus'];
            $application['application_net_profit_normal'] = $application['application_net_profit'];
            $application['manager_share_normal'] = $application['manager_share'];

            $application['transportation_cost_Client'] = number_format($application['transportation_cost_Client'] ,0,'.',' ');

            $application['actual_payment_Client'] = number_format($application['actual_payment_Client'],0,'.',' ');
            $application['transportation_cost_Carrier'] = number_format($application['transportation_cost_Carrier'],0,'.',' ');
            $application['actual_payment_Carrier'] = number_format($application['actual_payment_Carrier'],0,'.',' ');
            $application['application_walrus'] =  number_format($application['application_walrus'],0,'.',' ');
            $application['application_net_profit'] = number_format($application['application_net_profit'],0,'.',' ');
            $application['manager_share'] = number_format($application['manager_share'],0,'.',' ');
            if($application['application_date_actual_unloading'])
                $application['application_date_actual_unloading'] = date('d.m.Y', strtotime($application['application_date_actual_unloading']));
            else
                $application['application_date_actual_unloading'] = '';


            $application['transportation_cost_Client_without_VAT'] = $application['transportation_cost_Client_normal'];
            $application['transportation_VAT_Client'] = 0;

            if($application['taxation_type_Client'] == 'С НДС'){
                $application['transportation_cost_Client_without_VAT'] = number_format(
                    (int)($application['transportation_cost_Client_normal'] / 1.2), 0, '', ' ');
                $application['transportation_VAT_Client'] = number_format((int)($application['transportation_cost_Client_normal'] / 6), 0, '', ' ');
            }


            $application['transportation_cost_Carrier_without_VAT'] = $application['transportation_cost_Carrier_normal'];
            $application['transportation_VAT_Carrier'] = 0;

            if($application['taxation_type_Carrier'] == 'С НДС'){
                $application['transportation_cost_Carrier_without_VAT'] = number_format((int)($application['transportation_cost_Carrier_normal'] / 1.2), 0, '', ' ');
                $application['transportation_VAT_Carrier'] = number_format((int)($application['transportation_cost_Carrier_normal'] / 6), 0, '', ' ');
            }


            $application['application_section_journal_name'] = '(Актуальные)';

            switch ($application['application_section_journal']) {
                case 2:
                    $application['application_section_journal_name'] = '(Завершенные)';
                    break;
                case 3:
                    $application['application_section_journal_name'] = '(Закрытые под расчет)';
                    break;
                case 4:
                    $application['application_section_journal_name'] = '(Срывы)';
                    break;
                case 5:
                    $application['application_section_journal_name'] = '(Отмененные)';
                    break;
            }

            $application['id_customer_name'] = '(ООО Логистика)';

            switch ($application['id_customer']){
                case 2:
                    $application['id_customer_name'] = '(ИП Иванов Иван Иванович)';
                    break;
            }

            $application['event_class_Client'] = '';
            $application['event_class_Carrier'] = '';
            foreach ($application['events_application'] as $event) {
                if($event['event'] == 'client_payment_status') {
                    $application['client_payment_event'] = ['id' => $event['id'], 'status' => $event['status']];
                    $application['event_class_Client'] = 'event-payment-' . $application['client_payment_event']['status'];
                }
                if($event['event'] == 'carrier_payment_status') {
                    $application['carrier_payment_event'] = ['id' => $event['id'], 'status' => $event['status']];
                    $application['event_class_Carrier'] = 'event-payment-' . $application['carrier_payment_event']['status'];
                }
            }

            foreach ($application['history_payment_Carrier'] as $key => $item) {
                $application['history_payment_Carrier'][$key]['quantity'] = number_format($item['quantity'], 0, '', ' ');
                $application['history_payment_Carrier'][$key]['date'] = date('d.m.Y', strtotime($item['date']));
            }

            foreach ($application['history_payment_Client'] as $key => $item) {
                $application['history_payment_Client'][$key]['quantity'] = number_format($item['quantity'], 0, '', ' ');
                $application['history_payment_Client'][$key]['date'] = date('d.m.Y', strtotime($item['date']));
            }

            if($application['full_payment_date_Client']){
                $application['full_payment_date_Client'] = date('d.m.Y', strtotime($application['full_payment_date_Client']));
            }
            
            if($application['full_payment_date_Carrier']){
                $application['full_payment_date_Carrier'] = date('d.m.Y', strtotime($application['full_payment_date_Carrier']));
            }

            $listApplications[] = $application;

        }

//        print_r($listApplications);

        if($listApplications)
            print json_encode(['status' => true, 'applications' => $listApplications]);
        else
            print json_encode(['status' => false]);

    }

    public function ajaxGetExcel()
    {
        $condition = [];

        $model = new Journal($this->database);

        $date_start = $this->request->input('date-start') ?? date('Y-m-d', strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

        if($this->auth->user()->id() == 22){
            $date_start = $this->request->input('date-start') ?? '2025-01-01';
        }

        if(date('d', strtotime($date_end)) == '01' AND !$this->request->input('date-end'))
            $date_start = $this->request->input('date-start') ?? date('Y-m-01', strtotime("-1 months"));

        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];

        $condition['application_section_journal'] = $this->request->input('section') ?? 1;

        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        if(!empty($clientFilter))
            $condition['client_id_Client'] = $clientFilter;

        if(!empty($carrierFilter))
            $condition['carrier_id_Carrier'] = $carrierFilter;

        if(!empty($logistFilter))
            $condition['id_user'] = $logistFilter;

        $model->createExcelTable($condition,['accountFilterNumberClient' => $this->request->input('accountFilterNumberClient')]);

        print json_encode(['result' => true,'link_file' => 'doc/journal.xlsx']);

    }

    public function ajaxGetExcelList()
    {
        $condition = [];

        $model = new Journal($this->database);

        $date_start = $this->request->input('date-start') ?? date('Y-m-d', strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

        if(date('d', strtotime($date_end)) == '01' AND !$this->request->input('date-end'))
            $date_start = $this->request->input('date-start') ?? date('Y-m-01', strtotime("-1 months"));

        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];

        $condition['application_section_journal'] = $this->request->input('section') ?? 1;

        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        if(!empty($clientFilter))
            $condition['client_id_Client'] = $clientFilter;

        if(!empty($carrierFilter))
            $condition['carrier_id_Carrier'] = $carrierFilter;

        if(!empty($logistFilter))
            $condition['id_user'] = $logistFilter;

        if($this->request->input('app-id')){
            $listId = trim($this->request->input('app-id'),',');
            $listId = explode(',', $listId);
            $condition = ['id' => $listId];

        }


        if($this->request->input('client-id')){
            $condition = ['client_id_Client' => $this->request->input('client-id')];
            $condition['cancelled']  = 0;
        }

        if($this->request->input('carrier-id')){
            $condition = ['carrier_id_Carrier' => $this->request->input('carrier-id')];
            $condition['cancelled']  = 0;
        }

        $noProfit = false;

        if($this->request->input('no-profit') == 1){
            $condition = ['application_walrus <=' => 4000, 'cancelled' => 0];
            if($this->request->input('date-start') AND $this->request->input('date-end')){
                $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];
            }
            $noProfit = true;
            
        }
        // $condition = [
        //     // 'application_section_journal' => [1,2,3,6],
        //     'actual_payment_Client !=' => 'transportation_cost_Client',
        //     'actual_payment_Carrier !=' => 'transportation_cost_Carrier',
        // ];

        if($this->request->input('not-in-salary') == 'Вадим'){
            $condition = [
                'id_user' => 19,
                'cancelled' => 0,
                'application_section_journal' => [1,2]
            ];
        }

        if($this->request->input('type') == 'ts'){
            $condition = [];
            $model->createExcelTableTS($condition,['accountFilterNumberClient' => $this->request->input('accountFilterNumberClient')]);
        }
        elseif($this->request->input('type') == 'prr'){
            $model->createExcelTablePRR($condition,['accountFilterNumberClient' => $this->request->input('accountFilterNumberClient')]);
        }
        else{
            if($this->request->input('type-excel') == 'application-payment')
                $model->createExcelTableApplicationPayment($condition,['accountFilterNumberClient' => $this->request->input('accountFilterNumberClient')]);
            else 
                $model->createExcelTable($condition,['accountFilterNumberClient' => $this->request->input('accountFilterNumberClient')]);
        }


        if($this->request->input('type') == 'report'){  
            $model->createExcelTablePLDDS($condition,['accountFilterNumberClient' => $this->request->input('accountFilterNumberClient')]);
        }

        if($this->request->input('noProfit')){
            $model->createExcelTableNoProfit($condition);
        }

        if($this->request->input('prr-app-id')){
            // echo 'test';
            $listId = trim($this->request->input('app-id'),',');
            $listId = explode(',', $listId);
            $condition = ['id' => $listId];
            $listApplication = $model->getListApplication($condition);


            $listId = trim($this->request->input('prr-app-id'),',');
            $listId = explode(',', $listId);
            $condition = ['id' => $listId];

            $listPRRApplication = $model->getListPRRApplication($condition);
            $listPrrToNormalApplication = $this->transformPrrToNormalApplication($listPRRApplication);

            // $listPRRApplication = [];

            $listApplication = array_merge($listApplication,$listPrrToNormalApplication);

            // var_dump($listApplication);

            // $listApplication = $model->getListApplication($condition, false);

            $model->createExcelTableDebitCredit($listApplication);
        }


        print json_encode(['result' => true,'link_file' => 'doc/journal.xlsx']);

    }

    public function getAdditionalExpensesForParseTXT(int $idApplication, $sum){
        return $additionalExpense = $this->database->first(
            'additional_expenses',
            [
                'id_application' => $idApplication,
                'sum' => (float)$sum 
            ]
        );
       

    }

    public function parseTXT($link = null)
    {
        $listFilesBankStatement = $this->database->select('files_bank_statement',[],['id' => 'DESC']) ?? [];

        if($this->request->input('id')){
            $fileBankStatement = new FileBankStatement(['id' => $this->request->input('id')]);

            if($fileBankStatement)
                $link = $fileBankStatement->get()['link'];
        }

        if(!$link){
        //            $parser = new ParseTXT('310125.txt');

            $this->extract([
                'controller' => $this,
                'titlePage' => 'Журнал выписка',
                'listPayment' => ['arrayFrom' => [], 'arrayTo' => []],
                'uniqueData' => [],
                'listFilesBankStatement' => $listFilesBankStatement,
                'linkForExcelTable' => $this->request->server['REDIRECT_QUERY_STRING'] ?? '',
                'condition' => [],
                'listManager' => [],
                'listClients' => [],
                'listCarriers' => [],
                'activeHeaderItem' => 11,
                'accessChangePayment' => true
            ]);

            $this->view('Journal/index-export-v2');
            exit;
        }
        else
            $parser = new ParseTXT($link);


        $documentArray = $parser->getParsedDocuments();
        // dd($documentArray);
        //    dd($documentArray[2]);
        // dump($documentArray);

        $arrayTo = [];
        $arrayFrom = [];

        foreach ($documentArray as $document) {
            if($document['ПлательщикИНН'] == 6679177087 AND $document['ПолучательИНН'] == 6671156960)
                continue;

            if($document['Плательщик1'] === 'ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ "ЛИБЕРО ЛОГИСТИКА"' OR
                $document['Плательщик1'] === 'ООО "ЛИБЕРО ЛОГИСТИКА"' OR $document['ПлательщикИНН'] == 6671156960
            ){
                // dump($document);
                if($document['Заявка'] == null)
                    continue;

                $arrayFrom[] = $document;
            }
            else{
                if($document['Счета'] == null)
                    continue;
                $arrayTo[] = $document;
            }
        }

        foreach ($arrayFrom as $key => $data) {
            $applicationNumber = $data['Заявка'];
            $applicationId = $this->database->first(
                'applications',
                ['application_number' => $applicationNumber],
            );
            
            if(str_contains($applicationNumber,'-П'))
                $applicationId = false;

            if($applicationId){

                $application = new ApplicationJournal(
                    $this->database,
                    false,
                    ['id' => $applicationId['id']]
                );

                $application = $application->get();
                

                if(!$application['id'])
                    continue;


                if(isset($data['ДопРасход'])){
                    $applicationData = $application;
                    $additionalExpense = $this->getAdditionalExpensesForParseTXT($application['id'],$data['Сумма']);
                    // dd($additionalExpense);
                    $application['transportation_cost_Carrier'] = $additionalExpense['sum'];
                    $application['taxation_type_Carrier'] = $additionalExpense['type_payment'];
                    

                    $application['TYPE'] = 1;
                    $application['MARKER'] = false;

                    $application['events_application'] = [];

                    $application['id_expense'] = $additionalExpense['id'];

                    $application['fullPaymentCarrier'] = $additionalExpense['is_paid'];

                    $application['actual_payment_Carrier'] = 0;

                    if($additionalExpense['is_paid'])
                        $application['actual_payment_Carrier'] = $additionalExpense['sum'];


                    // if($additionalExpense['is_paid']){
                    //     $application['actual_payment_Carrier'] = $additionalExpense['sum'];
                    //     $application['fullPaymentCarrier'] = true;
                    // }
                    // else{
                    //     $application['actual_payment_Carrier'] = 0;
                        

                    // }

                    // dd($application);

                    // break;
                }
                else{
                    $application['TYPE'] = 1;

                    $application['fullPaymentCarrier'] = false;

                    if($application['transportation_cost_Carrier'] == $application['actual_payment_Carrier']){
                        $application['fullPaymentCarrier'] = true;
                    }

                    $application['MARKER'] = false;

                    if(! $application['fullPaymentCarrier']){

                        $existingPayment = $this->database->first('history_payment', [
                            'id_application'       => $application['id'],
                            'quantity' => $data['Сумма'],
                            'side' => 1
                        ])['id'] ?? false;

                        if($existingPayment){
                            $application['MARKER'] = true;
                            $application['MARKER-comment'] = 'Раннее такая сумма уже вносилась, 
                                необходимо проверить не является ли это повтором';
                        }
                    }

                    if($application['transportation_cost_Carrier'] < $data['Сумма']){
                        $application['MARKER'] = true;
                        $application['MARKER-comment'] = 'Сумма операции больше чем стоимость перевозки';
                    }

                    if($application['actual_payment_Carrier'] + $data['Сумма'] > $application['transportation_cost_Carrier']){
                        $application['MARKER'] = true;
                        $application['MARKER-comment'] = 'Общая сумма будет превышать стоимость перевозки';
                    }
            }
            }
            else{
                $applicationId = $this->database->first(
                    'prr_application',
                    ['application_number' => $applicationNumber],
                    ['id']
                );

            //  dump($data['Заявка'],$applicationId);
                

                if(!$applicationId){
                    continue;
                }



                $application = new PRRApplicationJournal($this->database,false,['id' => $applicationId['id']]);

                $application = $application->get();

                $application['TYPE'] = 2;

                $application['fullPaymentPrr'] = false;

                if($application['cost_prr'] == $application['actual_payment_prr']){
                    $application['fullPaymentPrr'] = true;
                }

                $application['MARKER'] = false;

                if(! $application['fullPaymentPrr']){

                    $existingPayment = $this->database->first('prr_history_payment', [
                        'id_application'       => $application['id'],
                        'quantity' => $data['Сумма'],
                        'side' => 1
                    ])['id'] ?? false;

                    if($existingPayment){
                        $application['MARKER'] = true;
                        $application['MARKER-comment'] = 'Раннее такая сумма уже вносилась, необходимо проверить не является ли это повтором';
                    }

                }

                if($application['cost_prr'] < $data['Сумма']){
                    $application['MARKER'] = true;
                    $application['MARKER-comment'] = 'Сумма операции больше чем стоимость перевозки';
                }

                if($application['actual_payment_prr'] + $data['Сумма'] > $application['cost_prr']){
                    $application['MARKER'] = true;
                    $application['MARKER-comment'] = 'Общая сумма будет превышать стоимость перевозки';
                }

                $application['application_number_Client'] = $application['application_number'];
                $application['transportation_list'] = $application['prr_place_list'];
                $application['carrier_data'] = $application['prr_data'];
                $application['transportation_cost_Carrier'] = $application['cost_prr'];
                $application['taxation_type_Carrier'] = $application['taxation_type_prr'];
                $application['actual_payment_Carrier'] = $application['actual_payment_prr'];
                $application['full_payment_date_Carrier'] = $application['full_payment_date_prr'];
                $application['history_payment_Carrier'] = $application['history_payment_prr'];
                $application['fullPaymentCarrier'] = $application['fullPaymentPrr'];
                $application['events_application'] = [];

        //                $application['carrier_id_']
            }

            $arrayFrom[$key]['applicationData'] = $application;

        }

        // dd($arrayFrom);


        foreach ($arrayTo as $key => $data) {
            $applicationAccountPayment = $data['Счета'];

            $model = new Journal($this->database);
            $applicationList = [];
            foreach ($applicationAccountPayment as $accountPayment) {
                $customer = 1;
                if($data['ПолучательИНН'] == '661221287051'){
                    $customer = 2;
                }
                if($data['ПолучательИНН'] == '661221186335'){
                    $customer = 3;
                }
                if($data['ПолучательИНН'] == '6671156960'){
                    $customer = 4;
                }
                $temp = $model->getListApplicationSearch(
                    // ['account_number_Client' => '%' .$accountPayment .'%', 'customer_id_Client' => $customer]
                    ['account_number_Client' => $accountPayment .'%', 'customer_id_Client' => $customer]
                ) ?? [];
                foreach ($temp as $tempApp){
                    $applicationList[] = $tempApp;
                }
            }

            if (!empty($applicationList)) {
                // Присваиваем список заявок
                $arrayTo[$key]['applicationDataList'] = $applicationList;

                // Если заявок больше одной, распределяем сумму
                if (count($applicationList) > 1) {
                    $fullSum = (float) $arrayTo[$key]['Сумма'];

                    foreach ($arrayTo[$key]['applicationDataList'] as $keyApp => $applicationData) {
                        $cost = (float) $applicationData['transportation_cost_Client'];
                        $arrayTo[$key]['applicationDataList'][$keyApp]['MARKER'] = false;
                        if ($fullSum > 0) {
                            // Определяем сумму, которую можно выделить на заявку
                            $allocatedAmount = min($cost, $fullSum);
                            $fullSum -= $allocatedAmount;

                            $arrayTo[$key]['applicationDataList'][$keyApp] = array_merge($applicationData, [
                                'inComposition'   => true,
                                'money_received'  => $allocatedAmount
                            ]);
                        } else {
                            // Если сумма исчерпана, заявка не включается в состав платежа
                            $arrayTo[$key]['applicationDataList'][$keyApp]['inComposition'] = false;
                        }
                    }
                } else {
                    // Единственная заявка
                    $applicationData = $arrayTo[$key]['applicationDataList'][0];
                    $cost = (float) $applicationData['transportation_cost_Client'];
                    $totalAmount = (float) $arrayTo[$key]['Сумма'];
                    $applicationData['MARKER'] = false;

                    // Проверяем, не превышает ли сумма платежа стоимость заявки
                    $moneyReceived = min($cost, $totalAmount);

                    if($totalAmount > $moneyReceived){
                        $applicationData['MARKER'] = true;
                        $applicationData['MARKER-comment'] = 'Сумма операции больше чем стоимость перевозки';
                    }

                    $arrayTo[$key]['applicationDataList'][0] = array_merge($applicationData, [
                        'inComposition'  => ($moneyReceived > 0),
                        'money_received' => $moneyReceived
                    ]);
                }
                // Проверяем, была ли заявка полностью оплачена
                foreach ($arrayTo[$key]['applicationDataList'] as $keyApp => $application) {
                    $arrayTo[$key]['applicationDataList'][$keyApp]['fullPaymentClient'] =
                        ($application['transportation_cost_Client'] == $application['actual_payment_Client']);
                }
            } else {
                foreach ($applicationAccountPayment as $accountPayment) {
                    $customer = 1;
                    if($data['ПолучательИНН'] == '661221287051'){
                        $customer = 2;
                    }
                    if($data['ПолучательИНН'] == '661221186335'){
                        $customer = 3;
                    }
                    $temp = $model->getListPRRApplicationSearch(
                        // ['account_number_Client' => '%' .$accountPayment .'%', 'customer_id_Client' => $customer]
                        ['account_number_Client' => $accountPayment .'%', 'customer_id_Client' => $customer]
                    ) ?? [];
                    foreach ($temp as $tempApp){
                        $applicationList[] = $tempApp;
                    }
                    if (!empty($applicationList)) {
                        // Присваиваем список заявок
                        $arrayTo[$key]['applicationDataList'] = $applicationList;

                        // Если заявок больше одной, распределяем сумму
                        if (count($applicationList) > 1) {
                            $fullSum = (float) $arrayTo[$key]['Сумма'];

                            foreach ($arrayTo[$key]['applicationDataList'] as $keyApp => $applicationData) {
                                $cost = (float) $applicationData['cost_Client'];
                                $arrayTo[$key]['applicationDataList'][$keyApp]['MARKER'] = false;
                                if ($fullSum > 0) {
                                    // Определяем сумму, которую можно выделить на заявку
                                    $allocatedAmount = min($cost, $fullSum);
                                    $fullSum -= $allocatedAmount;

                                    $arrayTo[$key]['applicationDataList'][$keyApp] = array_merge($applicationData, [
                                        'inComposition'   => true,
                                        'money_received'  => $allocatedAmount
                                    ]);
                                } else {
                                    // Если сумма исчерпана, заявка не включается в состав платежа
                                    $arrayTo[$key]['applicationDataList'][$keyApp]['inComposition'] = false;
                                }
                            }
                        } else {
                            // Единственная заявка
                            $applicationData = $arrayTo[$key]['applicationDataList'][0];
                            $cost = (float) $applicationData['cost_Client'];
                            $totalAmount = (float) $arrayTo[$key]['Сумма'];
                            $applicationData['MARKER'] = false;

                            // Проверяем, не превышает ли сумма платежа стоимость заявки
                            $moneyReceived = min($cost, $totalAmount);

                            if($totalAmount > $moneyReceived){
                                $applicationData['MARKER'] = true;
                                $applicationData['MARKER-comment'] = 'Сумма операции больше чем стоимость перевозки';
                            }

                            $arrayTo[$key]['applicationDataList'][0] = array_merge($applicationData, [
                                'inComposition'  => ($moneyReceived > 0),
                                'money_received' => $moneyReceived
                            ]);
                        }
                        // Проверяем, была ли заявка полностью оплачена
                        foreach ($arrayTo[$key]['applicationDataList'] as $keyApp => $application) {
                            $arrayTo[$key]['applicationDataList'][$keyApp]['fullPaymentClient'] =
                                ($application['transportation_cost_Client'] == $application['actual_payment_Client']);
                        }
                    }
                    else{
                        // Если заявок нет, создаем пустой массив
                        $arrayTo[$key]['applicationDataList'] = [];
                    }
                }

            }
        }
        // dd($arrayTo);
        //    dd(['arrayTo' => $arrayTo]);
        // dd(['arrayFrom' => $arrayFrom, 'arrayTo' => $arrayTo]);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Журнал выписка',
            'listPayment' => ['arrayFrom' => $arrayFrom, 'arrayTo' => $arrayTo],
            'uniqueData' => [],
            'linkForExcelTable' => $this->request->server['REDIRECT_QUERY_STRING'] ?? '',
            'condition' => [],
            'listManager' => [],
            'listClients' => [],
            'listCarriers' => [],
            'activeHeaderItem' => 11,
            'listFilesBankStatement' => $listFilesBankStatement,
            'accessChangePayment' => true
        ]);

        $this->view('Journal/index-export-v2');
    }

    public function parsePOSTTXT()
    {
        foreach ($this->request->files as $key => $value) {
            $file = $this->request->file("$key");

            $file_upload = $file->upload('docs');

            $link = $this->storage->url($file_upload) ?? 'Error';


            $newFileBankStatement = new FileBankStatement();

            $fileName = str_replace('.'.$file->getExtension(),'',$file->name);

            $newFileBankStatement->edit([
                'idUserUpload' => $this->auth->user()->id(),
                'datetimeUpload' => date("Y-m-d H:i:s"),
                'datetimeLastOpen' => date("Y-m-d H:i:s"),
                'name' => $fileName,
                'link' => $link,
            ]);

            $newFileBankStatement->save();

            $dataFile = $newFileBankStatement->get();

            $dataFile['datetime_last_open'] = date("Y-m-d H:i:s");
            $dataFile['datatime_upload'] = date("Y-m-d H:i:s");


            print json_encode($dataFile);
        }
    }

    public  function ajaxAddNumDocPaymentParser()
    {
        $numDoc = $this->request->input('numDoc');

        $sessionNumDoc = $this->session->get('numDoc');

        if($sessionNumDoc){
            $sessionNumDoc[] = $numDoc;
            $this->session->set('numDoc', $sessionNumDoc);
        }
        else {
            $this->session->set('numDoc', [$numDoc]);
        }

        print json_encode(['session' => $this->session->get('numDoc'), 'numDoc' => $numDoc]);
    }

    public function ajaxGetRegister()
    {
        $applicationsId = $this->request->input('applications');

    }

    public function saleJournal(){
        $condition = [];

        $model = new Journal($this->database);


        $modelListUser = new UserList($this->database);
        $modelClientList = new ClientList($this->database);
        $modelCarrierList = new CarrierList($this->database);

        $date_start = $this->request->input('date-start') ?? date('Y-m-d', strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];

        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        if(!empty($clientFilter))
            $condition['client_id_Client'] = $clientFilter;

        if(!empty($carrierFilter))
            $condition['carrier_id_Carrier'] = $carrierFilter;


        $user = $this->auth->user();

        $arraySubordinates = [];
        $isROPJournal = false;
        $activeHeaderItem = 6;

        $isClosedJournal = false;

        $year = $this->request->input('year') ?? 2025;

        $selectedUser = $this->request->input('user') ?? 0;

        $conditionFilter = $condition;

        $conditionFilter['dateField']['end'] = $date_end;

        $conditionFilter['client_id_Client'] = $clientFilter;
        $conditionFilter['carrier_id_Carrier'] = $carrierFilter;
        $conditionFilter['id_user'] = $logistFilter;

        $settings = $this->auth->user()->getUserSetting();

        $dopSetting = [];
        $dopSetting['carrier-email'] = $settings['journal_email'];
        $dopSetting['gruz'] = $settings['journal_gruz'];
        $dopSetting['carrier-ati'] = $settings['journal_ati'];
        $dopSetting['driver-car'] = $settings['journal_driver_car'];
        $dopSetting['driver-number'] = $settings['journal_driver_number'];


        $condition['for_sales'] = 1;

//         dd($condition);

        $fullCRMAccess = $user->fullCRM();

        if($user->id() == 55){
            $fullCRMAccess = true;
        }

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Журнал',
            'listApplication' => $model->getListApplication($condition),
            'listPRRApplication' => $model->getListPRRApplication($condition),
            'listTSApplication' => $model->getListTSApplication($condition),
            'condition' => $conditionFilter,
            'listManager' => $modelListUser->listManagerJournal(),
            'listClients' => $modelClientList->simpleListClients(),
            'listCarriers' => $modelCarrierList->simpleListCarriers(),
            'fullCRMAccess' => $fullCRMAccess,
            'activeHeaderItem' => $activeHeaderItem,
            'dopSetting' => $dopSetting,
            'arraySubordinates' => $arraySubordinates,
            'isROPJournal' => $isROPJournal,
            'isClosedJournal' => $isClosedJournal,
            'selectedUser' => $selectedUser,
            'link' => $this->request->requestUri(),
            'year' => $year,
            'customers' => $this->database->select('customers')
        ]);

        $this->view('Journal/manager');
    }

    public function managerNew()
    {
        $condition = [];
        $model = new Journal($this->database);

        $modelListUser = new UserList($this->database);
        $modelClientList = new ClientList($this->database);
        $modelCarrierList = new CarrierList($this->database);

        $date_start = $this->request->input('date-start') ?? date('Y-m-d', strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];

        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        if(!empty($clientFilter))
            $condition['client_id_Client'] = $clientFilter;

        if(!empty($carrierFilter))
            $condition['carrier_id_Carrier'] = $carrierFilter;

        if(!empty($logistFilter))
            $condition['id_user'] = $logistFilter;

        $user = $this->auth->user();

        $arraySubordinates = [];
        $isROPJournal = false;
        $activeHeaderItem = 6;

        $isClosedJournal = false;

        $year = $this->request->input('year') ?? 2025;

        if($user->manager()){
            $arraySubordinates = $user->getSubordinatesList();

            if($this->request->input('rop') == 1 AND $arraySubordinates != []){
                $isROPJournal = true;
                $activeHeaderItem = 9;

                $condition['id_user'] = $arraySubordinates;

                $tempArray = [];

                foreach ($arraySubordinates as $subordinate) {
                    $tempUser = new User(['id' => $subordinate]);

                    $tempArray[] = ['id' => $subordinate, 'name' => $tempUser->FIO()];
                }

                $arraySubordinates = $tempArray;

            }
            else{
                $condition['id_user'] = $user->id();
            }


        }
        $selectedUser = $this->request->input('user') ?? 0;
        if($this->request->input('closed-months') == 1){
            $activeHeaderItem = 10;

            $date_start = date("$year-01-01");
            $date_end = date("$year-12-31");

            $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end];

            if($this->auth->user()->manager()){
                $condition['id_user'] = $user->id();
            }
            else{
                $condition['id_user'] = $selectedUser;
            }

            $condition['application_section_journal'] = [3,6];

            $isClosedJournal = true;
        }


        $conditionFilter = $condition;

        $conditionFilter['dateField']['end'] = $date_end;

        $conditionFilter['client_id_Client'] = $clientFilter;
        $conditionFilter['carrier_id_Carrier'] = $carrierFilter;
        $conditionFilter['id_user'] = $logistFilter;

        $settings = $this->auth->user()->getUserSetting();

        $dopSetting = [];
        $dopSetting['carrier-email'] = $settings['journal_email'];
        $dopSetting['gruz'] = $settings['journal_gruz'];
        $dopSetting['carrier-ati'] = $settings['journal_ati'];
        $dopSetting['driver-car'] = $settings['journal_driver_car'];
        $dopSetting['driver-number'] = $settings['journal_driver_number'];

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Журнал',
            'listApplication' => $model->getListApplication($condition),
            'listPRRApplication' => $model->getListPRRApplication($condition),
            'listTSApplication' => $model->getListTSApplication($condition),
            'condition' => $conditionFilter,
            'listManager' => $modelListUser->listManagerJournal(),
            'listClients' => $modelClientList->simpleListClients(),
            'listCarriers' => $modelCarrierList->simpleListCarriers(),
            'fullCRMAccess' => $user->fullCRM(),
            'activeHeaderItem' => $activeHeaderItem,
            'dopSetting' => $dopSetting,
            'arraySubordinates' => $arraySubordinates,
            'isROPJournal' => $isROPJournal,
            'isClosedJournal' => $isClosedJournal,
            'selectedUser' => $selectedUser,
            'link' => $this->request->requestUri(),
            'year' => $year,
            'customers' => $this->database->select('customers')
        ]);

        $this->view('Journal/manager');
    }
    public function manager()
    {
        $condition = [];
        $model = new Journal($this->database);

        $modelListUser = new UserList($this->database);
        $modelClientList = new ClientList($this->database);
        $modelCarrierList = new CarrierList($this->database);

        $date_start = $this->request->input('date-start') ?? date('Y-m-d', strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

        if(date('d', strtotime($date_end)) == '01' AND !$this->request->input('date-end'))
            $date_start = $this->request->input('date-start') ?? date('Y-m-01', strtotime("-1 months"));

        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];
        $condition['customer_id_Carrier'] = $this->request->input('customer') ?? 1;
        $condition['application_section_journal'] = $this->request->input('status') ?? 1;

        $clientFilter = $this->request->input('client') ?? [];
        $carrierFilter = $this->request->input('carrier') ?? [];
        $logistFilter = $this->request->input('logist') ?? [];

        if(!empty($clientFilter))
            $condition['client_id_Client'] = $clientFilter;

        if(!empty($carrierFilter))
            $condition['carrier_id_Carrier'] = $carrierFilter;

        if(!empty($logistFilter))
            $condition['id_user'] = $logistFilter;

        $user = $this->auth->user();

        $arraySubordinates = [];

        if($user->manager()){
            $condition['id_user'] = $user->id();

            $arraySubordinates = $user->getSubordinatesList();
        }

        $conditionFilter = $condition;

        $conditionFilter['dateField']['end'] = $date_end;
        $link = $this->request->requestUri();

        if($this->request->isGET()) {
            $link = str_replace(
                [
                    '?customer=1', '&customer=1',
                    '?customer=2', '&customer=2',
                    '?customer=3', '&customer=3',
                    '?status=1', '&status=1',
                    '?status=2', '&status=2',
                    '?status=3', '&status=3',
                    '?status=4', '&status=4',
                    '?status=5', '&status=5',
                ],
                '',
                $link
            );

            if($link != '/journal/manager')
                $link .= '&';
            else
                $link .= '?';
        }
        else
            $link .= '?';

        $linkStatus = $this->request->requestUri();
        if($this->request->isGET()) {
            $linkStatus = str_replace(
                [
                    '?status=1', '&status=1',
                    '?status=2', '&status=2',
                    '?status=3', '&status=3',
                    '?status=4', '&status=4',
                    '?status=5', '&status=5',
                ],
                '',
                $linkStatus
            );

            if($linkStatus != '/journal/manager')
                $linkStatus .= '&';
            else
                $linkStatus .= '?';
        }
        else
            $linkStatus .= '?';

        $conditionFilter['client_id_Client'] = $clientFilter;
        $conditionFilter['carrier_id_Carrier'] = $carrierFilter;
        $conditionFilter['id_user'] = $logistFilter;

        $dopSetting = [];
        $dopSetting['carrier-email'] = $this->session->get('carrier-email') ?? false;
        $dopSetting['gruz'] = $this->session->get('gruz') ?? false;
        $dopSetting['carrier-ati'] = $this->session->get('carrier-ati') ?? false;
        $dopSetting['driver-car'] = $this->session->get('driver-car') ?? false;

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Журнал',
            'listApplication' => $model->getListApplication($condition),
            'link' => $link,
            'linkStatus' => $linkStatus,
            'condition' => $conditionFilter,
            'listManager' => $modelListUser->listManager(),
            'listClients' => $modelClientList->simpleListClients(),
            'listCarriers' => $modelCarrierList->simpleListCarriers(),
            'fullCRMAccess' => $user->fullCRM(),
            'activeHeaderItem' => 6,
            'arraySubordinates' => $arraySubordinates,
            "dopSetting" => $dopSetting
        ]);

        $this->view('Journal/manager');
    }
//    public function managerPrevVersion()
//    {
//        $condition = [];
//
//        $model = new Journal($this->database);
//
//        $modelListUser = new UserList($this->database);
//        $modelClientList = new ClientList($this->database);
//        $modelCarrierList = new CarrierList($this->database);
//
//        $date_start = $this->request->input('date-start') ?? date('Y-m-d', strtotime('-60 days'));
//        $date_end = $this->request->input('date-end') ?? date('Y-m-d');
//
//        if(date('d', strtotime($date_end)) == '01' AND !$this->request->input('date-end'))
//            $date_start = $this->request->input('date-start') ?? date('Y-m-01', strtotime("-1 months"));
//
//        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));
//
//        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];
//        $condition['customer_id_Carrier'] = $this->request->input('customer') ?? 1;
//        $condition['application_section_journal'] = $this->request->input('status') ?? 1;
//
//        $clientFilter = $this->request->input('client') ?? [];
//        $carrierFilter = $this->request->input('carrier') ?? [];
//        $logistFilter = $this->request->input('logist') ?? [];
//
//        if(!empty($clientFilter))
//            $condition['client_id_Client'] = $clientFilter;
//
//        if(!empty($carrierFilter))
//            $condition['carrier_id_Carrier'] = $carrierFilter;
//
//        if(!empty($logistFilter))
//            $condition['id_user'] = $logistFilter;
//
//        $user = $this->auth->user();
//
//        if($user->manager()){
//            $condition['id_user'] = $user->id();
//        }
//
//        $conditionFilter = $condition;
//
//        $conditionFilter['dateField']['end'] = $date_end;
//        $link = $this->request->requestUri();
//
//        if($this->request->isGET()) {
//            $link = str_replace(
//                [
//                    '?customer=1', '&customer=1',
//                    '?customer=2', '&customer=2',
//                    '?customer=3', '&customer=3',
//                    '?status=1', '&status=1',
//                    '?status=2', '&status=2',
//                    '?status=3', '&status=3',
//                    '?status=4', '&status=4',
//                    '?status=5', '&status=5',
//                ],
//                '',
//                $link
//            );
//
//            if($link != '/journal/manager')
//                $link .= '&';
//            else
//                $link .= '?';
//        }
//        else
//            $link .= '?';
//
//        $linkStatus = $this->request->requestUri();
//        if($this->request->isGET()) {
//            $linkStatus = str_replace(
//                [
//                    '?status=1', '&status=1',
//                    '?status=2', '&status=2',
//                    '?status=3', '&status=3',
//                    '?status=4', '&status=4',
//                    '?status=5', '&status=5',
//                ],
//                '',
//                $linkStatus
//            );
//
//            if($linkStatus != '/journal/manager')
//                $linkStatus .= '&';
//            else
//                $linkStatus .= '?';
//        }
//        else
//            $linkStatus .= '?';
//
//        $conditionFilter['client_id_Client'] = $clientFilter;
//        $conditionFilter['carrier_id_Carrier'] = $carrierFilter;
//        $conditionFilter['id_user'] = $logistFilter;
//
//        $dopSetting = [];
//        $dopSetting['carrier-email'] = $this->session->get('carrier-email') ?? false;
//        $dopSetting['gruz'] = $this->session->get('gruz') ?? false;
//        $dopSetting['carrier-ati'] = $this->session->get('carrier-ati') ?? false;
//        $dopSetting['driver-car'] = $this->session->get('driver-car') ?? false;
//
//        $this->extract([
//            'controller' => $this,
//            'titlePage' => 'Журнал',
//            'listApplication' => $model->getListApplication(['id_user' => 2]),
//            'link' => $link,
//            'linkStatus' => $linkStatus,
//            'condition' => $conditionFilter,
//            'listManager' => $modelListUser->listManager(),
//            'listClients' => $modelClientList->simpleListClients(),
//            'listCarriers' => $modelCarrierList->simpleListCarriers(),
//            'fullCRMAccess' => $user->fullCRM(),
//            'activeHeaderItem' => 6,
//            "dopSetting" => $dopSetting
//        ]);
//
//        $this->view('Journal/manager');
//    }
    public function ajaxLoadApplications()
    {
        $model = new Journal($this->database);
        $date_start = $this->request->input('date-start') ?? date('Y-m-d', strtotime('-30 days'));
        $date_end = $this->request->input('date-end') ?? date('Y-m-d');

//        if(date('d', strtotime($date_end)) == '01' AND !$this->request->input('date-end'))
//            $date_start = $this->request->input('date-start') ?? date('Y-m-01', strtotime("-1 months"));

        $date_end_filter = date('Y-m-d', strtotime($date_end . ' +1 day'));

        $condition['dateField'] = ['name' => 'date', 'start' => $date_start, 'end' => $date_end_filter];
        if($this->request->input('id_user') != '')
            $condition['id_user'] = $this->request->input('id_user');

        if($this->request->input('client') != '')
            $condition['client_id_Client'] = $this->request->input('client');

        if($this->request->input('carrier') != '')
            $condition['client_id_Carrier'] = $this->request->input('carrier');


        $condition['application_section_journal'] = $this->request->input('section');
        $condition['customer_id_Client'] = $this->request->input('customer');

        print json_encode($model->getListApplication($condition));
    }

    public function ajaxSaveManagerComment()
    {
        $id = $this->request->input('id');
        $comment = $this->request->input('comment');

        print $this->database->update('applications',['manager_comment' => $comment],['id' => $id]);
    }

    public function ajaxChangeDopSetting()
    {
        $name = $this->request->input('name');
        $status = $this->request->input('status');

        $field = 'journal_';

        switch ($name){
            case 'gruz':
                $field .= 'gruz';
                break;
            case 'carrier-email':
                 $field .= 'email';
                 break;
            case 'carrier-ati':
                $field .= 'ati';
                break;
            case 'driver-car':
                $field .= 'driver_car';
                break;
            case 'driver-number':
                $field .= 'driver_number';
                break;
        }

        $this->database->update('user_settings',[$field => $status],['id_user' => $this->auth->user()->id()]);
        print json_encode(['result' => true]);

    }

    public function ajaxChangeEventStatus()
    {
        $id = $this->request->input('id');

        $this->database->update('manager_journal_event',['status' => 1],['id' => $id]);
    }

    public function ajaxChangeStatusApplicationJournal()
    {
        $id = $this->request->input('id');
        $status = $this->request->input('status');
        $applicationDateActualUnloading = NULL;

        $typeApp = $this->request->input('type');

        $nameTable = 'applications';

        switch ($typeApp){
            case 'ts':
                $nameTable = 'ts_application';
                break;
            case 'prr':
                $nameTable = 'prr_application';
                break;
        }

        if($status == 'В пути' OR $status == 'Е.Н.П' OR $status == 'В работе'){
            $sectionApplication = 1;
        }
        elseif ($status == 'Выгрузился'){
            $sectionApplication = 2;
            $applicationDateActualUnloading = $this->request->input('actual_date_unloading');

            if($nameTable == 'applications'){

                $applicationData = $this->database->first('applications',['id' => $id,'customer_id_Client' => 2],['id_user','id','application_number','carrier_id_Carrier']);

                if($applicationData){
//                    $mailUser = $this->auth->user()->get(['email'])['email'];

                    $applicationNumber = $applicationData['application_number'];
                    $applicationId = $applicationData['id'];
                    $user = $this->database->first('users',['id' => $applicationData['id_user']]);

                    $carrierName = $this->database->first('carriers',['id' => $applicationData['carrier_id_Carrier']])['name'];

                    $message = [
                        'Необходимо сообщить перевозчику (' .$carrierName .
                            ') о перевыставлении счета по заявке № ' .$applicationNumber .' на ИП Беспутин 
                            <a target="_blank" href="https://pegas-tech.online/application?id=' .$applicationId .'">Открыть заявку</a>'
                    ];
                    $this->mailer->sendMail($message, $user['email']);
                }


            }

        }

        $oldDateActualUnloading = $this->database->first(
            $nameTable,
            ['id' => $id],
            ['application_date_actual_unloading']
        );

        
        $this->database->update(
            $nameTable,
            [
                'application_status_journal' => $status,
                'application_date_actual_unloading' => $applicationDateActualUnloading
            ],
            ['id' => $id]
        );

        if($status == 'В пути'){
            $this->database->update(
            $nameTable,
            [
                'actual_date_in_way' => date('Y-m-d')
            ],
            ['id' => $id]
        );
        
        }

        $this->database->update(
            $nameTable,
            ['application_section_journal' => $sectionApplication],
            ['id' => $id, 'cancelled' => 0]
        );
        if($nameTable == 'applications')
            $this->database->insert("changes", [
                "changes" => json_encode([[
                    "key" => 'application_date_actual_unloading',
                    "oldValue" => $oldDateActualUnloading['application_date_actual_unloading'],
                    "newValue" => $applicationDateActualUnloading
                ]], JSON_UNESCAPED_UNICODE),
                "application_id" => $id,
                "user_id" => $this->auth->user()->id(),
                "datetime" =>  date("Y-m-d H:i:s")
            ]);



        print json_encode(['id' => $id, 'status' => $status]);
    }
    public function ajaxChangePaymentStatusCancel()
    {
        $idApplications = $this->request->input('id');
        $name = $this->request->input('name');
        $applicationType = $this->request->input('applicationType');
        $errors = [];
        foreach ($idApplications as $idApplication) {
            switch ($applicationType) {
                case 'prr':
                    $application = new PRRApplication(['id' => $idApplication]);
                    $tableHistory = 'prr_history_payment';
                    $tableManager = 'prr_manager_journal_event';
                    break;
                case 'ts':
                    $application = new TSApplication(['id' => $idApplication]);
                    $tableHistory = 'ts_history_payment';
                    $tableManager = 'ts_manager_journal_event';
                    break;
                default:
                    $application = new Application(['id' => $idApplication]);
                    $tableHistory = 'history_payment';
                    $tableManager = 'manager_journal_event';
                    break;
            }

            $oldValue = $application->get()["application_status"];

            $deleteJournalEvent = $this->database->delete(
                $tableManager,
                [
                    'application_id' => $idApplication,
                    'event' =>  $name .'_payment_status',
                ]
            );

            $side = 1;
            if($name == 'client')
                $side = 0;

            $deleteHistoryPayment = $this->database->delete(
                $tableHistory,
                ['id_application' => $idApplication, 'side' => $side]
            );

            $this->database->insert("changes", [
                "changes" => json_encode([[
                    "key" => $name .'_payment_status',
                    "oldValue" => $oldValue,
                    "newValue" => 'Ожидается счет'
                ]], JSON_UNESCAPED_UNICODE),
                "application_id" => $idApplication,
                "datetime" =>  date("Y-m-d H:i:s")
            ]);

            $application->edit([$name .'_payment_status' => 'Ожидается счет']);

            $nameInTable = 'Carrier';

            if($name == 'client')
                $nameInTable = 'Client';

            switch ($applicationType) {
                case 'prr':
                    $cost = $application->get()["cost_" . mb_strtolower($nameInTable)];
                    break;
                case 'ts':
                    $cost = $application->get()["cost_" . $nameInTable];
                    break;
                default:
                    $cost = $application->get()["transportation_cost_" . $nameInTable];
                    break;
            }

            $application->edit(['actual_payment_' .$nameInTable => 0]);

            $application->edit(['full_payment_date_' .$nameInTable => NULL]);

            if(! $application->save() AND ! $deleteHistoryPayment AND !$deleteJournalEvent)
                $errors[] = 1;
        }

        if(count($errors) == 0){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }
    public function ajaxChangePaymentStatus()
    {
        $idApplications = $this->request->input('id');
        $name = $this->request->input('name');
        $applicationType = $this->request->input('applicationType');
        $errors = [];

        foreach ($idApplications as $idApplication) {
            switch ($applicationType) {
                case 'prr':
                    $application = new PRRApplication(['id' => $idApplication]);
                    $tableApplication = 'prr_application';
                    $tableHistory = 'prr_history_payment';
                    $tableManager = 'prr_manager_journal_event';
                    break;
                case 'ts':
                    $application = new TSApplication(['id' => $idApplication]);
                    $tableApplication = 'ts_application';
                    $tableHistory = 'ts_history_payment';
                    $tableManager = 'ts_manager_journal_event';
                    break;
                default:
                    $application = new Application(['id' => $idApplication]);
                    $tableApplication = 'applications';
                    $tableHistory = 'history_payment';
                    $tableManager = 'manager_journal_event';
                    break;
            }

            $oldValue = $application->get()["application_status"];

            $this->database->insert(
                $tableManager,
                [
                    'application_id' => $idApplication,
                    'event' => $name . '_payment_status'
                ]
            );

            $this->database->insert("changes", [
                "changes" => json_encode([[
                    "key" => $name . '_payment_status',
                    "oldValue" => $oldValue,
                    "newValue" => 'Оплачено полностью'
                ]], JSON_UNESCAPED_UNICODE),
                "application_id" => $idApplication,
                "datetime" => date("Y-m-d H:i:s")
            ]);

            $application->edit([$name . '_payment_status' => 'Оплачено полностью']);

            $nameInTable = 'Carrier';

            $side = 1;

            if ($name == 'client') {
                $nameInTable = 'Client';
                $side = 0;
            }
            if($name == 'prr')
                $nameInTable = 'Prr';

            switch ($applicationType) {
                case 'prr':
                    $cost = $application->get()["cost_" . mb_strtolower($nameInTable)];
                    break;
                case 'ts':
                    $cost = $application->get()["transportation_cost"];
                    break;
                default:
                    $cost = $application->get()["transportation_cost_" . $nameInTable];
                    break;
            }


            $application->edit(['actual_payment_' . $name => $cost]);

//            var_dump($application->get());

            $this->database->update(
                $tableApplication,
                [
                    'full_payment_date_' .$nameInTable => date('Y-m-d'),
                    'actual_payment_' . $nameInTable => $cost
                ],
                ['id' => $application->id()]
            );

//            $application->edit(['full_payment_date_' . $nameInTable => date('Y-m-d')]);

//            var_dump($application->get());

            if (!$application->save())
                $errors[] = 1;

            $this->database->insert(
                $tableHistory,
                [
                    'id_user' => $this->auth->user()->id(),
                    'id_user_application' => $this->database->first(
                        $tableApplication,
                        ['id' => $application->id()],
                        ['id_user'])['id_user'],
                    'id_application' => $application->id(),
                    'side' => $side,
                    'quantity' => $cost,
                    'date' => date('Y-m-d H:i:s')
                ]
            );
        }

        if(count($errors) == 0){

            print json_encode(['result' => true, 'cost' => number_format($cost,0,'.',' ') .' ₽','nameInTable' => $nameInTable]);
        }
        else{
            print json_encode(['result' => false]);
        }

        $modelChangeApplicationStatus = new ApplicationChangeStatus($this->database);

        $modelChangeApplicationStatus->checkPossibleChangeStatus($idApplications[0]);


        if($applicationType == 'prr'){
            $modelChangeApplicationStatus = new PRRApplicationChangeStatus($this->database);

            $modelChangeApplicationStatus->checkPossibleChangeStatus($idApplications[0]);
        }
    }
}
