<?php

namespace App\User\Contoller\Common;

use App\Config\Config;
use App\Model\Addition\Addition;
use App\Model\AdditionalExpenses\AdditionalExpenses;
use App\Model\AdditionalProfit\AdditionalProfit;
use App\Model\Application\Application;
use App\Model\CarBrands\CarBrands;
use App\Model\Carrier\Carrier;
use App\Model\Client\Client;
use App\Model\ClientInfo\ClientInfo;
use App\Model\Condition\Condition;
use App\Model\Customer\Customer;
use App\Model\Driver\Driver;
use App\Model\Driver\DriverCar;
use App\Model\File\File;
use App\Model\Fines\Fines;
use App\Model\Marshrut\Marshrut;
use App\Model\Notification\Notification;
use App\Model\Search\SearchApplication;
use App\Model\Task\Task;
use App\Model\TermsPayment\TermsPayment;
use App\Model\TypeCarcase\TypeCarcase;
use App\Model\TypeTaxation\TypeTaxation;
use App\Model\TypeTransport\TypeTransport;
use App\User\Contoller\Controller;
use App\User\Model\Application\ApplicationChangeHistory;
use App\User\Model\Application\ApplicationChangeStatus;
use App\User\Model\Application\ApplicationList;
use App\User\Model\Application\ApplicationNotification;
use App\User\Model\Application\ApplicationPage;
use App\User\Model\Application\Comment;
use App\User\Model\Application\Plan;
use App\User\Model\Carrier\CarrierList;
use App\User\Model\Client\ClientList;
use App\User\Model\Driver\DriverList;
use App\User\Model\User\ManagerPlan;
use App\User\Model\User\UserList;
use App\Model\User\User;




class ApplicationController extends Controller
{


    public function ajaxChangeDateReceiptAllDocuments()
    {
        $idApplication = $this->request->input('id');

        $date = $this->request->input('date');

        echo $this->database->update(
            'applications',
            ['date_receipt_all_documents_Carrier' => date('Y-m-d', strtotime($date))],
            ['id' => $idApplication]);


//        $changeHistory = new ApplicationChangeHistory($applicationId, $application, $userId);
//        $changeHistory->saveChangeHistory();
    }

    public function insertPlanManagers()
    {

    }

    public function plan()
    {
        $modelManagerPlan = new ManagerPlan($this->database);

        $modelManagerPlan->checkPlanExecutionManager();
    }

    public function checkProfitPeriod()
    {

        $applications = $this->database->superSelect('applications',
            ['id_user' => 3],
            [],
            -1,
            ['id'],
        );
        foreach ($applications as $application) {
            $tempApplication = new Application($application);

            $tempApplication->save();
        }
        dd($applications);
    }

    public function changeStatusOldStatus()
    {
        echo  'text';
        for ($i = 2; $i <= 1654; $i++){
            $modelApplicationChangeStatus = new ApplicationChangeStatus($this->database);
            if($modelApplicationChangeStatus->checkPossibleChangeStatus($i))
                echo $i . '<br>';
            else
                echo $i . ' - nope <br>';
        }
    }
    public function checkPossibleChangeStatus()
    {
        $modelApplicationChangeStatus = new ApplicationChangeStatus($this->database);

        dd($modelApplicationChangeStatus->checkPossibleChangeStatus(1562));

    }
    public function checkWalrusNew()
    {
        $id = $this->request->input('id');

        $application = new Application(['id' => $id]);

        dd($application->getWalrus());
    }
    public function UpdateAllWalrus()
    {
        $listIdApplications = $this->database->superSelect('applications', ['dateField' => [
            'name' => 'date', 'start' => '2025-01-20', 'end' => '2025-05-20'
        ]], ['id' => 'DESC'], -1 ,['id']);

        foreach ($listIdApplications as $applicationId) {
            $tempApplication = new Application(['id' => $applicationId['id']]);

            $tempApplication->save();
        }
    }

    public function setAdditionalExpensesInsurance()
    {

    }

    public function checkWalrus()
    {
        $id = $this->request->input('id');

        $application = new Application(['id' => $id]);


    }
    public function ajaxCopyTest()
    {
        $applicationId = $this->request->input("id");

        $application = new Application(["id"=> $applicationId]);

        print $application->copy();
    }

    public function ajaxChangeTTNStatus()
    {
        $idApp = $this->request->input('idApp');
        $type = $this->request->input('type');
        $status = (int)$this->request->input('status');
        $this->database->update('applications', ['ttn_' . $type => $status],['id' => $idApp]);
    }

    public function ajaxGetTermsPaymentList()
    {
        $type = $this->request->input("type");
        $typeFor = $this->request->input("typeFor");

        $listTermsPayment = $this->database->select(
            'terms_payment',
            ['type_for' => $typeFor, 'type_taxation' => $type],
            [],
            -1,
            ['id','name']
        );

        print json_encode($listTermsPayment);

    }

    public function updateQuantityApplicationsClient()
    {
        $clientDB = $this->database->select('clients',['quantity_applications' => 0]);

        foreach ($clientDB as $client) {
            $quantity = $this->database->first('applications', ['client_id_Client' => $client['id'],'cancelled' => 0],['COUNT(*)'])['COUNT(*)'] ?? 0;

            if($client['quantity_applications'] == 0 AND $quantity > 0){
                $notification = new Notification();
                $notification->edit([
                    'id_application' => $client['id'],
                    'name' => 'Первая заявка ',
                    'date' => date('Y-m-d H:i:s'),
                    'text' => 'Первая заявка у клиента ' .$client['name'],
                    'id_from_user' => 20,
                    'id_to_user' => 20,
                    'application_number' =>  0,
                    'for_client' => 1
                ]);

                $notification->save();
            }

            $this->database->update('clients', ['quantity_applications' => $quantity], ['id' => $client['id']]);

        }
    }

    public function index(){
        $application = new ApplicationPage($this->request->input('id'));
        $canChangeApplication = true;
        if(! $application->id)
            $this->redirect->to('/applications-list');

        $activeUser = $this->auth->user();
        $isFullCRMAccess = $activeUser->fullCRM();

        $canDeleteFiles = true;

        if(! $isFullCRMAccess){
            $subordinatesList = $activeUser->getSubordinatesList();
            if($activeUser->id() !== $application->idUser AND $this->auth->user()->id() != 55) {
                $access = false;

                foreach ($subordinatesList as $subordinate) {
                    if($subordinate == $application->idUser) {
                        $access = true;
                    }
                }

                if(! $access)
                    $this->redirect->to('/applications-list');
            }

            $canDeleteFiles = in_array($application->applicationSectionJournal,[1,2]);

        }

        $documents = $this->database->select("files", ["application_id" => $application->id]);

        $listDatabaseTasks = $this->database->select('tasks',['application_id' => $application->id]);
        $listTasks = [];

        $userListModel = new UserList($this->database);

        $listManager = $userListModel->listManager();

        foreach($listDatabaseTasks as $task){
            $temp = new Task(['id'=> $task['id']]);
            $listTasks[] = $temp->get();
        }

        $commentsDB = $this->database->select(
            'comments_application',
            ['id_application' => $application->id],
            ['important' => 'DESC'],
            -1 ,
            ['id']
        ) ?? [];


        foreach($commentsDB as $comment){
            $temp = new Comment(['id'=> $comment['id']]);
            $data = $temp->get();
            $data['user_data'] = $temp->getUserData();
        }
        $activityFeed = $this->database->select("changes", ["application_id" => $application->id], ["id" => "DESC"]);

        $customersIdToName = [];
        $carriersIdToName = [];
        $clientsIdToName = [];
        $driversIdToName = [];

        for($i = 0; $i < count($activityFeed); $i++){
            $feed = $activityFeed[$i];
            foreach (json_decode($feed["changes"], true) ?? [] as $changesItem){
                if ($changesItem["key"] === "customer_id_Client" || $changesItem["key"] === "customer_id_Carrier"){
                    $customersIdToName[$changesItem["oldValue"]] =
                        $this->database->select("customers", ["id" => $changesItem["oldValue"]])[0]["name"];
                    $customersIdToName[$changesItem["newValue"]] =
                        $this->database->select("customers", ["id" => $changesItem["newValue"]])[0]["name"];
                }
                elseif ($changesItem["key"] === "carrier_id_Carrier"){
                    $carriersIdToName[$changesItem["oldValue"]] =
                        $this->database->select("carriers", ["id" => $changesItem["oldValue"]])[0]["name"];
                    $carriersIdToName[$changesItem["newValue"]] =
                        $this->database->select("carriers", ["id" => $changesItem["newValue"]])[0]["name"];
                }
                elseif ($changesItem["key"] === "client_id_Client"){
                    $clientsIdToName[$changesItem["oldValue"]] =
                        $this->database->select("clients", ["id" => $changesItem["oldValue"]])[0]["name"];
                    $clientsIdToName[$changesItem["newValue"]] =
                        $this->database->select("clients", ["id" => $changesItem["newValue"]])[0]["name"];
                }
                elseif ($changesItem["key"] === "driver_id_Carrier" || $changesItem["key"] === "driver_id_Client"){
                    $driversIdToName[$changesItem["oldValue"]] =
                        $this->database->select("drivers", ["id" => $changesItem["oldValue"]])[0]["name"];
                    $driversIdToName[$changesItem["newValue"]] =
                        $this->database->select("drivers", ["id" => $changesItem["newValue"]])[0]["name"];
                }
            }
            $tempUser = new User(['id' => $feed['user_id']]);
            $activityFeed[$i]['user_name'] = $tempUser->FIO();
            $activityFeed[$i]['user_role'] = $tempUser->getRole();
            $activityFeed[$i]['user_avatar'] = $tempUser->avatar();

        }
        $config = new Config();


        $this->extract([
            'controller' => $this,
            'titlePage' => $application->numApp,
            'application' => $application,
            'documents' => $documents,
            'listTasks' => $listTasks,
            'listManager' => $listManager,
            'canDeleteFiles' => $canDeleteFiles,
            'comments' => [],
            'activityFeed' => $activityFeed,
            'config' => $config,
            "customersIdToName" =>$customersIdToName,
            "carriersIdToName" => $carriersIdToName,
            "clientsIdToName" => $clientsIdToName,
            "driversIdToName" => $driversIdToName,
            'canChangeApplication' => $canChangeApplication
        ]);

        $this->view("Application/application");
    }

    public function applicationsListPage()
    {

        $this->updateQuantityApplicationsClient();

        $activeUser = $this->auth->user();

        $isFullCRMAccess = $activeUser->fullCRM();

        $applicationListModel = new ApplicationList($this->database);

        $userListModel = new UserList($this->database);

        $link = '?';


        if($this->request->input('order'))
            $order = ['id' => $this->request->input('order')];
        else
            $order = ['id' => 'DESC'];

        $condition = [];
        $conditionFilter = ['manager' => [], 'client-taxation' => '', 'carrier-taxation' => '',
            'client' => '', 'carrier' => '', 'date' => '', 'cancelled' => false];

        $condition['cancelled'] = 0;

        if($this->request->input('cancelled') == 1){
            $condition['cancelled'] = 1;
            $conditionFilter['cancelled'] = true;
            $link = 'cancelled=1';
        }

        if(is_array($this->request->input('manager'))){
            foreach ($this->request->input('manager') as $item){
                $link .= '&manager%5B%5D=' .$item;
                $condition['id_user'][] = $item;
                $conditionFilter['manager'][] = $item;
            }

        }
        $rop = false;
        if(! $isFullCRMAccess) {
            $userLogist = new User(['id' => $activeUser->id()]);

            if(count($userLogist->getSubordinatesList()) > 0 OR $this->request->input('rop') == 1 AND $userLogist->id() != 7){
                $condition['id_user'] = [];
                $link .= '&rop=1';
                if($this->request->input('rop') == 1)
                    $rop = true;

//                $condition['id_user'][] = $activeUser->id();

                foreach ($userLogist->getSubordinatesList() as $subordinate){
                    $condition['id_user'][] = $subordinate;
                }

            }
            else{
                $condition['id_user'] = $activeUser->id();

            }
        }

        $conditionFilter['overdue'] = false;

        if($this->request->input('overdue') == 1){
            $condition['dateField'] = [
                'name' => 'date',
                'start' => '2025-01-01 00:00:00',
                'end' => date('Y-m-d', strtotime('-40 day'))
            ];
            $conditionFilter['overdue'] = true;
            $condition['application_section_journal'] = [1,2];
        }

        $listSearch = [];
        $searchData = ['search-select' => '', 'search' => ''];


        if($this->request->input('search') AND $this->request->input('search-select')){

            $str = $this->request->input('search');

//            dd(preg_match('/-п/iu', $str));

            if (preg_match('/-п/iu', $str)) {
                $this->redirect->to('/prr/list?search=' . urlencode($str));
            }

            $searchText = $this->request->input('search');
            $searchSelect = $this->request->input('search-select');
            $searchModel = new SearchApplication($searchSelect);

            $link .= '?search-select=' . $searchSelect . '&search=' . $searchText;

            $listSearch = $searchModel->search($searchText);
//            dd($listSearch);

            $searchData['search-select'] = $searchSelect;
            $searchData['search'] = $searchText;
        }
        if(count($listSearch) > 0){
            $condition['id'] = $listSearch;
            $condition['cancelled'] = [0,1];
        }

        if($this->request->input('date') != ''){
            $date = explode(' - ', $this->request->input('date'));
            $condition['dateField']['start'] = date('Y-m-d',strtotime($date[0]));

            if(count($date) > 1){
                $condition['dateField']['end'] = date('Y-m-d',strtotime($date[1] . ' +1 day'));
            }
            else{
                $condition['dateField']['end'] = date('Y-m-d',
                    strtotime($date[0] . ' +1 day'));
            }

            $condition['dateField']['name'] = 'date';

            $conditionFilter['date'] = $this->request->input('date');
            $link .= 'date=' .$this->request->input('date');
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

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = $this->session->get('countElementsPage') ?? 25;

        $carrierListModel = new CarrierList($this->database);
        $clientListModel = new ClientList($this->database);

        $listClients = $clientListModel->simpleListClients();

        $planExecution = false;
        $plan = false;

        if(! $isFullCRMAccess){
            $model = new ManagerPlan($this->database);
            $model->checkPlanExecutionManager($activeUser->id());


            $planModel = new Plan($this->database, $activeUser->id());

            $planExecution = $planModel->getInfoFromDB();
            if($planExecution['id_plan'] > 2){
                $planExecution = false;
            }

            else{
                $plan = $this->database->first('types_plan',['id' => $planExecution ['id_plan']]);
            }


            $listClients = $clientListModel->simpleListClients($activeUser->id());
        }

        $overdueCondition = $condition;

        $overdueCondition['dateField'] = [
            'name' => 'date',
            'start' => '2025-01-01 00:00:00',
            'end' => date('Y-m-d', strtotime('-40 day'))
        ];
        $overdueCondition['application_section_journal'] = [1,2];
        $countOverdue = $this->database->superSelect('applications',$overdueCondition,[],1,['COUNT(*)'])[0]['COUNT(*)'] ?? 0;

        // dd($condition);

        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Заявки',
            'applicationList' => $applicationListModel->listApplication($page, $condition, $order,$elementsPage),
            'userList' => $userListModel->listManager(),
            'carrierList' => $carrierListModel->simpleListCarriers(),
            'planExecution' => $planExecution,
            'plan' => $plan,
            'clientList' => $listClients,
            'condition' => $conditionFilter,
            'countPage' => $applicationListModel->countPage($elementsPage, $condition),
            'searchData' => $searchData,
            'elementsPage' => $elementsPage,
            'page' => $page,
            'link' => $link,
            'isFullCRMAccess' => $isFullCRMAccess,
            'activeHeaderItem' => 1,
            'countOverdue' => $countOverdue,
            'rop' => $rop
        ]);
        $this->view('Application/applications-list');
    }

    public function ajaxChangePrepaymentCarrier()
    {
        $id = $this->request->input('id');
        $status = $this->request->input('status');

        if($status)
            $status = 1;
        else
            $status = 0;

        var_dump($this->database->update(
            'applications',
            ['prepayment_Carrier' => $status],
            ['id' => $id])
        );
    }


    public function ajaxChangeManager()
    {
        $idApplication = $this->request->input('id_application');
        $idUser = $this->request->input('id_user');

        if($this->database->update('applications',['id_user' => $idUser],['id' => $idApplication])){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }

    public function ajaxAddComment()
    {
        $applicationId = $this->request->input("id_application");
        $commentText = $this->request->input("comment");
        $typeComment = $this->request->input("type_comment");
        $userId = $this->auth->user()->id();

        $newComment = new Comment();

        $newComment->edit([
            'id_application' => $applicationId,
            'comment' => $commentText,
            'id_user' => $userId,
            'type_comment' => $typeComment
        ]);


        if($newComment->save()){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }

    }

    public function ajaxEditComment()
    {
        $commentText = $this->request->input("comment");
        $commentId = $this->request->input("comment_id");

        $editComment = new Comment(['id' => $commentId]);

        $editComment->edit(['comment' => $commentText]);

        if($editComment->save()){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }

    }

    public function ajaxLoadComment()
    {
        $applicationId = $this->request->input("id_application");

        $user = $this->auth->user();
        $userId = $user->id();



        $commentsDB = $this->database->select(
            'comments_application',
            ['id_application' => $applicationId,'visible' => 1],
            ['important' => 'DESC'],
            -1 ,
            ['id']
        ) ?? [];


        $listComment = [];

        foreach($commentsDB as $comment){
            $temp = new Comment(['id'=> $comment['id']]);
            $data = $temp->get();
            $data['user_data'] = $temp->getUserData();
            $data['date_time'] = date('d.m.Y H:i', strtotime($data['date_time']));
            if($user->fullCRM() and in_array($user->get()['role'], [7,6,3]))
                $data['edit_access'] = true;
            else{
                if($data['id_user'] == $userId)
                    $data['edit_access'] = true;
                else
                    $data['edit_access'] = false;
            }
            $listComment[] = $data;
        }

        print json_encode($listComment);

    }

    public function ajaxChangeImportantComment()
    {
        $comment = new Comment(['id' => $this->request->input("id")]);

        if($comment->get()['important']){
            $comment->edit(['important' => 0]);
        }
        else{
            $comment->edit(['important' => 1]);
        }

        if($comment->save()){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }

    public function ajaxDeleteComment()
    {
        $comment = new Comment(['id' => $this->request->input("id")]);

        $comment->edit(['visible' => 0]);

        if($comment->save()){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }

    public function add(){
        $currentUser = $this->auth->user();
        $currentUserData = $currentUser->get();

        $isUserAdmin = $currentUserData["role"] === 6 || $currentUserData["role"] === 7;

        $listDatabaseTermsPayment = $this->database->select('terms_payment');
        $listTermsPayment = [];

        foreach($listDatabaseTermsPayment as $term){
            $temp = new TermsPayment(['id'=> $term['id']]);
            $listTermsPayment[] = $temp->get();
        }

        $listDatabaseConditions = $this->database->select('conditions');
        $listConditions = [];

        foreach($listDatabaseConditions as $condition){
            $temp = new Condition(['id'=> $condition['id']]);
            $listConditions[] = $temp->get();
        }

        $listDatabaseCarBrands = $this->database->select('car_brands');
        $listCarBrands = [];

        foreach($listDatabaseCarBrands as $brand){
            $temp = new CarBrands(['id' => $brand['id']]);
            $listCarBrands[] = $temp->get();
        }

        $listDatabaseTypeTransport = $this->database->select('type_transport');
        $listTypeTransport = [];
        foreach($listDatabaseTypeTransport as $typeTransport){
            $temp = new TypeTransport(['id'=> $typeTransport['id']]);
            $listTypeTransport[] = $temp->get();
        }

        //сustomers
        $listDatabaseCustomers = $this->database->select('customers');
        $listCustomers = [];
        foreach($listDatabaseCustomers as $customer){
            $temp = new Customer(['id'=> $customer['id']]);
            $listCustomers[] = $temp->get();
        }

        //carriers
        $carriersList = new  CarrierList($this->database);
        $listCarriers = $carriersList->simpleListCarriers();

        //clients
        $clientsList = new  ClientList($this->database);
        if ($isUserAdmin){
            $listClients = $clientsList->simpleListClients();
        }
        else{
            $listClients = $clientsList->simpleListClients($currentUserData["id"]);
        }

        //drivers
        $driversList = new DriverList($this->database);
        $listDrivers = $driversList->simpleListDrivers();

        //drivers
        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }

        //carcase types
        $listDatabaseCarcaseTypes = $this->database->select('type_carcase');
        $listCarcaseTypes = [];
        foreach($listDatabaseCarcaseTypes as $typeCarcase){
            $temp = new TypeCarcase(['id'=> $typeCarcase['id']]);
            $listCarcaseTypes[] = $temp->get();
        }

        //Additions
        if ($isUserAdmin){
            $listDatabaseAdditions = $this->database->select('addition',[],['id' => 'DESC']);
        }
        else{
            $listDatabaseAdditions = $this->database->select('addition', ['users_access' => "%|".$currentUserData['id']."|%"], ['id' => 'DESC'],-1,  ['*'], 0,'AND', $comparisonOperator = 'LIKE');
        }
        $listAdditions = [];
        foreach($listDatabaseAdditions as $addition){
            $temp = new Addition(['id'=> $addition['id']]);
            $listAdditions[] = $temp->get();
        }

        $listPRRCompany = $this->database->select('prr_company');

        $this->extract([
            'controller' => $this,
            'titlePage' => "Добавление заявки",
            'listTermsPayment' => $listTermsPayment,
            'listConditions' => $listConditions,
            'listCarBrands' => $listCarBrands,
            'listTypeTransport' => $listTypeTransport,
            'listCustomers' => $listCustomers,
            'listCarriers' => $listCarriers,
            'listClients' => $listClients,
            'listTypesTaxation' => $listTypesTaxation,
            'listDrivers' => $listDrivers,
            'listCarcaseTypes' =>  $listCarcaseTypes,
            "listAdditions" => $listAdditions,
            'listPRRCompany' => $listPRRCompany,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
        ]);

        $this->view("Application/add");
    }

    public function edit(){
        $applicationId = $this->request->input('id');

        $application = new Application(['id'=> $applicationId]);


        $canChangeApplication = true;


        $currentUser = $this->auth->user();
        $currentUserData = $currentUser->get();

        $isUserAdmin = $this->auth->user()->fullCRM();

        $listDatabaseTermsPayment = $this->database->select('terms_payment');

        $listTermsPayment = [];

        foreach($listDatabaseTermsPayment as $term){
            $temp = new TermsPayment(['id'=> $term['id']]);
            $listTermsPayment[] = $temp->get();
        }

        $listDatabaseConditions = $this->database->select('conditions');
        $listConditions = [];

        foreach($listDatabaseConditions as $condition){
            $temp = new Condition(['id'=> $condition['id']]);
            $listConditions[] = $temp->get();
        }

        $listDatabaseCarBrands = $this->database->select('car_brands');
        $listCarBrands = [];

        foreach($listDatabaseCarBrands as $brand){
            $temp = new CarBrands(['id' => $brand['id']]);
            $listCarBrands[] = $temp->get();
        }

        $listDatabaseTypeTransport = $this->database->select('type_transport');
        $listTypeTransport = [];
        foreach($listDatabaseTypeTransport as $typeTransport){
            $temp = new TypeTransport(['id'=> $typeTransport['id']]);
            $listTypeTransport[] = $temp->get();
        }

        //сustomers
        $listDatabaseCustomers = $this->database->select('customers');
        $listCustomers = [];
        foreach($listDatabaseCustomers as $customer){
            $temp = new Customer(['id'=> $customer['id']]);
            $listCustomers[] = $temp->get();
        }

        //carriers
        $carriersList = new  CarrierList($this->database);
        $listCarriers = $carriersList->simpleListCarriers();

        //clients
        $clientsList = new  ClientList($this->database);
        if ($isUserAdmin){
            $listClients = $clientsList->simpleListClients();
        }
        else{
            $listClients = $clientsList->simpleListClients($currentUserData["id"]);
        }

        //drivers
        $driversList = new DriverList($this->database);
        $listDrivers = $driversList->simpleListDrivers();

        //drivers
        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }
//        dd($listTypesTaxation);

        //carcase types
        $listDatabaseCarcaseTypes = $this->database->select('type_carcase');
        $listCarcaseTypes = [];
        foreach($listDatabaseCarcaseTypes as $typeCarcase){
            $temp = new TypeCarcase(['id'=> $typeCarcase['id']]);
            $listCarcaseTypes[] = $temp->get();
        }

        //Additions
        if ($isUserAdmin){
            $listDatabaseAdditions = $this->database->select('addition');
        }
        else{

            if($currentUser->id() != 2)
                $canChangeApplication = in_array($application->get()['application_section_journal'], [1,2,4,5]);



            $listDatabaseAdditions = $this->database->select(
                'addition',
                ['users_access' => "%|".$currentUserData['id']."|%"],
                [],
                -1,
                ['*'],
                0,
                'AND',
                $comparisonOperator = 'LIKE');
        }
        $listAdditions = [];
        foreach($listDatabaseAdditions as $addition){
            $temp = new Addition(['id'=> $addition['id']]);
            $listAdditions[] = $temp->get();
        }

        //Routes
        $listDatabaseRoutes = $this->database->select('routes',["id_application" => $applicationId]);
        $listRoutes = [];
        foreach($listDatabaseRoutes as $route){
            $temp = new Marshrut(['id'=> $route['id']]);
            $listRoutes[] = $temp->get();
        }

        //Fines
        $listDatabaseFines = $this->database->select('fines',["id_application" => $applicationId]);
        $listFines = [];
        foreach($listDatabaseFines as $fine){
            $temp = new Fines(['id'=> $fine['id']]);
            $listFines[] = $temp->get();
        }

        //Additional Expenses
        $listDatabaseAdditionalExpenses = $this->database->select('additional_expenses',["id_application" => $applicationId]);
        $additionalExpenses = [];
        foreach($listDatabaseAdditionalExpenses as $additionalExpense){
            $temp = new AdditionalExpenses(['id'=> $additionalExpense['id']]);
            $additionalExpenses[] = $temp->get();
        }

        $listDatabaseAdditionalProfit = $this->database->select(
            'additional_profit',
            ["id_application" => $applicationId]
        );
        $additionalProfit = [];
        foreach($listDatabaseAdditionalProfit as $additional_profit){
            $temp = new AdditionalProfit(['id'=> $additional_profit['id']]);
            $additionalProfit[] = $temp->get();
        }


        $this->extract([
            'controller' => $this,
            "application" => $application->get(),
            'titlePage' => "Редактирование заявки",
            'listTermsPayment' => $listTermsPayment,
            'listConditions' => $listConditions,
            'listCarBrands' => $listCarBrands,
            'listTypeTransport' => $listTypeTransport,
            'listCustomers' => $listCustomers,
            'listCarriers' => $listCarriers,
            'listClients' => $listClients,
            'listTypesTaxation' => $listTypesTaxation,
            'listDrivers' => $listDrivers,
            'listCarcaseTypes' =>  $listCarcaseTypes,
            "listAdditions" => $listAdditions,
            "listRoutes" => $listRoutes,
            "listFines" => $listFines,
            "additionalExpenses" => $additionalExpenses,
            "additionalProfit" => $additionalProfit,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
            'canChangeApplication' => $canChangeApplication
        ]);
        $this->view("Application/edit");
    }

    public function ajaxChangeCommentDocument()
    {
        $idUser = $this->auth->user()->id();
        $idApp = $this->request->input('idApp');
        $numDoc = $this->request->input('numDoc');
        $name = $this->request->input('name');
        $side = $this->request->input('side');
        $comment = $this->request->input('comment');


        $commentDocument = $this->database->select(
            'application_document_comment',
            [
                'id_application' => $idApp,
                'name' => $name,
                'side' => $side,
                'num_document' => $numDoc,
            ]
        );

        if(!$commentDocument){
            $this->database->insert(
                'application_document_comment',
                [
                    'name' => $this->request->input('name'),
                    'comment' => $this->request->input('comment'),
                    'id_application' => $this->request->input('idApp'),
                    'num_document' => $this->request->input('numDoc'),
                    'side' => $this->request->input('side'),
                    'datetime' => date("Y-m-d H:i:s"),
                    'id_user' => $this->auth->user()->id()
                ]
            );
        }
        else{
            $this->database->update(
                'application_document_comment',
                ['comment' => $comment, 'datetime' => date("Y-m-d H:i:s"),
                    'id_user' => $this->auth->user()->id()],
                ['id' => $commentDocument[0]['id']]
            );
            unset($commentDocument[0]['id']);

            print $this->database->insert('application_document_comment_history', $commentDocument[0]);
        }


        $modelChangeApplicationStatus = new ApplicationChangeStatus($this->database);

        $modelChangeApplicationStatus->checkPossibleChangeStatus($idApp);

        $modelNotificationApplication = new ApplicationNotification($this->database);

        $modelNotificationApplication->needSendNotification($idApp);

    }
    public function ajaxChangeStatusApplication()
    {
        $applicationId = $this->request->input('id');

        $application = new Application(['id' => $applicationId]);

        $oldValue = $application->get()["application_status"];

        $statusName = $this->request->input('name');
        $statusValue = $this->request->input('value');

        if($statusName == 'client_payment_status' AND $statusValue == 'Оплачено полностью' OR
            $statusName == 'carrier_payment_status' AND $statusValue == 'Оплачено полностью'){

            $this->database->insert(
                'manager_journal_event',
                [
                    'application_id' => $applicationId,
                    'event' => $statusName
                ]
            );
        }

        $this->database->insert("changes", [
            "changes" => json_encode([[
                "key" => $statusName,
                "oldValue" => $oldValue,
                "newValue" => $statusValue
            ]], JSON_UNESCAPED_UNICODE),
            "application_id" => $applicationId,
            "datetime" =>  date("Y-m-d H:i:s")
        ]);

        $application->edit([$statusName => $statusValue]);

        if($application->save()){
            echo 1;
        }
        else{
            echo 0;
        }

    }
    public function ajaxChangeCancel()
    {
        $idApplication = $this->request->input('id_application');
        $cancelled = ! $this->request->input('cancelled');
        $application_section_journal = 4;

        if(! $cancelled)
            $application_section_journal = 1;


        if($this->database->update('applications',['cancelled' => (int)$cancelled, 'application_section_journal' => $application_section_journal],['id' => $idApplication])){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false,'cancelled' => $cancelled]);
        }

    }
    public function ajaxCancelApplication()
    {
        $idUser = $this->auth->user()->id();
        $idApplication = $this->request->input('id');
        $cause = $this->request->input('cause');
        $comment = $this->request->input('comment');

        $application_section_journal = 5;

        if($cause == 2){
            $application_section_journal = 4;
        }

        $this->database->insert(
            'cancelled_applications',
            [
                'id_application' => $idApplication,
                'id_user' => $idUser,
                'cause' => $cause,
                'comment' => $comment
            ]
        );

        if($this->database->update(
            'applications',
            ['cancelled' => 1, 'application_section_journal' => $application_section_journal],
            ['id' => $idApplication])
        ){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }


    }

    public function ajaxAddTask()
    {
        $applicationId = $this->request->input('application_id');
        $application = new Application(["id" => $applicationId]);
        $applicationArray = $application->get();

        $clientId = $applicationArray["client_id_Client"];
        $client = new Client(["id" => $clientId]);
        $clientArray = $client->get();

        $driverId = $applicationArray["driver_id_Client"];
        $driver = new Driver(["id" => $driverId]);
        $driverArray = $driver->get();

        $taskName = "Выставить счет";

        $createDateTime = date("Y-m-d H:i:s");

        $deadLine = date($createDateTime, strtotime("+1 day"));

        $date_completion = 'No';

        $routesDB = $this->database->select('routes',["id_application" => $applicationId,'type_for' => 1]);

        $textRoutes = '';

        $date = $routesDB[0]['date'];

        foreach($routesDB as $route){
            if($applicationArray['id_user'] == 10 OR $applicationArray['id_user'] == 6)
                $textRoutes .= $route["city"] .' ' .$route["address"] . " - ";
            else
                $textRoutes .= $route["city"] . " - ";
        }

        if($applicationArray['client_id_Client'] == 322){
            $date .= ' ' . str_replace(['<br>','<p>', '</p>'],'',$applicationArray['nature_cargo_Client'])
                .', ' .str_replace(['<br>','<p>', '</p>'],'',$applicationArray['place_Client'])
                .", Заявка № " .$applicationArray['application_number'];

        }

        $textRoutes = trim($textRoutes, ' - ');

        $clientName = $clientArray['name'];
        $clientTaxationType = $applicationArray['taxation_type_Client'];
        $clientTransportationCost = number_format(
            $applicationArray['transportation_cost_Client'],
            0,
            ',',
            '.'
        );

        $driverName = $driverArray['name'];

        $carBrand = $applicationArray['car_brand_id_Client'];
        $clientGovernmentNumber = trim($applicationArray['government_number_Client'], '_');
//        $date = date("d.m.Y", strtotime($date));
        $appNum = $applicationArray['application_number'];
        $comment = "Заявка № {$appNum} <br> ПРОСЬБА ВЫСТАВИТЬ СЧЕТ ООО:<br>{$clientName}<br>
            {$clientTransportationCost} {$clientTaxationType}<br><br>
            {$driverName}, a/м {$carBrand }, {$clientGovernmentNumber},
             загрузка {$date}.<br>Маршрут: " .$textRoutes;
        $executor_id = 1;

        $status = 0;

        $task = new Task();
        $task->edit([
            'name'=>$taskName,
            'create_datetime'=>$createDateTime,
            'deadline'=>$deadLine,
            'date_completion'=>$date_completion,
            'comment'=> $comment,
            'executor_id'=>$executor_id,
            'status'=>$status,
            'application_id' => $applicationId]);

        if ($task->save()){
            echo json_encode($task->get());
        }
        else{
            echo "Error";
        }
    }

    public function checkIssetCarrierInn()
    {
        $inn = $this->request->input('inn');

        if($this->database->select('carriers', ['inn' => $inn],[],1,['id']))
            print json_encode(['isset' => 1]);
        else
            print json_encode(['isset' => 0]);
    }

    public function ajaxUploadFiles(){
        $files = [];

        $date = date("Y-m-d");

        $doc_id = $this->request->input('doc_id');


        if ($this->request->input('application_id') !== null){
            $application_id = $this->request->input('application_id');
            $applicationData = $this->database->select(
                'applications',
                ['id' => $application_id],
                [],
                1,
                ['id', 'client_id_Client','id_user','application_number'],
            )[0];

            $clientData = $this->database->select(
                'clients',
                ['id' => $applicationData['client_id_Client']],
                [],
                1,
                ['id','name', 'format_work']
            )[0];
        }
        else{
            $application_id = 0;
        }


        if ($this->request->input('client_id') !== null){
            $client_id = $this->request->input('client_id');
        }
        else{
            $client_id = 0;
        }

        foreach ($this->request->files as $key => $value) {
            $file = $this->request->file("$key");

            $file_upload = $file->upload('docs');

            $link = $this->storage->url($file_upload) ?? 'Error';

            $fileModel = new File();
            $fileModel->edit(['name' => $file->name, 'link' => $link, 'date' => $date, 'application_id' => $application_id, 'client_id' => $client_id, 'document_id' => $doc_id]);
            $fileModel->save();

//            $files[] = $fileModel->get();
        }


        $filesDB = $this->database->select('files',["application_id" => $application_id,'document_id' => $doc_id],[],-1,['id']) ?? [];

        if($client_id > 0){
            $filesDB = $this->database->select('files',["client_id" => $client_id,'document_id' => $doc_id],[],-1,['id']) ?? [];
        }


        foreach ($filesDB as $file){
            $fileModel = new File(['id' => $file['id']]);
            $files[] = $fileModel->get();
        }



        echo json_encode($files);

        $userId = $this->auth->user()->get(["id"])["id"];

        $this->database->insert("changes", [
            "changes" => json_encode([[
                "key" => 'file_upload',
                "files" => $files

            ]], JSON_UNESCAPED_UNICODE),
            "application_id" => $application_id,
            "datetime" =>  date("Y-m-d H:i:s"),
            "user_id" => $userId
        ]);

        $modelChangeApplicationStatus = new ApplicationChangeStatus($this->database);

        $modelChangeApplicationStatus->checkPossibleChangeStatus($application_id);

        $modelNotificationApplication = new ApplicationNotification($this->database);

        $modelNotificationApplication->needSendNotification($application_id);

    }

    public function ajaxDeleteFile()
    {
        $id = $this->request->input('id');

        $userId = $this->request->input('user_id');

        $file = $this->database->select("files", ['id' => $id])[0];

        if ($this->database->delete("files", ['id' => $id])) {
            echo "Success";
            $this->database->insert("changes", [
                "changes" => json_encode([[
                    "key" => 'file_delete',
                    "name" => $file['name'],
                    "document_id" => $file["document_id"],
                    "client_id" => $file["client_id"],

                ]], JSON_UNESCAPED_UNICODE),
                "application_id" => $file['application_id'],
                "datetime" =>  date("Y-m-d H:i:s"),
                "user_id" => $userId
            ]);
        }
        else
            echo "Error";
    }

    public function ajaxGetTermsPayment()
    {
        $termsPayment = new TermsPayment(['id'=> $this->request->input('id')]);

        echo 'test';
    }

    public function ajaxGetTermsPaymentDescription(){
        $id = $this->request->input('id');

        echo $this->database->first('terms_payment', ["id" => $id])['description'];
    }
    public function ajaxGetConditions()
    {
        $condition = new Condition(['id'=> $this->request->input('id')]);

        echo $condition->get(['description'])['description'];
    }

    public function addCarrier()
    {
        $string_legal_address = '';

        foreach ($this->request->input('info_inputs') as $item) {
            $string_legal_address.= $item.'||';
        }
        $string_legal_address = trim($string_legal_address, "||");

        $form_data = $this->request->input('form_data');

        $errors = [];

        $nameValidation = $this->validator->validate(["name" => $form_data['carrier_name']], ["name" => ["required", "min:3"]]);

        if (!$nameValidation)
            $errors += $this->validator->errors();

        $innValidation = $this->validator->validate(["inn" => $form_data['carrier_inn']], ["inn" => ["required", "min:3"]]);
        if (!$innValidation)
            $errors += $this->validator->errors();

        $legalAddressValidation = $this->validator->validate(["address" => $form_data['carrier_legal_address']], ["address" => ["required", "min:3"]]);
        if (!$legalAddressValidation)
            $errors += $this->validator->errors();

        if ($nameValidation && $innValidation && $legalAddressValidation) {
            $existenceOrganization = new Carrier(['inn' => $form_data['carrier_inn']]);
            if(! $existenceOrganization->get(['id'])['id'] ) {
                $carrier = new Carrier();
                $carrier->edit([
                    'name' => $form_data['carrier_name'],
                    'inn' => $form_data['carrier_inn'],
                    'legal_address' => $form_data['carrier_legal_address'],
                    'info' => $string_legal_address
                ]);

                if ($carrier->save()) {
                    echo json_encode(["status" => "Success", "data" => $carrier->get()]);
                } else
                    echo json_encode(["status" => "Error"]);
            }
            else{
                echo json_encode(["status" => "Error", "errorText" => 'Организация с таким ИНН уже есть в базе']);
            }
        }
        else{
            echo json_encode(["status" => "Error", "error" => $errors]);
        }
    }

    public function addClient()
    {
        $form_data = $this->request->post;


        $this->request->validate([
            "type_taxation_id" => ["required"],
            "format_work" => ['required'],
            "application_format" => ["min:1"],
            "client_name" => ["required", "min:3"],
            "client_inn" => ["required", "min:3"],
            "client_legal_address" => ["required", "min:3"],
            "client_full_name" => ["required", "min:3"],
            "client_job_title" => ["required", "min:3"],
            "client_phone" => ["required", "min:3"],
            "client_email" => ["required", "min:3", "email"],
        ]);

        if (empty($this->request->errors())){
            $existenceOrganization = new Client(['inn' => $form_data['client_inn']]);

            if(! $existenceOrganization->get(['id'])['id'] ) {
                $client = new Client();
                $client->edit([
                    'type_taxation_id' => $form_data['type_taxation_id'],
                    'format_work' => $form_data['format_work'],
                    "application_format" => $form_data['application_format'],
                    'name' => $form_data['client_name'],
                    'inn' => $form_data['client_inn'],
                    'legal_address' => $form_data['client_legal_address'],
                    'full_name' => $form_data['client_full_name'],
                    'job_title' => $form_data['client_job_title'],
                    'phone' => $form_data['client_phone'],
                    'email' => $form_data['client_email'],
                    'users_access' => "|" . $this->auth->user()->get(["id"])["id"] . "|"
                ]);
                if ($client->save()) {
                    echo json_encode(["status" => "Success", "data" => $client->get()]);

                    $client_info = new ClientInfo();
                    $client_id = $client->get(['id'])['id'];
                    $client_info->edit(['client_id' => $client_id]);
                    $client_info->save();
                } else
                    echo json_encode(["status" => "Error"]);
            }
            else{
                echo json_encode(["status" => "Error", "errorText" => 'Организация с таким ИНН уже есть в базе']);
            }
        }
        else{
            echo json_encode(["status" => "Error", "error" => $this->request->errors()]);
        }
    }

    public function editDriver()
    {
        $form_data = $this->request->post;

        $this->request->validate([
            "driver_full_name" => ["required", "min:3"],
            "driver_license" => ["required", "min:3"],
            "driver_phone" => ["required", "min:3"],
            "driver_passport_serial_number" => ["required", "min:3"],
            "driver_issued_by" => ["required", "min:3"],
            "driver_issued_date" => ["required", "min:3"],
            "driver_department_code" => ["required", "min:3"],
        ]);

        if (empty($this->request->errors())){
            $driver = new Driver(['id' => $form_data['driver_id']]);

            $driver->edit([
                'name' => $form_data['driver_full_name'],
                'driver_license' => $form_data['driver_license'],
                'phone' => $form_data['driver_phone'],
                'passport_serial_number' => $form_data['driver_passport_serial_number'],
                'issued_by' => $form_data['driver_issued_by'],
                'issued_date' => $this->format_date($form_data['driver_issued_date'], "Y-m-d"),
                'department_code' => $form_data['driver_department_code'],
            ]);

            if($driver->save()){
                echo json_encode(["status" => "Success", "data" => $driver->get()]);
            }
            else
                echo json_encode(["status" => "Error"]);
        }
        else{
            echo json_encode($this->request->errors());
        }
    }

    public function addDriver()
    {
        $form_data = $this->request->post;

        $this->request->validate([
            "driver_full_name" => ["required", "min:3"],
            "driver_license" => ["required", "min:3"],
            "driver_phone" => ["required", "min:3"],
            "driver_passport_serial_number" => ["required", "min:3"],
            "driver_issued_by" => ["required", "min:3"],
            "driver_issued_date" => ["required", "min:3"],
            "driver_department_code" => ["required", "min:3"],
        ]);

        if (empty($this->request->errors())){
            $driver = new Driver();

            $driver->edit([
                'name' => $form_data['driver_full_name'],
                'driver_license' => $form_data['driver_license'],
                'phone' => $form_data['driver_phone'],
                'passport_serial_number' => $form_data['driver_passport_serial_number'],
                'issued_by' => $form_data['driver_issued_by'],
                'issued_date' => $this->format_date($form_data['driver_issued_date'], "Y-m-d"),
                'department_code' => $form_data['driver_department_code'],
            ]);

            if($this->request->input('is-our-driver')){
                $driver->edit([
                    'is_our_driver' => 1
                ]);
            }

            if($driver->save()){
                echo json_encode(["status" => "Success", "data" => $driver->get()]);
            }
            else
                echo json_encode(["status" => "Error"]);
        }
        else{
            echo json_encode(["status" => "Error", 'error' => $this->request->errors()]);
        }
    }

    public function ajaxGetCarrier()
    {
        $id = $this->request->input('id');

        $carrier = new Carrier(['id' => $id]);

        echo json_encode($carrier->get());
    }

    public function ajaxGetAddition(){
        $id = $this->request->input("id");

        $addition = new Addition(['id' => $id]);

        echo $addition->get(["description"])["description"];
    }

    public function ajaxEditCarrier(){
        $id = $this->request->input('form_data')['carrier_edit_id'];

        $string_info = '';

        foreach ($this->request->input('info_inputs') as $item) {
            $string_info.= $item.'||';
        }
        $string_info = trim($string_info, "||");

        $form_data = $this->request->input('form_data');

        $carrier = new Carrier(['id' => $id]);
        $carrier->edit([
            'name' => $form_data['carrier_name'],
            'inn' => $form_data['carrier_inn'],
            'legal_address' => $form_data['carrier_legal_address'],
            'info' => $string_info
        ]);

        if($carrier->save()){
            echo json_encode($carrier->get());
        }
        else
            echo "Error";
    }

    public function ajaxGetClient()
    {
        $id = $this->request->input('id');

        $client = new Client(['id' => $id]);

        $contactFaces = $this->database->select("contact_faces", ["client_id" => $id], [], -1, ["phone"]);

        echo json_encode(array_merge($client->get(), ["phones" => $contactFaces]));
    }

    public function ajaxEditClient(){
        $id = $this->request->post['client_edit_id'];

        $form_data = $this->request->post;

        $client = new Client(['id' => $id]);
        $client->edit([
            'name' => $form_data['client_name'],
            'inn' => $form_data['client_inn'],
            'legal_address' => $form_data['client_legal_address'],
            'phone' => $form_data['client_edit_phone']
        ]);

        if($client->save()){
            echo json_encode($client->get());;
        }
        else
            echo "Error";
    }

    public function ajaxGetDriver()
    {
        $id = $this->request->input('id');

        $driver = new Driver(['id' => $id]);
        $driverData = $driver->get();

        $driverData['issued_date'] = date('d.m.Y', strtotime($driverData['issued_date']));

        echo json_encode($driverData);
    }

    public function ajaxAddApplication()
    {
        $application = new Application();

        $data = $this->request->post;

//        if($data['customerIdClient'] == 1 AND $data['taxationTypeClient'] != 'С НДС'){
//            echo json_encode([
//                "status" => "Error taxation",
//                'message' => 'Неправильно выбран вид налогообложения во вкладке "ПЕРЕВОЗЧИК"'
//            ]);
//            return false;
//        }

        $client = new Client(['id' => $this->request->input('clientIdClient')]);
        $clientApplicationFormat = $client->get()['application_format'];
        $clientFormat = '';

        if($clientApplicationFormat){
            $clientFormat = 'required';
        }
        $this->request->validate([
            "customerIdCarrier" => ["required","min:1"],
            "applicationNumberClient" => [$clientFormat],
            "carrierIdCarrier" => ["required"],
            "driverIdCarrier" => ["required"],
            "carBrandIdCarrier" => ["required"],
            "governmentNumberCarrier" => ["required"],
            "typeTransportIdCarrier" => ["required"],
            "natureCargoCarrier" => ["required"],
            "placeCarrier" => ["required"],
            "weightCarrier" => ["required"],
            "refModeCarrier" => ["required"],
            "transportationCarrier" => ["required"],
            "transportationCostCarrier" => ["required"],
            "taxationTypeCarrier" => ["required"],
//            "prerequisitesCarrier" => ["required"],
            "customerIdClient" => ["required"],
            "clientIdClient" => ["required"],
            "carBrandIdClient" => ["required"],
            "governmentNumberClient" => ["required"],
            "typeTransportIdClient" => ["required"],
            "natureCargoClient" => ["required"],
            "placeClient" => ["required"],
            "weightClient" => ["required"],
            "refModeClient" => ["required"],
            "transportationClient" => ["required"],
            "transportationCostClient" => ["required"],
            "driverIdClient" => ["required"],
        ]);

        if($data['costCargoClient'] == ''){
            $data['costCargoClient'] = 0;
        }

        if (empty($this->request->errors())){
            $application->edit($data);
            $application->edit(['idUser' => $this->auth->user()->id()]);
            if ($application->save()) {
                $carData = [
                    'id_driver' => $data['driverIdCarrier'],
                    'car_brand' => $data['carBrandIdCarrier'],
                    'government_number' => $data['governmentNumberCarrier'],
                    'semitrailer' => $data['semitrailerCarrier'],
                    'type_transport' => $data['typeTransportIdCarrier'],
                    'type_carcase' => $data['typeCarcaseIdCarrier'],
                ];
                $driverCar = new DriverCar($carData);

                if(! $driverCar->get()['id']) {
                    $driverCar->edit($carData);
                    $driverCar->save();
                }

                echo json_encode(['status' => 'Success', 'id' => $application->id()]);
            }
            else
                echo json_encode(['status' => 'Error']);
        }
        else{
            echo json_encode(['status' => 'Validate Error', 'error' => $this->request->errors()]);
        }

    }
    public function ajaxEditApplication()
    {
        $applicationId = $this->request->input("applicationId");

        $application = new Application(["id" => $applicationId]);

        $data = $this->request->post;

//        var_dump($data);

//        str_replace(['"',"'"], ['\"', "\'"], $data);
        $client = new Client(['id' => $this->request->input('clientIdClient')]);
        $clientApplicationFormat = $client->get()['application_format'];
        $clientFormat = '';

        if($clientApplicationFormat){
            $clientFormat = 'required';
        }

        $this->request->validate([
            "customerIdCarrier" => ["required","min:1"],
//            "dateCarrier" => [],
            "applicationNumberClient" => [$clientFormat],
            "carrierIdCarrier" => ["required"],
            "driverIdCarrier" => ["required"],
            "carBrandIdCarrier" => ["required"],
            "governmentNumberCarrier" => ["required"],
            "typeTransportIdCarrier" => ["required"],
            "natureCargoCarrier" => ["required"],
            "placeCarrier" => ["required"],
            "weightCarrier" => ["required"],
            "refModeCarrier" => ["required"],
            "transportationCarrier" => ["required"],
            "transportationCostCarrier" => ["required"],
            "taxationTypeCarrier" => ["required"],
//            "prerequisitesCarrier" => ["required"],
            "customerIdClient" => ["required"],
            "clientIdClient" => ["required"],
            "carBrandIdClient" => ["required"],
            "governmentNumberClient" => ["required"],
            "typeTransportIdClient" => ["required"],
//
            "natureCargoClient" => ["required"],
            "placeClient" => ["required"],
            "weightClient" => ["required"],
            "refModeClient" => ["required"],

//            "specialConditionsCarrier" => [],
//            "specialConditionsClient" => [],
            "transportationClient" => ["required"],
//            "termsPaymentCarrier" => ["required"],
//            "termsPaymentClient" => ["required"],
            "transportationCostClient" => ["required"],
            // "costCargoClient" => ["required"],
            "taxationTypeClient" => ["required"],
//            "fines" => ["required", "min:5"],
//            "expensesCarrier" => ["required", "min:5"],
//            "expensesClient" => ["required", "min:5"],
//            "additionId" => ["required", "min:1"],
            "driverIdClient" => ["required"],
        ]);

        if($data['costCargoClient'] == ''){
            $data['costCargoClient'] = 0;
        }

        if (empty($this->request->errors())){
            $application->edit($data);

            $userId = $this->auth->user()->get(["id"])["id"];

            $changeHistory = new ApplicationChangeHistory($applicationId, $application, $userId);
            $changeHistory->saveChangeHistory();

            if ($application->save()) {
                $carData = [
                    'id_driver' => $data['driverIdCarrier'],
                    'car_brand' => $data['carBrandIdCarrier'],
                    'government_number' => $data['governmentNumberCarrier'],
                    'semitrailer' => $data['semitrailerCarrier'],
                    'type_transport' => $data['typeTransportIdCarrier'],
                    'type_carcase' => $data['typeCarcaseIdCarrier'],
                ];
                $driverCar = new DriverCar($carData);

                if(! $driverCar->get()['id']) {
                    $driverCar->edit($carData);
                    $driverCar->save();
                }

                // print ($application->checkForSales());

                echo json_encode(['status' => 'Success', 'id' => $application->id(), 'forSales' => $application->checkForSales()]);

            }
            else
                echo json_encode(['status' => 'Error']);
        }
        else{
            echo json_encode(['status' => 'Validate Error', 'error' => $this->request->errors()]);;
        }

    }
    public function format_date($date, $format)
    {
        $intDate = strtotime($date);
        return date($format, $intDate);
    }
}