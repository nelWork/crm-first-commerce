<?php

namespace App\User\Contoller\Common;

use App\Model\User\User;
use App\User\Contoller\Controller;
use App\User\Model\Analytics\AnalyticsDocuments;
use App\User\Model\Analytics\Declaration;
use App\User\Model\Analytics\FineManager;
use App\User\Model\Analytics\ManagerList;
use App\User\Model\Analytics\PaymentsManager;
use App\User\Model\Analytics\Report;
use App\User\Model\Analytics\DebtorCreditor;
use App\User\Model\Analytics\Salary;
use App\User\Model\Analytics\NetProfitStat;
use App\User\Model\Analytics\SalaryList;
use App\User\Model\Analytics\SalaryStatistics;
use App\User\Model\Analytics\Statistics;
use App\User\Model\Application\ApplicationList;
use App\User\Model\Application\Plan;
use App\User\Model\Carrier\CarrierList;
use App\User\Model\Client\ClientList;
use App\User\Model\User\UserList;
use App\Mailer\Mailer;
use App\Config\Config;
use App\User\Model\Journal\Journal;


class AnalyticsController extends Controller
{
    public function debtorCreditor(){

        $debtorCreditorModel = new DebtorCreditor($this->database);

        $data = $debtorCreditorModel->getReportDebCred();
        // dd($data);
        // dd($data['debtor']['list']['ООО «Тюменская фабрика бумажных изделий»']);
        $this->extract([
            'controller' => $this,
            'data' => $data,
            'titlePage' => 'Дебиторка и кредиторка'
        ]);

        $this->view('Analytics/debtor-creditor');
    }
    public function ajaxDownloadDebtorCreditorReportExcel(){
        $debtorCreditorModel = new DebtorCreditor($this->database);

        $data = $debtorCreditorModel->getReportDebCred();


        $report = new Report($this->database);

        $report->createDebtorCreditReportExcel($data);

    }
    public function ajaxDownloadReportExcel(){

        $report = new Report($this->database);

        $modelUserList = new UserList($this->database);

        $isManagerSelect = false;
        $isRopSelect = false;
        $conditions = [
            'application_section_journal' => [1,2,3,6],
            'application_status_journal' => ['В пути', 'Выгрузился', 'В работе']
        ];

        $date = date('01.m.Y') .' - ' . date('d.m.Y');

        if($this->request->input('date')){
            $date = $this->request->input('date');
        }


        $dateArray = explode(' - ',$date);

        $conditions['dateField'] = [
            'name' => 'date',
            'start' => date('Y-m-d',strtotime($dateArray[0])),
            'end' => date('Y-m-d 23:59:59', strtotime($dateArray[1]))
        ];

        if($this->request->input('id-user')){
            $conditions['id_user'] = $this->request->input('id-user');
            $isManagerSelect = true;
        }

        if($this->request->input('id-rop')){
            $rop = new User(['id' => $this->request->input('id-rop')]);

            $conditions['id_user'] = $rop->getSubordinatesList();

            $conditions['id_user'][] =  $rop->id();
            $isRopSelect = true;

        }

        if($this->request->input('customer')){
            $conditions['customer_id_Client'] = $this->request->input('customer');
        }



        $dataPL = $report->createPLReportExcel($conditions);


    }
    public function updateHistoryPayment()
    {
        $historyPaymentDB = $this->database->select('history_payment', [],[],-1);

        foreach ($historyPaymentDB as $historyPayment) {
            $idUser = $this->database->first(
                'applications',
                ['id' => $historyPayment['id_application']],
                ['id_user']
            )['id_user'];
            $this->database->update(
                'history_payment',
                ['id_user_application' => $idUser],
                ['id' => $historyPayment['id']]
            );
        }
    }

    public function netProfitStat(){

        $netProfitStatModel = new NetProfitStat($this->database);

        $conditions = [
            'application_section_journal' => [1,2,3,6],
            'actual_payment_Client >' => 0
        ];

        $date = date('01.m.Y') .' - ' . date('d.m.Y');

        if($this->request->input('date')){
            $date = $this->request->input('date');
        }

        $dateArray = explode(' - ',$date);

        $conditions['dateField'] = [
            'name' => 'date',
            'start' => date('Y-m-d',strtotime($dateArray[0])),
            'end' => date('Y-m-d 23:59:59', strtotime($dateArray[1]))
        ];

        $paymentApplication = $this->database->superSelect('history_payment',[
            'side' => 0,
            'dateField' => [
                'name' => 'date',
                'start' => date('Y-m-d',strtotime($dateArray[0])),
                'end' => date('Y-m-d 23:59:59', strtotime($dateArray[1]))
            ]
        ]);

        $listId = [];

        foreach($paymentApplication as $payment){
            $listId[] = $payment['id_application'];
        }

        $conditions = ['id' => $listId];

        $quantityDayForSalary = $this->countDayBetweenDate($dateArray[0], $dateArray[1]);

        $netProfitStat = $netProfitStatModel->getStatistics($conditions, $quantityDayForSalary);

        $this->extract([
            'controller' => $this,
            'titlePage' => "ДДС",
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
            'netProfitStat' => $netProfitStat,
            'date' => $date
        ]);

        $this->view("Analytics/net-profit-stat");
    }

    public function countDayBetweenDate($date1, $date2){
        return round(abs(strtotime($date1) - strtotime($date2)) / 86400) + 1;
    }

    public function journalReport()
    {
        $this->extract([
            'controller' => $this,
            'titlePage' => "Ведомость",
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
        ]);

        $this->view("Analytics/journalReport");
    }

    public function getListManagerApplicationExcel()
    {
        $idManager = $this->request->input('id-manager');

        $listApplicationsFull = $this->database->select('applications', ['id_user' => $idManager, 'cancelled' => 0], ['id' => 'DESC'], -1);

        $listApplications = [];

        foreach ($listApplicationsFull as $application) {
            $tempData = [
                'date' => date('d.m.Y', strtotime($application['date'])),
                'id' => $application['id'],
                'transportation_cost_Carrier' => $application['transportation_cost_Carrier'],
                'transportation_cost_Client' => $application['transportation_cost_Client'],
                'taxation_type_Carrier' => $application['taxation_type_Carrier'],
                'taxation_type_Client' => $application['taxation_type_Client'],
                'application_number' => $application['application_number'],
                'application_number_client' => $application['application_number_client'],
                'account_number_Client' => $application['account_number_Client'],
                'manager_share' => $application['manager_share'],
                'application_walrus' => $application['application_walrus'],
                'application_section_journal' => $application['application_section_journal'],
                'unfulfilledConditions' => [],
                'actual_payment_Carrier' => $application['actual_payment_Carrier'],
                'actual_payment_Client' => $application['actual_payment_Client'],
            ];

            $additionalIdDocument = [];


            if($tempData['application_section_journal'] == 2){
                $listIdDocument = array_merge([1,2,10,13,14], $additionalIdDocument);

                $files = $this->database->superSelect(
                    'files',
                    ['application_id' => $tempData['id'], 'document_id' => $listIdDocument]
                ) ?? [];

                $listNameDocument = [
                    0 => 'Оплата от клиента (Б)',
                    1 => 'Подписанная заявка от перевозчика (Л)',
                    2 => 'Фото ТТН/ТН/ЭР с выгрузки (Л)',
                    10 => 'Подписанная заявка от клиента (Л)',
                    13 => 'Счета для клиента (Б)',
                    14 => 'АКТ/УПД для клиента (Б)'
                ];

                $counterAdditional = 1;

                if(strtotime($tempData['date']) > strtotime('2025-04-01')) {
                    foreach ($tempData['additional_expenses'] as $expenses) {
                        if ($expenses['type_expenses'] !== 'Страховка' and $expenses['type_expenses'] !== 'Вычет') {
                            $listNameDocument[100 + $counterAdditional] = 'Доп затрата - ' . $expenses['type_expenses'] . ' ('
                                . number_format((float)$expenses['sum'], 0, '.', ' ') . ' ₽ '
                                . $expenses['type_payment'] . ') (Л)';
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

                if($tempData['actual_payment_Client'] === $tempData['transportation_cost_Client'])
                    unset($listNameDocument[0]);

                $tempData['unfulfilledConditions'] = $listNameDocument;
            }
            if($tempData['application_section_journal'] == 3){
                $listIdDocument = [18,4,5];

                $files = $this->database->superSelect(
                    'files',
                    ['application_id' => $tempData['id'], 'document_id' => $listIdDocument]
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
                    ['id_application' => $tempData['id'], 'name' => $listNameCommentDocument]
                ) ?? [];


                foreach ($commentsDocument as $comment) {
                    foreach ($listNameCommentDocument as $key => $documentName) {
                        if($comment['name'] == $documentName AND $comment['comment'] != ''){
                            unset($listNameCommentDocument[$key]);
                        }
                    }
                }

                $listNameDocument += $listNameCommentDocument;

                if($tempData['actual_payment_Carrier'] === $tempData['transportation_cost_Carrier'])
                    unset($listNameDocument[0]);

                $tempData['unfulfilledConditions'] = $listNameDocument;

            }

            $additionalExpenses = $this->database->select('additional_expenses', ['id_application' => $application['id']], [], -1);

            $additionalExpensesText = '';
            $additionalExpensesSumNDS = 0;
            $additionalExpensesSumBezNDS = 0;
            $additionalExpensesSumNAL = 0;

            foreach ($additionalExpenses as $additionalExpense) {
                $additionalExpensesText .= $additionalExpense['type_expenses'] . ' '
                    . $additionalExpense['sum'] . ' ' . $additionalExpense['type_payment'] . ', ';

                switch ($additionalExpense['type_payment']):
                    case 'Б/НДС':
                        $additionalExpensesSumBezNDS += (float)$additionalExpense['sum'];
                        break;
                    case 'НАЛ':
                        $additionalExpensesSumNAL += (float)$additionalExpense['sum'];
                        break;
                    default:
                        $additionalExpensesSumNDS += (float)$additionalExpense['sum'];
                        break;
                endswitch;
            }

            $tempData['into_salary'] = "Нет";


            $alreadyIntoSalary = $this->database->superSelect(
                'salary',
                ['closed_applications' => '%|' . $application['application_number'] . '|%'],
                [],
                1,
                ['id'],
                0,
                'AND',
                'LIKE'
            );

            if ($alreadyIntoSalary) {
                $tempData['into_salary'] = "Да";
            }


            $tempData['additionalExpensesText'] = $additionalExpensesText;
            $tempData['additionalExpensesSumNDS'] = $additionalExpensesSumNDS;
            $tempData['additionalExpensesSumBezNDS'] = $additionalExpensesSumBezNDS;
            $tempData['additionalExpensesSumNAL'] = $additionalExpensesSumNAL;



            $tempData['carrier'] = $this->database->first(
                'carriers',
                ['id' => $application['carrier_id_Carrier']],
                ['name']
            )['name'];


            $tempData['client'] = $this->database->first(
                'clients',
                ['id' => $application['client_id_Client']],
                ['name']
            )['name'];

            $tempData['actual_payment_Client'] = $application['actual_payment_Client'];
            $tempData['actual_payment_Carrier'] = $application['actual_payment_Carrier'];

            $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'];
            $transportationCostCarrierNDS = '0';

            if($application['taxation_type_Carrier'] == 'С НДС'){
                $transportationCostCarrierWithoutNDS = $application['transportation_cost_Carrier'] / 1.2;

                $transportationCostCarrierNDS = $application['transportation_cost_Carrier'] / 6;
            }


            $transportationCostClientWithoutNDS = $application['transportation_cost_Client'];
            $transportationCostClientNDS = '0';

            if($application['taxation_type_Client'] == 'С НДС'){
                $transportationCostClientWithoutNDS = $application['transportation_cost_Client'] / 1.2;

                $transportationCostClientNDS = $application['transportation_cost_Client'] / 6;
            }

            $tempData['transportationCostClientWithoutNDS'] = $transportationCostClientWithoutNDS;
            $tempData['transportationCostCarrierWithoutNDS'] = $transportationCostCarrierWithoutNDS;

            $tempData['transportationCostClientNDS'] = $transportationCostClientNDS;
            $tempData['transportationCostCarrierNDS'] = $transportationCostCarrierNDS;

            $listApplications[] = $tempData;

        }

            $analyticsDocument = new AnalyticsDocuments();

            $analyticsDocument->createListManagerApplicationExcel($listApplications);

            print json_encode(['status' => true, 'link_file' => '/doc/list-manager-applications.xlsx']);

            //dd($listApplications);
        }


    public function declaration()
    {
        $declaration = new Declaration($this->database);

        $periodGET = '';

        if($this->request->input('period')){
            $periodGET = $this->request->input('period');
            $period = explode(' - ',$this->request->input('period'));
            $date_start = $period[0];
            $date_end = $period[1];

        }
        else{

            if (date('d') < 20) {
                $date_start = date("Y-m-20", strtotime("-1 month"));
                $date_end = date("Y-m-19");
            } else {
                $date_start = date("Y-m-20");
                $date_end = date("Y-m-19", strtotime('first day of next month'));
            }
        }


        $this->extract([
            'controller' => $this,
            'declaration' => $declaration->getDeclaration(['date_start' => $date_start, 'date_end' => $date_end]),
            'titlePage' => "Ведомость",
            'period' => $periodGET,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
        ]);

        $this->view("Analytics/declaration");
    }
    public function carrierStat(){
        $date = $this->request->input('date');

        if(!$date){
            $date = date('01.m.Y') .' - ' . date('d.m.Y');
        }

        $this->extract([
            'controller' => $this,
            'titlePage' => "Статистика по перевозчикам",
            'date' => $date,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
        ]);

        $this->view("Analytics/carrier-stat");
    }
    public function ajaxGetExcelCarrierStat(){

        $date = $this->request->input('date');

        $period = explode(' - ',$date);
        $date_start = date('Y-m-d',strtotime($period[0]));
        $date_end = date('Y-m-d 23:59:59',strtotime($period[1]));

        $model = new Journal($this->database);

        $applicationList = $this->database->superSelect(
            'applications',
            [
                'dateField' => [
                    'name' => 'date',
                    'start' => $date_start,
                    'end' => $date_end
                ],
                'application_section_journal' => [1,2,3,6]
            ]
        );

        // var_dump($applicationList);

        $result = [];

        foreach ($applicationList as $item) {
            $userId = $item['id_user'];
            $type = $item['taxation_type_Carrier'];
            $cost = $item['transportation_cost_Carrier'];

            if (!isset($result[$userId])) {
                $result[$userId] = [
                    'sum_with_nds' => 0,
                    'sum_without_nds' => 0,
                    'percent_with_nds' => 0,
                    'percent_without_nds' => 0,
                ];
            }

            if ($type === 'С НДС' || $type == 1) {
                $result[$userId]['sum_with_nds'] += $cost;
            } elseif ($type === 'Б/НДС' || $type == 2) {
                $result[$userId]['sum_without_nds'] += $cost;
            }
        }

        // Добавляем проценты
        foreach ($result as $userId => &$r) {
            $total = $r['sum_with_nds'] + $r['sum_without_nds'];
            if ($total > 0) {
                $r['percent_with_nds'] = round($r['sum_with_nds'] / $total * 100, 2);
                $r['percent_without_nds'] = round($r['sum_without_nds'] / $total * 100, 2);
            }
        }

        

        $new_result = [];

        foreach ($result as $userId => $data) {
            // Получаем данные пользователя из базы
            $user = $this->database->first('users',['id' => $userId]);

            // Если пользователь найден
            if ($user) {
                $full_name = trim($user['name'] . ' ' . $user['surname']);
            } else {
                $full_name = "Неизвестный пользователь (ID: $userId)";
            }

            // Присваиваем новое имя в качестве ключа
            $new_result[$full_name] = $data;
        }

        $nameFileUserCarrierStat = 'journal.xlsx';
        $model->createExcelTableManagerCarrierStat($new_result, $nameFileUserCarrierStat);
    }

    public function report()
    {
        $report = new Report($this->database);

        $modelUserList = new UserList($this->database);

        $isManagerSelect = false;
        $isRopSelect = false;
        $conditions = [
            'application_section_journal' => [1,2,3,6],
            'application_status_journal' => ['В пути', 'Выгрузился', 'В работе']
        ];

        $date = date('01.m.Y') .' - ' . date('d.m.Y');

        if($this->request->input('date')){
            $date = $this->request->input('date');
        }


        $dateArray = explode(' - ',$date);

        $conditions['dateField'] = [
            'name' => 'date',
            'start' => date('Y-m-d',strtotime($dateArray[0])),
            'end' => date('Y-m-d 23:59:59', strtotime($dateArray[1]))
        ];

        if($this->request->input('id-user')){
            $conditions['id_user'] = $this->request->input('id-user');
            $isManagerSelect = true;
        }

        if($this->request->input('id-rop')){
            $rop = new User(['id' => $this->request->input('id-rop')]);

            $conditions['id_user'] = $rop->getSubordinatesList();

            $conditions['id_user'][] =  $rop->id();
            $isRopSelect = true;

        }

        if($this->request->input('customer')){
            $conditions['customer_id_Client'] = $this->request->input('customer');
        }

        $page = "Analytics/report";

        switch ($this->request->input('type')):
            case '2':
                $data = $report->getReportTypeDDS($conditions);
                $page = "Analytics/report-dds";
                break;
            case '3':
                break;
            default:
                $data = $report->getReportTypeFR($conditions);
                break;
        endswitch;

        $dataPL = $report->getReportTypePL($conditions);
        // dd($data);
        // dd($conditions);

        $this->extract([
            'controller' => $this,
            'report' => $data,
            'titlePage' => "P&L",
            'date' => $date,
            'isManagerSelect' => $isManagerSelect,
            'isRopSelect' => $isRopSelect,
            'managerList' => $modelUserList->minListManager(),
            'ropList' => $modelUserList->listROP() ,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
            'dataPL' => $dataPL
        ]);

        $this->view($page);
    }


    public function index()
    {

        $statisticModel = new Statistics($this->database);


        $periodGET = '';

        if($this->request->input('period')){
            $periodGET = $this->request->input('period');
            $period = explode(' - ',$this->request->input('period'));
            $date_start = $period[0];
            $date_end = $period[1];
            $statistics = $statisticModel->getStatistics(['period' => ['date_start' => $date_start, 'date_end' => $date_end]]);

        }
        else{
            $statistics = $statisticModel->getStatistics();
        }

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Аналитика',
            'activeHeaderItem' => 2,
            'period' => $periodGET,
            'statistics' => $statistics
        ]);

        $this->view('Analytics/index');
    }

    public function salary()
    {

        $isFullCRMAccess = $this->auth->user()->fullCRM();

        $modelList = new SalaryList($this->database);

        $userActive = $this->auth->user();
        $modelUserList = new UserList($this->database);

        $idUser = $this->request->input('id-user') ?? 2;

        $year = $this->request->input('year') ?? 2026;

        if(! $isFullCRMAccess){
            $salaryList = $modelList->salaryUserList([
                'id_user' => $userActive->id(),
                'date_start >=' => "$year-01-01",
                'date_end <=' => "$year-12-31"
            ]);
        }
        else{
            $salaryList = $modelList->salaryUserList([
                'id_user' => $idUser,
                'date_start >=' => "$year-01-01",
                'date_end <=' => "$year-12-31"
            ]);
        }

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Зарплата',
            'activeHeaderItem' => 2,
            'isFullCRMAccess' => $isFullCRMAccess,
            'salaryList' => $salaryList,
            'idUser' => $idUser,
            'managerList' => $modelUserList->minListManager(),
        ]);

        $this->view('Analytics/salary');
    }

    public function salaryStatistics()
    {
        $idSalary = $this->request->input('id') ?? 0;
        if(! $idSalary){
            $this->redirect->to('/analytics/salary');
        }

        $modelSalaryStat = new SalaryStatistics($this->database);

        $salaryStatisticsArray =  $modelSalaryStat->getSalaryStatistics($idSalary);


        $this->extract([
            'controller' => $this,
            'titlePage' => 'Подробная статистика по зарплате',
            'activeHeaderItem' => 2,
            'salaryStatisticsArray' => $salaryStatisticsArray,

        ]);

        $this->view('Analytics/salary-statistics');

    }

    public function ajaxAddPaymentsManager()
    {
        $newPaymentsManager = new PaymentsManager();
        $newPaymentsManager->edit([
            'idUserCreate' => $this->auth->user()->id(),
            'idManager' => $this->request->input('id-manager'),
            'type' => $this->request->input('type'),
            'quantity' => $this->request->input('quantity'),
            'idSalary' => $this->request->input('id-salary'),
            'dateCreate' => date('Y-m-d'),
        ]);

        $newPaymentsManager->save();

    }

    public function ajaxAddFineManager(){
        $newManagerFine = new FineManager();
        $newManagerFine->edit([
            'description' => $this->request->input('description'),
            'idManager' => $this->request->input('id-manager'),
            'quantity' => $this->request->input('quantity'),
            'idSalary' => $this->request->input('id-salary'),
            'dateCreate' => date('Y-m-d'),
        ]);
        $newManagerFine->save();
        print_r($_POST);
    }


    public function ajaxManagersGetExcel()
    {

        $actualUser = $this->auth->user();

        $userROP = in_array($actualUser->id(),[31,17]);

        if(!$actualUser->fullCRM() AND !$userROP){
            exit();
        }

        $userListModel = new ManagerList($this->database);

        $condition = [];
        $condition['cancelled'] = 0;

        $date = $this->request->input('date') ?? date('01.m.Y') .' - ' .date('d.m.Y');

        $dateArray = explode(' - ', $date);

        if(count($dateArray) == 1){
            $dateArray[1] = $dateArray[0];
        }

        $condition['dateField']['start'] = date('Y-m-d',strtotime($dateArray[0]));
        $condition['dateField']['end'] = date('Y-m-d',strtotime($dateArray[1] . ' +1 day'));;
        $condition['dateField']['name'] = 'date';


        $conditionUser = [];

        if($userROP){

            $conditionUser['id'] = $actualUser->getSubordinatesList();
            $conditionUser['id'][] = $actualUser->id();
        }

        $listData = $userListModel->ROPManagerList($condition,$conditionUser);

//        var_dump($userListModel->ROPManagerList($condition));

        $analyticsDocument = new AnalyticsDocuments($this->database);

        $listManager = $userListModel->managersIndividualData($condition,$conditionUser);


        $analyticsDocument->createManagersExcel($listData, $listManager, $userROP);

        print json_encode(['status' => true,'link_file' => '/doc/managers-report.xlsx']);


    }

    public function cronSendMailManagersExcel(bool $withSalary = true){
        

        $mailer = new Mailer(new Config());

        $actualUser = $this->auth->user()?? new User(['id' => 1]);

        $userROP = in_array($actualUser->id(),[31,17]);

        $userListModel = new ManagerList($this->database);

        $condition = [];
        $condition['cancelled'] = 0;

        $date = $this->request->input('date') ?? date('d.m.Y', strtotime('-1 days')) .' - ' .date('d.m.Y', strtotime('-1 days'));

        $dateArray = explode(' - ', $date);

        if(count($dateArray) == 1){
            $dateArray[1] = $dateArray[0];
        }

        $condition['dateField']['start'] = date('Y-m-d',strtotime($dateArray[0]));
        $condition['dateField']['end'] = date('Y-m-d',strtotime($dateArray[1] . ' +1 day'));
        $condition['dateField']['name'] = 'date';


        $conditionUser = [];

        if($userROP){

            $conditionUser['id'] = $actualUser->getSubordinatesList();
            $conditionUser['id'][] = $actualUser->id();
        }

        $listData = $userListModel->ROPManagerList($condition,$conditionUser);



        $analyticsDocument = new AnalyticsDocuments($this->database);

        $listManager = $userListModel->managersIndividualData($condition,$conditionUser);

        $nameFileOneDay = 'Отчет за 1 день - ' .date('d-m-Y',strtotime($dateArray[0]));
        $analyticsDocument->createManagersExcel($listData, $listManager, false,$nameFileOneDay .'.xlsx', $withSalary);


        $condition['dateField']['start'] = date('2025-12-01');
        $condition['dateField']['end'] = date('Y-m-d');

        $listData = $userListModel->ROPManagerList($condition,$conditionUser);
        $listManager = $userListModel->managersIndividualData($condition,$conditionUser);
        $nameFilePeriod = 'Отчет за период ' .date('d-m-Y',strtotime($condition['dateField']['start'])) .' - ' .date('d-m-Y',strtotime($condition['dateField']['end'] .'-1 days'));

        $analyticsDocument->createManagersExcel($listData, $listManager, false, $nameFilePeriod .'.xlsx', $withSalary);
        if($withSalary){
            $mailer->sendMail(['test' => 'Ежедневный ОТЧЕТ'],'yana-borisovna@pegas.best','/public/doc',$nameFileOneDay.'.xlsx','/public/doc',$nameFilePeriod.'.xlsx');
            $this->cronSendMailManagersExcel(false);
        }
        else{
            $mailer->sendMail(['test' => 'Ежедневный ОТЧЕТ'],'dasha.zhdanova.2002@mail.ru','/public/doc',$nameFileOneDay.'.xlsx','/public/doc',$nameFilePeriod.'.xlsx');
        }

        // $mailer->sendMail(['test' => 'test'],'yana-borisovna@pegas.best','/public/doc','managers-report.xlsx');
    }
    
    public function cronSendWeeklyReport(){
        $mailer = new Mailer(new Config());

        $date_start = date('Y-m-d', strtotime('monday this week'));

        $date_end = date('Y-m-d 23:59:59', strtotime('sunday this week'));


        $conditions = [
            'application_section_journal' => [1,2,3,6],
            'application_status_journal' => ['В пути', 'Выгрузился', 'В работе']
        ];


        $conditions['dateField'] = [
            'name' => 'date',
            'start' => $date_start,
            'end' => $date_end
        ];


        $report = new Report($this->database);

        $data = $report->getReportTypeFR($conditions);

        $condition['id'] = $data['listIdApplication'];
        $model = new Journal($this->database);

        $nameFilePL = 'Отчет P&L за ' .date('d-m-Y',strtotime($date_start)) .' - ' .date('d-m-Y',strtotime($date_end)).'.xlsx';

        $model->createExcelTablePLDDS($condition,[],$nameFilePL);

        $netProfitStatModel = new NetProfitStat($this->database);

        $paymentApplication = $this->database->superSelect('history_payment',[
            'side' => 0,
            'dateField' => [
                'name' => 'date',
                'start' => $date_start,
                'end' => $date_end
            ]
        ]);

        $listId = [];

        foreach($paymentApplication as $payment){
            $listId[] = $payment['id_application'];
        }

        $conditions = ['id' => $listId];

        $netProfitStat = $netProfitStatModel->getStatistics($conditions);

        $nameFileDDS = 'Отчет ДДС за ' .date('d-m-Y',strtotime($date_start)) .' - ' .date('d-m-Y',strtotime($date_end)).'.xlsx';

        $condition = ['id' => $netProfitStat['listId']];

        $model->createExcelTablePLDDS($condition,[],$nameFileDDS);

        $applicationList = $this->database->superSelect(
            'applications',
            [
                'dateField' => [
                    'name' => 'date',
                    'start' => $date_start,
                    'end' => $date_end
                ],
                'application_section_journal' => [1,2,3,6]
            ]
        );

        $result = [];

        foreach ($applicationList as $item) {
            $userId = $item['id_user'];
            $type = $item['taxation_type_Carrier'];
            $cost = $item['transportation_cost_Carrier'];

            if (!isset($result[$userId])) {
                $result[$userId] = [
                    'sum_with_nds' => 0,
                    'sum_without_nds' => 0,
                    'percent_with_nds' => 0,
                    'percent_without_nds' => 0,
                ];
            }

            if ($type === 'С НДС' || $type == 1) {
                $result[$userId]['sum_with_nds'] += $cost;
            } elseif ($type === 'Без НДС' || $type == 2) {
                $result[$userId]['sum_without_nds'] += $cost;
            }
        }

        // Добавляем проценты
        foreach ($result as $userId => &$r) {
            $total = $r['sum_with_nds'] + $r['sum_without_nds'];
            if ($total > 0) {
                $r['percent_with_nds'] = round($r['sum_with_nds'] / $total * 100, 2);
                $r['percent_without_nds'] = round($r['sum_without_nds'] / $total * 100, 2);
            }
        }

        $new_result = [];

        foreach ($result as $userId => $data) {
            // Получаем данные пользователя из базы
            $user = $this->database->first('users',['id' => $userId]);

            // Если пользователь найден
            if ($user) {
                $full_name = trim($user['name'] . ' ' . $user['surname']);
            } else {
                $full_name = "Неизвестный пользователь (ID: $userId)";
            }

            // Присваиваем новое имя в качестве ключа
            $new_result[$full_name] = $data;
        }

        $nameFileUserCarrierStat = 'Отчет по перевозчикам за ' .date('d-m-Y',strtotime($date_start)) .' - ' .date('d-m-Y',strtotime($date_end)).'.xlsx';
        $model->createExcelTableManagerCarrierStat($new_result, $nameFileUserCarrierStat);

        // dd($new_result);

        $mailer->subject_name = 'Еженедельный ОТЧЕТ ' .date('d.m.Y',strtotime($date_start)) .' - ' .date('d.m.Y',strtotime($date_end));


            // $mailer->sendMail(['test' => 'Ежедневный ОТЧЕТ'],'yana-borisovna@pegas.best','/public/doc',$nameFileOneDay.'.xlsx','/public/doc',$nameFilePeriod.'.xlsx');
        dd($mailer->sendMail(
            ['test' => 'Еженедельный ОТЧЕТ'],
            'yana-borisovna@pegas.best',
            '/public/doc',
            $nameFilePL,
            '/public/doc',
            $nameFileDDS,
            '/public/doc',
            $nameFileUserCarrierStat
        ));

        dd($condition);

        dd([$date_start,$date_end]);
    }
    public function managers()
    {
        // $this->cronSendMailManagersExcel();

        $actualUser = $this->auth->user();

        $userROP = in_array($actualUser->id(),[2,31,17]);



        if(!$actualUser->fullCRM() AND !$userROP){
            $this->redirect->to('/');
        }

        $userListModel = new ManagerList($this->database);

        $condition = [];
        $condition['cancelled'] = 0;

        $date = $this->request->input('date') ?? date('01.m.Y') .' - ' .date('d.m.Y');

        $dateArray = explode(' - ', $date);

        if(count($dateArray) == 1){
            $dateArray[1] = $dateArray[0];
        }

        $condition['dateField']['start'] = date('Y-m-d',strtotime($dateArray[0]));
        $condition['dateField']['end'] = date('Y-m-d',strtotime($dateArray[1] . ' +1 day'));;
        $condition['dateField']['name'] = 'date';

        $conditionUser = [];

        if($userROP){

            $conditionUser['id'] = $actualUser->getSubordinatesList();
            $conditionUser['id'][] = $actualUser->id();
        }

        if($this->request->input('customer_id_Client')){
            $condition['customer_id_Client'] = $this->request->input('customer_id_Client');
        }

//        dd($condition);

        $listData = $userListModel->ManagerList($condition,$conditionUser);
        $sumSalary = $listData['sumSalary'];
        $sumWalrus = $listData['sumWalrus'];

        $countApplication = $listData['countApplication'];

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Аналитика менеджеры',
            'managerList' => $listData['list'],
            'date' => $date,
            'sumSalary' => $sumSalary,
            'sumWalrus' => $sumWalrus,
            'countApplication' => $countApplication,
            'activeHeaderItem' => 2
        ]);

        $this->view('Analytics/managers');
    }
    public function applications()
    {
        $applicationListModel = new ApplicationList($this->database);

        $activeUser = $this->auth->user();
        $isFullCRMAccess = $activeUser->fullCRM();

        $userListModel = new UserList($this->database);

        $link = '';

        $order = [];

        if($this->request->input('order'))
            $order = ['id' => $this->request->input('order')];
        else
            $order = ['id' => 'DESC'];

        $condition = [];
        $conditionFilter = ['manager' => '', 'client-taxation' => '', 'carrier-taxation' => '', 'client' => '', 'carrier' => ''];

        if($this->request->input('manager') AND $this->request->input('manager') != 'null'){
            $link .= '&manager=' .$this->request->input('manager');
            $condition['id_user'] = $this->request->input('manager');
            $conditionFilter['manager'] = $this->request->input('manager');
        }


        if($this->request->input('date') !== null AND $this->request->input('date') !== '') {
            $period = $this->request->input('date');

            $date = explode(' - ', $period);

            if(count($date) == 1){
                $date[1] = $date[0];
            }

            $condition['dateField']  = [
                'name' => 'date',
                'start' => date('Y-m-d', strtotime($date[0])),
                'end' => date('Y-m-d 23:59:59', strtotime($date[1])),
            ];

            $link .= '&date=' .$this->request->input('date');

            $conditionFilter['date'] = $this->request->input('date');

        }


        if($this->request->input('customer') AND $this->request->input('customer') != 'null'){
            $link .= '&customer=' .$this->request->input('customer');
            $condition['customer_id_Client'] = $this->request->input('customer');
            $conditionFilter['customer'] = $this->request->input('customer');
        }

        if($this->request->input('client-taxation') AND $this->request->input('client-taxation') != 'null'){
            $link .= '&client-taxation=' .$this->request->input('client-taxation');
            $condition['taxation_type_Client'] = $this->request->input('client-taxation');
            $conditionFilter['client-taxation'] = $this->request->input('client-taxation');
        }
        if($this->request->input('carrier-taxation') AND $this->request->input('carrier-taxation') != 'null'){
            $link .= '&carrier-taxation=' .$this->request->input('carrier-taxation');
            $condition['taxation_type_Carrier'] = $this->request->input('carrier-taxation');
            $conditionFilter['carrier-taxation'] = $this->request->input('carrier-taxation');
        }

        if($this->request->input('carrier') AND $this->request->input('carrier') != 'null'){
            $link .= '&carrier=' .$this->request->input('carrier');
            $condition['carrier_id_Carrier'] = $this->request->input('carrier');
            $conditionFilter['carrier'] = $this->request->input('carrier');
        }

        if($this->request->input('client') AND $this->request->input('client') != 'null'){
            $link .= '&client=' .$this->request->input('client');
            $condition['client_id_Client'] = $this->request->input('client');
            $conditionFilter['client'] = $this->request->input('client');
        }

        if(! $isFullCRMAccess)
            $condition['id_user'] = $activeUser->id();

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = 25;

        $carrierListModel = new CarrierList($this->database);
        $clientListModel = new ClientList($this->database);

        $listClients = $clientListModel->simpleListClients();

        if(! $isFullCRMAccess)
            $listClients = $clientListModel->simpleListClients($activeUser->id());

        $elementsPage = $this->session->get('countElementsPage') ?? 25;

        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Аналитика заявки',
            'applicationList' => $applicationListModel->listApplication($page, $condition, $order,$elementsPage),
            'countPage' => $applicationListModel->countPage($elementsPage, $condition),
            'userList' => $userListModel->listUsers(),
            'condition' => $conditionFilter,
            'page' => $page,
            'link' => $link,
            'isFullCRMAccess' => $isFullCRMAccess,
            'listClients' => $listClients,
            'listCarriers' => $carrierListModel->simpleListCarriers(),
            'activeHeaderItem' => 2,
            'elementsPage'=> $elementsPage
        ]);


        $this->view('Analytics/applications');
    }
}