<?php

namespace App\User\Contoller\Common;

use App\Model\Client\Client;
use App\Model\Customer\Customer;
use App\Model\Driver\DriverCar;
use App\Model\File\File;
use App\Model\PRR\PRRApplication;
use App\Model\PRR\PRRCompany;
use App\Model\PRR\PRRPlace;
use App\Model\Search\SearchApplication;
use App\Model\Task\Task;
use App\Model\TypeTaxation\TypeTaxation;
use App\Model\User\User;
use App\User\Contoller\Controller;
use App\User\Model\Client\ClientList;
use App\User\Model\PRR\PRRApplicationChangeStatus;
use App\User\Model\PRR\PRRCompanyList;
use App\User\Model\PRR\PRRList;
use App\User\Model\User\UserList;

class PRRController extends Controller
{
    public function PRRPage()
    {

        $idApp = $this->request->input('id');

        $user = $this->auth->user();

        $isFullCRMAccess = $user->fullCRM();

        $prrApplication = new PRRApplication(['id' => $idApp]);
        $userManager = new User(['id' => $prrApplication->get()['id_user']]);

        if(! $isFullCRMAccess){
            $subordinatesList = $user->getSubordinatesList();
            if($user->id() !== $prrApplication->idUser()) {
                $access = false;

                foreach ($subordinatesList as $subordinate) {
                    if($subordinate == $prrApplication->idUser()) {
                        $access = true;
                    }
                }

                if(! $access)
                    $this->redirect->to('/applications-list');
            }

        }

        $client = new Client(['id' => $prrApplication->get()['client_id_client']]);
        $prrCompany = new PRRCompany(['id' => $prrApplication->get()['prr_id_prr']]);

        $documents = $this->database->select(
            "prr_files",
            ["application_id" => $prrApplication->id()]
        );

        $documentComments = $this->database->select(
            'prr_application_document_comment',
            [
                'id_application' => $prrApplication->id()
            ]
        );

        $listDatabaseTasks = $this->database->select(
            'prr_tasks',
            ['application_id' => $prrApplication->id()]
        );
        $listTasks = [];


        foreach($listDatabaseTasks as $task){
            $temp = new Task(['id'=> $task['id']],'prr_');
            $listTasks[] = $temp->get();
        }


        $this->extract([
            'controller'=> $this,
            'prrApplication' => $prrApplication,
            'userManager' => $userManager,
            'titlePage' => 'Заявка ПРР',
            'isFullCRMAccess' => $isFullCRMAccess,
            'activeHeaderItem' => 1,
            'client' => $client,
            'listTasks' => $listTasks,
            'documents' => $documents,
            'documentComments' => $documentComments,
            'prrCompany' => $prrCompany,
        ]);
        $this->view('PRR/prr-page');
    }
    public function PRRList()
    {
        $activeUser = $this->auth->user();

        $isFullCRMAccess = $activeUser->fullCRM();

        $prrListModel = new PRRList($this->database);

        $userListModel = new UserList($this->database);

        $link = '?';


        if($this->request->input('order'))
            $order = ['id' => $this->request->input('order')];
        else
            $order = ['id' => 'DESC'];

        $condition = [];
        $condition['cancelled'] = 0;

        $conditionFilter = ['manager' => [], 'client-taxation' => '', 'carrier-taxation' => '',
            'client' => '', 'carrier' => '', 'date' => '', 'cancelled' => false];

        if(! $isFullCRMAccess) {
            $condition['id_user'] = $activeUser->id();
        }

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = $this->session->get('countElementsPage') ?? 25;

        $listSearch = [];
        $searchData = ['search-select' => '', 'search' => ''];
        if($this->request->input('search')){
            $searchText = $this->request->input('search');
            $searchSelect = 'number';
            $searchModel = new SearchApplication($searchSelect);

            $link .= '?search-select=' . $searchSelect . '&search=' . $searchText;

            $listSearch = $searchModel->searchPRR($searchText);

//            dd($listSearch);

            $searchData['search-select'] = $searchSelect;
            $searchData['search'] = $searchText;
        }

        if(count($listSearch) > 0){
            $condition['id'] = $listSearch;
        }

//        dd($condition);

        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Заявки ПРР',
            'prrList' => $prrListModel->listPRR($page, $condition, $order, $elementsPage),
            'userList' => $userListModel->listManager(),
            'condition' => $conditionFilter,
            'countPage' => $prrListModel->countPage($elementsPage, $condition),
            'elementsPage' => $elementsPage,
            'searchData' => $searchData,
            'page' => $page,
            'link' => $link,
            'isFullCRMAccess' => $isFullCRMAccess,
            'activeHeaderItem' => 1,
        ]);
        $this->view('PRR/prr-list');
    }

    public function add()
    {
        $currentUser = $this->auth->user();
        $currentUserData = $currentUser->get();

        $isUserAdmin = $currentUserData["role"] === 6 || $currentUserData["role"] === 7;

        $listDatabaseCustomers = $this->database->select('customers');
        $listCustomers = [];
        foreach($listDatabaseCustomers as $customer){
            $temp = new Customer(['id'=> $customer['id']]);
            $listCustomers[] = $temp->get();
        }
        $clientsList = new  ClientList($this->database);
        if ($isUserAdmin){
            $listClients = $clientsList->simpleListClients();
        }
        else{
            $listClients = $clientsList->simpleListClients($currentUserData["id"]);
        }

        $prrList = new PRRCompanyList($this->database);

        $listPRRCompany = $prrList->simpleListPRRCompany();


        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }
        if($isUserAdmin){
            $listApplication = $this->database->select(
                'applications',
                ['cancelled' => 0],
                ['id' => 'DESC'],
                -1,
                ['id','application_number']
            );
        }
        else{
            $listApplication = $this->database->select(
                'applications',
                ['id_user' => $currentUser->id(), 'cancelled' => 0],
                ['id' => 'DESC'],
                -1,
                ['id','application_number']
            );
        }


        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Добавление ПРР',
            'listCustomers' => $listCustomers,
            'listApplication' => $listApplication,
            'listTypesTaxation' => $listTypesTaxation,
            'listClients' => $listClients,
            'listPRRCompany' => $listPRRCompany,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
            'activeHeaderItem' => 1,
        ]);

        $this->view('PRR/add');
    }

    public function ajaxAddApplicationPRR()
    {

        $data = $this->request->post;

//        var_dump($data);

        $this->request->validate([
            "weightPrr" => ["required"],
            "weightClient" => ["required"],
            "customerIdPrr" => ["required"],
            "customerIdClient" => ["required"],
            "prrIdPrr" => ["required"],
            "clientIdClient" => ["required"],
            "natureCargoPrr" => ["required"],
            "natureCargoClient" => ["required"],
//            "termsPaymentPrr" => ["required"],
//            "termsPaymentClient" => ["required"],
            "costPrr" => ["required"],
            "costClient" => ["required"],
            "taxationTypePrr" => ["required"],
            "taxationTypeClient" => ["required"],
        ]);

        if (empty($this->request->errors())){
            $prrApplication = new PRRApplication();

            $prrApplication->edit($data);

            $prrApplication->edit(['idUser' => $this->auth->user()->id()]);

//            $userId = $this->auth->user()->get(["id"])["id"];
//
//            $changeHistory = new ApplicationChangeHistory($applicationId, $application, $userId);
//            $changeHistory->saveChangeHistory();

            if ($prrApplication->save()) {


                echo json_encode(['status' => 'Success', 'id' => $prrApplication->get()['id']]);

            }
            else
                echo json_encode(['status' => 'Error']);
        }
        else{
            echo json_encode(['status' => 'Validate Error', 'error' => $this->request->errors()]);;
        }

    }

    public function edit()
    {

        $prrApplication = new PRRApplication(['id' => $this->request->input('id')]);

        $additionalExpensesList = $prrApplication->getAdditionalExpensesList();


        $currentUser = $this->auth->user();
        $currentUserData = $currentUser->get();

        $isUserAdmin = $currentUserData["role"] === 6 || $currentUserData["role"] === 7;

        $listDatabaseCustomers = $this->database->select('customers');
        $listCustomers = [];
        foreach($listDatabaseCustomers as $customer){
            $temp = new Customer(['id'=> $customer['id']]);
            $listCustomers[] = $temp->get();
        }
        $clientsList = new  ClientList($this->database);
        if ($isUserAdmin){
            $listClients = $clientsList->simpleListClients();
        }
        else{
            $listClients = $clientsList->simpleListClients($currentUserData["id"]);
        }

        $prrList = new PRRCompanyList($this->database);

        $listPRRCompany = $prrList->simpleListPRRCompany();


        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }

        if($isUserAdmin){
            $listApplication = $this->database->select(
                'applications',
                ['cancelled' => 0],
                ['id' => 'DESC'],
                -1,
                ['id','application_number']
            );
        }
        else{
            $listApplication = $this->database->select(
                'applications',
                ['id_user' => $currentUser->id(), 'cancelled' => 0],
                ['id' => 'DESC'],
                -1,
                ['id','application_number']
            );
        }

        $listDatabasePlacePRR = $this->database->select(
            'prr_place',
            [
                "id_application" => $prrApplication->id()
            ]
        ) ?? [];


        $listPlacePRR = [];
        foreach($listDatabasePlacePRR as $placePRR){
            $temp = new PRRPlace(['id'=> $placePRR['id']]);
            $listPlacePRR[] = $temp->get();
        }


        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Редактирование ПРР',
            'listCustomers' => $listCustomers,
            'listTypesTaxation' => $listTypesTaxation,
            'listApplication' => $listApplication,
            'listClients' => $listClients,
            'additionalExpensesList' => $additionalExpensesList,
            'listPRRCompany' => $listPRRCompany,
            'isFullCRMAccess' => $this->auth->user()->fullCRM(),
            'activeHeaderItem' => 1,
            'PRRApplication' => $prrApplication->get(),
            'listPlacePRR' => $listPlacePRR,
        ]);

        $this->view('PRR/edit');
    }

    public function ajaxGetTextDescription()
    {
        if(!$this->request->input('id_application')){
            return json_encode(['status' => 'Error']);
        }
        $prrApplication = new PRRApplication(['id' => $this->request->input('id_application')]);
        $prrApplicationData = $prrApplication->get();


        $client = new Client(['id' => $prrApplicationData['client_id_client']]);

        $applicationNumber = $prrApplicationData['application_number'];

        $customers = $this->database->select('customers');

        $customer = $customers[$prrApplicationData['customer_id_prr'] - 1]['name'];

        $prrPlaceList = $prrApplication->getPRRPlaceList();

        $natureCargo = str_replace(['<p>','</p>'],'',$prrApplicationData['nature_cargo_prr']);

        $numberLoaders = $prrApplicationData['number_loaders_prr'];

        $address = '';
        $contactFace = '';
        $dateApplication = '';

        foreach($prrPlaceList as $place){
            if($place['type_for'] == 1){
                $address = $place['city'] .' ' .$place['address'];
                $contactFace = $place['contact'] .' - ' .$place['phone'];
                $dateApplication = $place['date'];

                if($place['time'] != '')
                    $dateApplication .= ' '.$place['time'];
            }

        }


        $text = "Заявка № {$applicationNumber}  {$customer}  <br>
            Дата заявки:  {$dateApplication} <br>
            Характер груза: {$natureCargo} <br>
            Количество грузчиков: {$numberLoaders}  <br>
            Адрес выгрузки: {$address} <br>
            Контактное лицо: {$contactFace}";

        echo json_encode(['status' => true, 'text' => $text]);

    }

    public function addPrrCompany()
    {
        $string_legal_address = '';

        foreach ($this->request->input('info_inputs') as $item) {
            $string_legal_address.= $item.'||';
        }
        $string_legal_address = trim($string_legal_address, "||");

        $form_data = $this->request->input('form_data');


        $errors = [];

        $nameValidation = $this->validator->validate(["name" => $form_data['prr-company']], ["name" => ["required", "min:3"]]);

        if (!$nameValidation)
            $errors += $this->validator->errors();

        $innValidation = $this->validator->validate(["inn" => $form_data['prr-company_inn']], ["inn" => ["required", "min:3"]]);
        if (!$innValidation)
            $errors += $this->validator->errors();

        $legalAddressValidation = $this->validator->validate(["address" => $form_data['prr-company_legal_address']], ["address" => ["required", "min:3"]]);
        if (!$legalAddressValidation)
            $errors += $this->validator->errors();

        if ($nameValidation && $innValidation && $legalAddressValidation) {
            $existenceOrganization = new PRRCompany(['inn' => $form_data['prr-company_inn']]);
            if(! $existenceOrganization->get(['id'])['id'] ) {
                $prrCompany = new PRRCompany();
                $prrCompany->edit([
                    'name' => $form_data['prr-company'],
                    'inn' => $form_data['prr-company_inn'],
                    'legal_address' => $form_data['prr-company_legal_address'],
                    'contact' => $string_legal_address
                ]);

                if ($prrCompany->save()) {
                    echo json_encode(["status" => "Success", "data" => $prrCompany->get()]);
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

    public function ajaxCopyPrrApplication()
    {
        $applicationId = $this->request->input("id");

        $application = new PRRApplication(["id"=> $applicationId]);

        print $application->copy();
    }

    public function ajaxGetPRRCompany()
    {
        print json_encode($this->database->first('prr_company',['id' => $this->request->input('id')]));
    }


    public function ajaxEditApplicationPRR()
    {

        $data = $this->request->post;

        $this->request->validate([
//            "cityPrr" => ["required","min:1"],
//            "cityClient" => ["required"],
//            "addressPrr" => ["required"],
//            "addressClient" => ["required"],
//            "datePrr" => ["required"],
//            "dateClient" => ["required"],
            "placePrr" => ["required"],
            "placeClient" => ["required"],
            "weightPrr" => ["required"],
            "weightClient" => ["required"],
            "numberLoadersPrr" => ["required"],
            "numberLoadersClient" => ["required"],
            "customerIdPrr" => ["required"],
            "customerIdClient" => ["required"],
            "prrIdPrr" => ["required"],
            "clientIdClient" => ["required"],
            "natureCargoPrr" => ["required"],
            "natureCargoClient" => ["required"],
//            "termsPaymentPrr" => ["required"],
//            "termsPaymentClient" => ["required"],
            "costPrr" => ["required"],
            "costClient" => ["required"],
            "taxationTypePrr" => ["required"],
            "taxationTypeClient" => ["required"],
        ]);

        if (empty($this->request->errors())){
            $prrApplication = new PRRApplication(['id' => $this->request->input('id')]);

            $prrApplication->edit($data);


//            $userId = $this->auth->user()->get(["id"])["id"];
//
//            $changeHistory = new ApplicationChangeHistory($applicationId, $application, $userId);
//            $changeHistory->saveChangeHistory();

            if ($prrApplication->save()) {


                echo json_encode(['status' => 'Success', 'id' => $prrApplication->get()['id']]);

            }
            else
                echo json_encode(['status' => 'Error']);
        }
        else{
            echo json_encode(['status' => 'Validate Error', 'error' => $this->request->errors()]);;
        }

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
            'prr_application_document_comment',
            [
                'id_application' => $idApp,
                'name' => $name,
                'side' => $side,
                'num_document' => $numDoc,
            ]
        );

        if(!$commentDocument){
            $this->database->insert(
                'prr_application_document_comment',
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
                'prr_application_document_comment',
                ['comment' => $comment, 'datetime' => date("Y-m-d H:i:s"),
                    'id_user' => $this->auth->user()->id()],
                ['id' => $commentDocument[0]['id']]
            );
            unset($commentDocument[0]['id']);

            print $this->database->insert('prr_application_document_comment_history', $commentDocument[0]);
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

            $fileModel = new File([],'prr_');
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


        $modelChangeApplicationStatus = new PRRApplicationChangeStatus($this->database);

        $modelChangeApplicationStatus->checkPossibleChangeStatus($application_id);

    }

    public function ajaxDeleteFile()
    {
        $id = $this->request->input('id');

        $userId = $this->request->input('user_id');

        $file = $this->database->select("prr_files", ['id' => $id])[0];

        if ($this->database->delete("prr_files", ['id' => $id])) {
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
        $application = new PRRApplication(["id" => $applicationId]);
        $applicationArray = $application->get();

        $applicationNumber = $applicationArray['application_number'];

        $clientId = $applicationArray["client_id_client"];
        $client = new Client(["id" => $clientId]);
        $clientArray = $client->get();

        $taskName = "Выставить счет";

        $createDateTime = date("Y-m-d H:i:s");

        $deadLine = date($createDateTime, strtotime("+1 day"));

        $date_completion = 'No';


        $clientName = $clientArray['name'];
        $clientTaxationType = $applicationArray['taxation_type_client'];
        $clientCost = number_format(
            $applicationArray['cost_client'],
            0,
            ',',
            ' '
        );

        $textCustomer = 'ООО';

        if($applicationArray['customer_id_client'] > 1)
            $textCustomer = 'ИП';

        $placePRR = $application->getPRRPlaceList();

        $placeCity = $placePRR['0']['city'];
        $placeAddress = $placePRR['0']['address'];

        $placeDate = $placePRR['0']['date'];

        $comment = "Заявка № {$applicationNumber} <br>
            ПРОСЬБА ВЫСТАВИТЬ СЧЕТ {$textCustomer}: <br>
            {$clientName} <br>
            {$clientCost} {$clientTaxationType}  <br>
            <br>
            Маршрут: {$placeCity} {$placeAddress}, {$placeDate}";



        $executor_id = 1;

        $status = 0;

        $task = new Task([],'prr_');
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