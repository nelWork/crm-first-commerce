<?php

namespace App\User\Contoller\Common;

use App\Model\Addition\Addition;
use App\Model\CarBrands\CarBrands;
use App\Model\Client\Client;
use App\Model\Condition\Condition;
use App\Model\Customer\Customer;
use App\Model\Driver\Driver;
use App\Model\Driver\DriverCar;
use App\Model\File\File;
use App\Model\Fines\Fines;
use App\Model\Marshrut\Marshrut;
use App\Model\Search\SearchApplication;
use App\Model\Task\Task;
use App\Model\TermsPayment\TermsPayment;
use App\Model\TSApplication\Forwarder;
use App\Model\TSApplication\TSApplication;
use App\Model\TypeCarcase\TypeCarcase;
use App\Model\TypeTaxation\TypeTaxation;
use App\Model\TypeTransport\TypeTransport;
use App\Model\User\User;
use App\User\Contoller\Controller;
use App\User\Model\Application\ApplicationChangeHistory;
use App\User\Model\Application\Comment;
use App\User\Model\Application\Plan;
use App\User\Model\Carrier\CarrierList;
use App\User\Model\Client\ClientList;
use App\User\Model\Driver\DriverList;
use App\User\Model\TS\ForwardersList;
use App\User\Model\TS\TSApplicationList;
use App\User\Model\User\ManagerPlan;
use App\User\Model\User\UserList;

class TSApplicationController extends Controller
{

    public function ajaxAddComment()
    {
        $applicationId = $this->request->input("id_application");
        $commentText = $this->request->input("comment");
        $typeComment = $this->request->input("type_comment");
        $userId = $this->auth->user()->id();

        $newComment = new Comment([],'ts_');

        $newComment->edit([
            'id_application' => (int)$applicationId,
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

        $editComment = new Comment(['id' => $commentId],'ts_');

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
            'ts_comments_application',
            ['id_application' => $applicationId,'visible' => 1],
            ['important' => 'DESC'],
            -1 ,
            ['id']
        ) ?? [];


        $listComment = [];

        foreach($commentsDB as $comment){
            $temp = new Comment(['id'=> $comment['id']],'ts_');
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
        $comment = new Comment(['id' => $this->request->input("id")],'ts_');

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
        $comment = new Comment(['id' => $this->request->input("id")],'ts_');

        $comment->edit(['visible' => 0]);

        if($comment->save()){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }
    public function TSPage()
    {
        $idApp = $this->request->input('id');

        $user = $this->auth->user();

        $isFullCRMAccess = $user->fullCRM();

        if($isFullCRMAccess){
            $TSApplication = new TSApplication(['id' => $idApp]);
            $userManager = new User(['id' => $TSApplication->get()['id_user']]);
        }
        else{
            $TSApplication = new TSApplication(['id' => $idApp, 'id_user' => $user->id()]);
            $userManager = $user;
        }

//        $client = new Client(['id' => $TSApplication->get()['client_id_client']]);
        $forwarder = new Forwarder(['id' => $TSApplication->get()['id_forwarder']]);

//        $documents = $this->database->select("ts_files", ["application_id" => $TSApplication->id()]);

//        $documentComments = $this->database->select('ts_application_document_comment', ['id_application' => $TSApplication->id()]);

        $listDatabaseTasks = $this->database->select(
            'ts_tasks',
            ['application_id' => $TSApplication->id()]
        );
        $listTasks = [];


        foreach($listDatabaseTasks as $task){
            $temp = new Task(['id'=> $task['id']],'ts_');
            $listTasks[] = $temp->get();
        }

        $this->extract([
            'controller'=> $this,
            'TSApplication' => $TSApplication,
            'userManager' => $userManager,
            'titlePage' => 'Заявка ТС',
            'isFullCRMAccess' => $isFullCRMAccess,
            'activeHeaderItem' => 1,
            'listTasks' => $listTasks,
//            'client' => $client,
//            'documents' => $documents,
//            'documentComments' => $documentComments,
            'forwarder' => $forwarder,
        ]);
        $this->view('TS/ts-application');
    }

    public function add()
    {
        $currentUser = $this->auth->user();
        $currentUserData = $currentUser->get();

        $isUserAdmin = $currentUserData["role"] === 6 || $currentUserData["role"] === 7;

        $forwardersList = new ForwardersList($this->database);
        $listForwarders = $forwardersList->simpleListForwarders();

        $listDatabaseTermsPayment = $this->database->select(
            'terms_payment',
            ['type_for' => 1]
        );

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

        //сustomers
        $listDatabaseCustomers = $this->database->select('customers');
        $listCustomers = [];
        foreach($listDatabaseCustomers as $customer){
            $temp = new Customer(['id'=> $customer['id']]);
            $listCustomers[] = $temp->get();
        }


        //drivers
        $driversList = new DriverList($this->database);
        $listDrivers = $driversList->simpleListDrivers(['is_our_driver' => 1]);

        //drivers
        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }

        $tsTransportList = $this->database->select('ts_transport');


        $this->extract([
            'controller' => $this,
            'titlePage' => "Добавление заявки ТС",
            'listTermsPayment' => $listTermsPayment,
            'listConditions' => $listConditions,
            'listCustomers' => $listCustomers,
            'listTypesTaxation' => $listTypesTaxation,
            'tsTransportList' => $tsTransportList,
            'listDrivers' => $listDrivers,
            'listForwarders' => $listForwarders,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
        ]);
        $this->view("TS/add");
    }

    public function ajaxAddApplicationTS()
    {
        $data = $this->request->post;

//        var_dump($data);

        $this->request->validate([
//            "date" => ["required"],
//            "applicationNumber" => ["required"],
            "natureCargo" => ["required"],
            "idCustomer" => ["required"],
            "idForwarder" => ["required"],
            "place" => ["required"],
            "weight" => ["required"],
            "refMode" => ["required"],
//            "specialConditions" => ["required"],
            "termsPayment" => ["required"],
            "transportationCost" => ["required"],
            "taxationType" => ["required"],
//            "costCargo" => ["required"],
            "idDriver" => ["required"],
            "carBrand" => ["required"],
            "governmentNumber" => ["required"],
            "semitrailer" => ["required"],
            "typeTransport" => ["required"],
            "typeCarcase" => ["required"],
        ]);

        if (empty($this->request->errors())){
            $TSApplication = new TSApplication();

            $TSApplication->edit($data);

            $TSApplication->edit(['idUser' => $this->auth->user()->id()]);

//            $userId = $this->auth->user()->get(["id"])["id"];
//
//            $changeHistory = new ApplicationChangeHistory($applicationId, $application, $userId);
//            $changeHistory->saveChangeHistory();

            if ($TSApplication->save()) {


                echo json_encode(['status' => 'Success', 'id' => $TSApplication->get()['id']]);

            }
            else
                echo json_encode(['status' => 'Error']);
        }
        else{
            echo json_encode(['status' => 'Validate Error', 'error' => $this->request->errors()]);
        }

    }

    public function edit(){
        $applicationId = $this->request->input('id');

        $TSApplication = new TSApplication(['id'=> $applicationId]);

        $additionalExpensesList = $TSApplication->getAdditionalExpensesList();
        $additionalProfitList = $TSApplication->getAdditionalProfitList();


        $currentUser = $this->auth->user();
        $currentUserData = $currentUser->get();

        $isUserAdmin = $currentUserData["role"] === 6 || $currentUserData["role"] === 7;

        $forwardersList = new ForwardersList($this->database);
        $listForwarders = $forwardersList->simpleListForwarders();

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
        $listDrivers = $driversList->simpleListDrivers(['is_our_driver' => 1]);

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
        $listDatabaseRoutes = $this->database->select('routes_ts',["id_application" => $applicationId]);
        $listRoutes = [];
        foreach($listDatabaseRoutes as $route){
            $temp = new Marshrut(['id'=> $route['id']], true);
            $listRoutes[] = $temp->get();
        }

        //Fines
        $listDatabaseFines = $this->database->select('fines',["id_application" => $applicationId]);
        $listFines = [];
        foreach($listDatabaseFines as $fine){
            $temp = new Fines(['id'=> $fine['id']]);
            $listFines[] = $temp->get();
        }

        $tsTransportList = $this->database->select('ts_transport');


        $this->extract([
            'controller' => $this,
            'titlePage' => "Редактирование заявки ТС",
            'listTermsPayment' => $listTermsPayment,
            'TSApplication' => $TSApplication->get(),
            'listConditions' => $listConditions,
            'listCarBrands' => $listCarBrands,
            'listTypeTransport' => $listTypeTransport,
            'listCustomers' => $listCustomers,
            'listTypesTaxation' => $listTypesTaxation,
            'listForwarders' => $listForwarders,
            'tsTransportList' => $tsTransportList,
            'listDrivers' => $listDrivers,
            'listCarcaseTypes' =>  $listCarcaseTypes,
            'additionalExpensesList' => $additionalExpensesList,
            'additionalProfitList' => $additionalProfitList,
            "listAdditions" => $listAdditions,
            "listRoutes" => $listRoutes,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
        ]);
        $this->view("TS/edit");
    }

    public function ajaxGetDataTsTransport()
    {
        if($this->request->input('idTransport')){
            echo json_encode($this->database->first('ts_transport',['id' => $this->request->input('idTransport')]));
        }
    }

    public function ajaxEditApplicationTS()
    {

        $data = $this->request->post;

        $this->request->validate([
//            "date" => ["required"],
//            "applicationNumber" => ["required"],
            "natureCargo" => ["required"],
            "idCustomer" => ["required"],
            "idForwarder" => ["required"],
            "place" => ["required"],
            "weight" => ["required"],
            "refMode" => ["required"],
//            "specialConditions" => ["required"],
            "termsPayment" => ["required"],
            "transportationCost" => ["required"],
            "taxationType" => ["required"],
//            "costCargo" => ["required"],
            "idDriver" => ["required"],
            "carBrand" => ["required"],
            "governmentNumber" => ["required"],
            "semitrailer" => ["required"],
            "typeTransport" => ["required"],
            "typeCarcase" => ["required"],
        ]);

        if (empty($this->request->errors())){
            $TSApplication = new TSApplication(['id' => $this->request->input('id')]);

            $TSApplication->edit($data);

//            $userId = $this->auth->user()->get(["id"])["id"];
//
//            $changeHistory = new ApplicationChangeHistory($applicationId, $application, $userId);
//            $changeHistory->saveChangeHistory();

            if ($TSApplication->save()) {


                echo json_encode(['status' => 'Success', 'id' => $TSApplication->get()['id']]);

            }
            else
                echo json_encode(['status' => 'Error']);
        }
        else{
            echo json_encode(['status' => 'Validate Error', 'error' => $this->request->errors()]);;
        }

    }

    public function applicationsListPage()
    {
        $activeUser = $this->auth->user();

        $isFullCRMAccess = $activeUser->fullCRM();

        $applicationListModel = new TSApplicationList($this->database);

        $userListModel = new UserList($this->database);

        $link = '?';


        if($this->request->input('order'))
            $order = ['id' => $this->request->input('order')];
        else
            $order = ['id' => 'DESC'];

        $condition = [];
        $conditionFilter = ['manager' => [], 'client-taxation' => '', 'carrier-taxation' => '',
            'client' => '', 'carrier' => '', 'date' => '', 'cancelled' => false];



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

            if(count($userLogist->getSubordinatesList()) > 0 AND $this->request->input('rop') == 1){
                $condition['id_user'] = [];

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

        $listSearch = [];
        $searchData = ['search-select' => '', 'search' => ''];


        if($this->request->input('search') AND $this->request->input('search-select')){
            $searchText = $this->request->input('search');
            $searchSelect = 'number';
            $searchModel = new SearchApplication($searchSelect);

            $link .= '?search-select=' . $searchSelect . '&search=' . $searchText;

            $listSearch = $searchModel->search($searchText, 'ts_application');

            $searchData['search-select'] = $searchSelect;
            $searchData['search'] = $searchText;
        }

        if(count($listSearch) > 0){
            $condition['id'] = $listSearch;
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



        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Заявки ТС',
            'applicationList' => $applicationListModel->listApplication($page, $condition, $order, $elementsPage),
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
            'rop' => $rop
        ]);
        $this->view('TS/applications-list');
    }
    public function addTransport(): void
    {
        print json_encode(['status' => $this->database->insert('ts_transport', $_POST), 'data' => $_POST]);
    }
    public function addForwarder(): void
    {
        $string_info = '';

        foreach ($this->request->input('info_inputs') as $item) {
            $string_info.= $item.'||';
        }
        $string_info = trim($string_info, "||");

        $form_data = $this->request->input('form_data');

        $errors = [];

        $nameValidation = $this->validator->validate(["name" => $form_data['forwarder_name']], ["name" => ["required", "min:3"]]);

        if (!$nameValidation)
            $errors += $this->validator->errors();

        $innValidation = $this->validator->validate(["inn" => $form_data['forwarder_inn']], ["inn" => ["required", "min:3"]]);
        if (!$innValidation)
            $errors += $this->validator->errors();

        $legalAddressValidation = $this->validator->validate(["address" => $form_data['forwarder_legal_address']], ["address" => ["required", "min:3"]]);
        if (!$legalAddressValidation)
            $errors += $this->validator->errors();


        if ($nameValidation && $innValidation && $legalAddressValidation) {
            $existenceOrganization = new Forwarder(['inn' => $form_data['forwarder_inn']]);
            if(! $existenceOrganization->get(['id'])['id'] ) {
                $forwarders = new Forwarder();
                $forwarders->edit([
                    'name' => $form_data['forwarder_name'],
                    'inn' => $form_data['forwarder_inn'],
                    'legal_address' => $form_data['forwarder_legal_address'],
                    'contact' => $string_info
                ]);

                if ($forwarders->save()) {
                    echo json_encode(["status" => "Success", "data" => $forwarders->get()]);
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

    public function editForwarder(): void
    {
        $string_info = '';

        foreach ($this->request->input('info_inputs') as $item) {
            $string_info.= $item.'||';
        }
        $string_info = trim($string_info, "||");

        $form_data = $this->request->input('form_data');

        $errors = [];

        $nameValidation = $this->validator->validate(["name" => $form_data['forwarder_name']], ["name" => ["required", "min:3"]]);

        if (!$nameValidation)
            $errors += $this->validator->errors();

        $innValidation = $this->validator->validate(["inn" => $form_data['forwarder_inn']], ["inn" => ["required", "min:3"]]);
        if (!$innValidation)
            $errors += $this->validator->errors();

        $legalAddressValidation = $this->validator->validate(["address" => $form_data['forwarder_legal_address']], ["address" => ["required", "min:3"]]);
        if (!$legalAddressValidation)
            $errors += $this->validator->errors();


        if ($nameValidation && $innValidation && $legalAddressValidation) {

                $forwarders = new Forwarder(['id' => $form_data['forwarder_edit_id']]);
                $forwarders->edit([
                    'name' => $form_data['forwarder_name'],
                    'inn' => $form_data['forwarder_inn'],
                    'legal_address' => $form_data['forwarder_legal_address'],
                    'contact' => $string_info
                ]);

                if ($forwarders->save()) {
                    echo json_encode(["status" => "Success", "data" => $forwarders->get()]);
                } else
                    echo json_encode(["status" => "Error"]);
        }
        else{
            echo json_encode(["status" => "Error", "error" => $errors]);
        }
    }
    public function ajaxGetForwarder()
    {
        $id = $this->request->input('id');

        $forwarder = new Forwarder(['id' => $id]);

        echo json_encode($forwarder->get());
    }

    public function ajaxEditForwarder(){
        $id = $this->request->input('form_data')['forwarder_edit_id'];

        $string_info = '';

        foreach ($this->request->input('info_inputs') as $item) {
            $string_info.= $item.'||';
        }
        $string_info = trim($string_info, "||");

        $form_data = $this->request->input('form_data');

        $carrier = new Forwarder(['id' => $id]);
        $carrier->edit([
            'name' => $form_data['forwarder_name'],
            'inn' => $form_data['forwarder_inn'],
            'legal_address' => $form_data['forwarder_legal_address'],
            'contact' => $string_info
        ]);

        if($carrier->save()){
            echo json_encode($carrier->get());
        }
        else
            echo "Error";
    }

    public function ajaxChangeCommentDocument()
    {
        $idUser = $this->auth->user()->id();
        $idApp = $this->request->input('idApp');
        $numDoc = $this->request->input('numDoc');
        $name = $this->request->input('name');
        $comment = $this->request->input('comment');


        $commentDocument = $this->database->select(
            'ts_application_document_comment',
            [
                'id_application' => $idApp,
                'name' => $name,
                'num_document' => $numDoc,
            ]
        );

        if(!$commentDocument){
            print $this->database->insert(
                'ts_application_document_comment',
                [
                    'name' => $this->request->input('name'),
                    'comment' => $this->request->input('comment'),
                    'id_application' => $this->request->input('idApp'),
                    'num_document' => $this->request->input('numDoc'),
                    'datetime' => date("Y-m-d H:i:s"),
                    'id_user' => $idUser
                ]
            );
        }
        else{
            $this->database->update(
                'ts_application_document_comment',
                ['comment' => $comment, 'datetime' => date("Y-m-d H:i:s"),
                    'id_user' => $idUser],
                ['id' => $commentDocument[0]['id']]
            );
            unset($commentDocument[0]['id']);

            print $this->database->insert('ts_application_document_comment_history', $commentDocument[0]);
        }


    }


    public function ajaxUploadFiles(){
        $files = [];

        $date = date("Y-m-d");

        $doc_id = $this->request->input('doc_id');


        if ($this->request->input('application_id') !== null){
            $application_id = $this->request->input('application_id');
        }
        else{
            echo json_encode(['status' => 'Error']);
            return false;
        }

        foreach ($this->request->files as $key => $value) {
            $file = $this->request->file("$key");

            $file_upload = $file->upload('docs');

            $link = $this->storage->url($file_upload) ?? 'Error';

            $fileModel = new File([],'ts_');
            $fileModel->edit(
                [
                    'name' => $file->name,
                    'link' => $link,
                    'date' => $date,
                    'application_id' => $application_id,
                    'document_id' => $doc_id
                ]);
            $fileModel->save();

            $files[] = $fileModel->get();
        }
        echo json_encode($files);

//        $userId = $this->auth->user()->get(["id"])["id"];

//        $this->database->insert("changes", [
//            "changes" => json_encode([[
//                "key" => 'file_upload',
//                "files" => $files
//
//            ]], JSON_UNESCAPED_UNICODE),
//            "application_id" => $application_id,
//            "datetime" =>  date("Y-m-d H:i:s"),
//            "user_id" => $userId
//        ]);

    }

    public function ajaxCopyTSApplication()
    {
        $applicationId = $this->request->input("id");

        $application = new TSApplication(["id"=> $applicationId]);

        print $application->copy();
    }

    public function ajaxDeleteFile()
    {
        $id = $this->request->input('id');

        $userId = $this->request->input('user_id');

        $file = $this->database->select("ts_files", ['id' => $id])[0];

        if ($this->database->delete("ts_files", ['id' => $id])) {
            echo "Success";
//            $this->database->insert("changes", [
//                "changes" => json_encode([[
//                    "key" => 'file_delete',
//                    "name" => $file['name'],
//                    "document_id" => $file["document_id"],
//                    "client_id" => $file["client_id"],
//
//                ]], JSON_UNESCAPED_UNICODE),
//                "application_id" => $file['application_id'],
//                "datetime" =>  date("Y-m-d H:i:s"),
//                "user_id" => $userId
//            ]);
        }
        else
            echo "Error";
    }

    public function ajaxAddTask()
    {
        $applicationId = $this->request->input('application_id');
        $application = new TSApplication(["id" => $applicationId]);
        $applicationArray = $application->get();

        $applicationNumber = $applicationArray['application_number'];

        $forwarderId = $applicationArray["id_forwarder"];
        $forwarder = new Forwarder(["id" => $forwarderId]);
        $forwarderArray = $forwarder->get();

        $taskName = "Выставить счет";

        $createDateTime = date("Y-m-d H:i:s");

        $deadLine = date($createDateTime, strtotime("+1 day"));

        $date_completion = 'No';


        $forwarderName = $forwarderArray['name'];
        $forwarderTaxationType = $applicationArray['taxation_type'];
        $forwarderCost = number_format(
            $applicationArray['transportation_cost'],
            0,
            ',',
            ' '
        );

        $routesDB = $this->database->select('routes_ts',["id_application" => $applicationId]);

        $textRoutes = '';

        $date = $routesDB[0]['date'];

        foreach($routesDB as $route){
            $textRoutes .= $route["city"] . " - ";
        }

        $textRoutes = trim($textRoutes, ' - ');

        $driverId = $applicationArray["id_driver"];
        $driver = new Driver(["id" => $driverId]);
        $driverArray = $driver->get();

        $driverName = $driverArray['name'];

        $carBrand = $applicationArray['car_brand'];
        $clientGovernmentNumber = trim($applicationArray['government_number'], '_');

        $comment = "Заявка № {$applicationNumber} <br>
            ПРОСЬБА ВЫСТАВИТЬ СЧЕТ ООО: <br>
            {$forwarderName} <br>
            {$forwarderCost} {$forwarderTaxationType}  <br>
            <br>
            {$driverName}, a/м {$carBrand }, {$clientGovernmentNumber},загрузка {$date}.<br>
            Маршрут: {$textRoutes}";

        $executor_id = 1;

        $status = 0;

        $task = new Task([],'ts_');
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
}