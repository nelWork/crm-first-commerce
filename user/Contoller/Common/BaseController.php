<?php

namespace App\User\Contoller\Common;


use App\Config\Config;
use App\Config\ConfigInterface;
use App\Model\Carrier\Carrier;
use App\Model\Client\Client;
use App\Model\ClientInfo\ClientInfo;
use App\Model\ContactFace\ContactFace;
use App\Model\Notification\Notification;
use App\Model\Search\SearchApplication;
use App\Model\Search\SearchCarrier;
use App\Model\Search\SearchClient;
use App\Model\TypeTaxation\TypeTaxation;
use App\User\Contoller\Controller;
use App\User\Model\Application\ApplicationList;
use App\User\Model\Base\Base;
use App\User\Model\Base\BaseDocuments;
use App\User\Model\User\UserList;
use App\Model\User\User;


class BaseController extends Controller
{
    private ConfigInterface $config;

    public function updateQuantityApplicationsClient(bool $clientsPage = false)
    {
        
        if($clientsPage){
            $clientDB = $this->database->select('clients');
        }
        else
            $clientDB = $this->database->select('clients', ['quantity_applications' => 0]);


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
    public function clientsPage()
    {
        if($this->auth->user()->id() == 7)
            $this->redirect->to('/');

        $model = new Base($this->database);

        $this->updateQuantityApplicationsClient(true);

        $activeUser = $this->auth->user();
        $isFullCRMAccess = $activeUser->fullCRM();

        $link = '';

        if($this->request->input('order'))
            $order = ['id' => $this->request->input('order')];
        else
            $order = ['id' => 'DESC'];

        $condition = [];
        $conditionFilter = ['taxation' => [],'format_work' => ''];

        $listSearch = [];
        $searchData = ['search-select' => '', 'search' => ''];


        if($this->request->input('search') AND $this->request->input('search-select')){
            $searchText = $this->request->input('search');
            $searchSelect = $this->request->input('search-select');
            $searchModel = new SearchClient($searchSelect);

            $link .= '?search-select=' . $searchSelect . '&search=' . $searchText;

            $listSearch = $searchModel->search($searchText);

            $searchData['search-select'] = $searchSelect;
            $searchData['search'] = $searchText;
        }

        if(count($listSearch) > 0){
            $condition['id'] = $listSearch;
        }

        if(! $isFullCRMAccess){
            $condition['users_access'] = '%|' .$activeUser->id() .'|%';
        }

        if($this->request->input('taxation') != '' AND $this->request->input('taxation') != 'null'){
            $link .= '&taxation=' .$this->request->input('taxation');
            $condition['type_taxation_id'] = $this->request->input('taxation');
            $conditionFilter['taxation'] = $this->request->input('taxation');

        }

        if($this->request->input('format-work') != '' AND $this->request->input('format-work') != 'null'){
            $link .= '&format-work=' .$this->request->input('format-work');
            $condition['format_work'] = $this->request->input('format-work');
            $conditionFilter['format_work'] = $this->request->input('format-work');

        }

//        dd($conditionFilter);

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = $this->session->get('countElementsPage') ?? 25;

        $condition['in_work'] = 1;


        //dd($condition);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'База клиентов',
            'listClients' => $model->getClientsList($page, $condition, $order,$elementsPage),
            'countPage' => $model->countPageClients($elementsPage, $condition),
            'page' => $page,
            'link' => $link,
            'condition' => $conditionFilter,
            'searchData' => $searchData,
            'elementsPage' => $elementsPage,
            'isFullCRMAccess' => $isFullCRMAccess,
            'activeHeaderItem' => 4,
        ]);

        $this->view('Base/clients');
    }



    public function carriersPage()
    {
        if($this->auth->user()->id() == 7)
            $this->redirect->to('/');

        $model = new Base($this->database);

        $link = '';

        if($this->request->input('order'))
            $order = ['id' => $this->request->input('order')];
        else
            $order = ['id' => 'DESC'];

        $condition = [];
        $conditionFilter = ['taxation' => []];

        $listSearch = [];
        $searchData = ['search-select' => '', 'search' => ''];


        if($this->request->input('search') AND $this->request->input('search-select')){
            $searchText = $this->request->input('search');
            $searchSelect = $this->request->input('search-select');
            $searchModel = new SearchCarrier($searchSelect);

            $link .= '?search-select=' . $searchSelect . '&search=' . $searchText;

            $listSearch = $searchModel->search($searchText);

            $searchData['search-select'] = $searchSelect;
            $searchData['search'] = $searchText;
        }

        if(count($listSearch) > 0){
            $condition['id'] = $listSearch;
        }

        if(is_array($this->request->input('manager'))){
            foreach ($this->request->input('manager') as $item){
                $link .= '&manager=' .$item;
                $condition['id_user'][] = $item;
                $conditionFilter['manager'][] = $item;
            }
        }

        if($this->request->input('taxation') != '' AND $this->request->input('taxation') != 'null'){
            $link .= '&taxation=' .$this->request->input('taxation');
            $condition['type_taxation_id'] = $this->request->input('taxation');
            $conditionFilter['taxation'] = $this->request->input('taxation');

        }

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = $this->session->get('countElementsPage') ?? 25;

        $this->extract([
            'controller' => $this,
            'titlePage' => 'База перевозчиков',
            'listCarriers' => $model->getCarriersList($page, $condition, $order,$elementsPage),
            'countPage' => $model->countPageCarriers($elementsPage, $condition),
            'page' => $page,
            'link' => $link,
            'condition' => $conditionFilter,
            'searchData' => $searchData,
            'elementsPage' => $elementsPage,
            'activeHeaderItem' => 4
        ]);

        $this->view('Base/carriers');
    }

    public function carrierPage(){

        if($this->auth->user()->id() == 7)
            $this->redirect->to('/');

        $carrier_id = $this->request->input('id');
        $carrier = new Carrier(['id'=> $carrier_id]);

        $dataCarrier = $carrier->get();

        if(! $dataCarrier['id'])
            $this->redirect->to('/base/carriers');

        $listApplications = $this->database->select('applications',
            ["carrier_id_Carrier" => $carrier_id],[],-1,['id','application_number','date']);

        $listContactFaces = explode('||', $dataCarrier['info']);

        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Перевозчик',
            'carrier' => $dataCarrier,
            'listApplications' => $listApplications,
            'listTypesTaxation' => $listTypesTaxation,
            'listContactFaces' => $listContactFaces
        ]);

        $this->view('Base/carrier');
    }

    public function ajaxExcelClientList()
    {
        $listClientsDB  = $this->database->select('clients',[],[],-1,['id']);

        $listClients = [];

        foreach($listClientsDB as $client){
            $clientModel = new Client(['id' => $client['id']]);
            $clientData = $clientModel->get();

            $listClients[] = [
                'users' => $clientModel->getManagersAccess(),
                'name' => $clientData['name'],
                'inn' => $clientData['inn'],
                'format_work' => $clientData['format_work'],
                'payment_deferment' => $clientData['payment_deferment'],
                'quantity' => $clientModel->countApplication()
            ];
        }

        $baseDoc = new BaseDocuments();

        $baseDoc->createListClientExcel($listClients);

        print json_encode(['status' => true,'link_file' => '/doc/list-clients.xlsx']);
    }

    public function clientPage(){
        if($this->auth->user()->id() == 7)
            $this->redirect->to('/');

        $client_id = $this->request->input('id');
        $client = new Client(['id'=> $client_id]);

        if(! $client->get()['id'])
            $this->redirect->to('/base/clients');

        $documents = $this->database->select("files", ["client_id" => $client_id]);

        $listDatabaseContactFaces = $this->database->select('contact_faces', ['client_id' => $client_id]);
        $listContactFaces = [];
        foreach ($listDatabaseContactFaces as $contactFace){
            $temp = new ContactFace(['id'=> $contactFace['id']]);
            $listContactFaces[] = $temp->get();
        }

        $clientInfo = new ClientInfo(['client_id' => $client_id]);
        $clientInfoArray = $clientInfo->get();

        $listDatabaseApplications = $this->database->select(
            'applications',
            [
                "client_id_Client" => $client_id,
                'cancelled' => 0
            ],
            ['id' => 'DESC'],
            -1,
            ['id','date','application_number']

        );
        $listApplications = [];
        foreach ($listDatabaseApplications as $application){
            $listApplications[] = [
                'application_id' => $application['id'],
                'application_date' => $this->format_date($application['date'], "d.m.Y"),
                'application_number' => $application['application_number'],
            ];
        }

        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }

        $userListModel = new UserList($this->database);

        $listManager = $userListModel->listManager();

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Клиент',
            'client' => $client->get(),
            'listContactFaces' => $listContactFaces,
            'documents' => $documents,
            'clientInfo' => $clientInfoArray,
            'listApplications' => $listApplications,
            'listTypesTaxation' => $listTypesTaxation,
            'listManager' => $listManager,

        ]);

        $this->view('Base/client');
    }

    public function ajaxClientChangeManager()
    {

        $idClient = $this->request->input('id');

        $client = new Client(['id'=> $idClient]);

        $newUserAccess = '';

        foreach ($this->request->input('val') as $item){
            $newUserAccess .= '|' .$item .'|,';
        }

        $client->edit(['usersAccess' => $newUserAccess]);

        if ($client->save())
            print json_encode(['result' => true]);
        else
            print json_encode(['result' => false]);
    }

    public function ajaxEditClient()
    {
        $data = $this->request->post;

        $client = new Client(["id" => $data["id"]]);
        $client->edit($data);

        if ($client->save()){
            echo "Success";
        }
        else{
            echo "Error";
        }
    }

    public function ajaxDeleteClient()
    {
        $id = $this->request->input('id');

        if ($this->database->delete("clients", ["id" => $id])) {
            echo "Success";
        }
        else{
            echo "Error";
        }
    }

    public function ajaxChangeClientInfo()
    {
        $input = $this->request->input('input');
        $value = $this->request->input('value');
        $clientId = $this->request->input('client_id');

        $clientInfo = new ClientInfo(['client_id' => $clientId]);
        if(! $clientInfo->get()['id']){
            $clientInfo->edit(['client_id' => $clientId]);
        }

        $clientInfo->edit(["$input" => $value]);
        var_dump($clientInfo->get());
        $clientInfo->save();
    }
    public function ajaxAddContactFace()
    {
        $contactFace = new ContactFace();
        $contactFace->edit($this->request->post);

        if ($contactFace->save()){
            echo json_encode($contactFace->get(), true);
        }
        else{
            echo "Error";
        }
    }

    public function ajaxDeleteContactFace()
    {
        $id = $this->request->input('id');

        if ($this->database->delete('contact_faces', ['id' => $id])) {
            echo "Success";
        }
        else{
            echo "Error";
        }
    }


    // public function callBasesPage()
    // {
    //     $this->config = new Config();

    //     $userData = $this->auth->user()->get(['id', 'role']);

    //     $userId = $userData['id'];
    //     $userRole = $userData['role'];

    //     $usersList = $this->database->select('users', [], [], -1, ['id','name']);;

    //     $this->extract([
    //         'controller' => $this,
    //         'titlePage' => 'База обзвонов',
    //         'userId' => $userId,
    //         'userRole' => $userRole,
    //         'usersList' => $usersList,
    //         'activeHeaderItem' => 4
    //     ]);

    //     $this->view('Base/call-bases');
    // }


    public function callBasesPage()
    {
        // $this->config = new Config();

        // $userData = $this->auth->user()->get(['id', 'role']);

        // $userId = $userData['id'];
        // $userRole = $userData['role'];

        // $usersList = $this->database->select('users', [], [], -1, ['id','name']);

        // $listCallBase = $this->database->select('base_calls');

        // $this->extract([
        //     'controller' => $this,
        //     'titlePage' => 'База обзвонов',
        //     'isFullCRMAccess' => true,
        //     'listCallBase' => $listCallBase,
        //     'activeHeaderItem' => 4
        // ]);

    

        $model = new Base($this->database);

        $this->updateQuantityApplicationsClient(true);

        $activeUser = $this->auth->user();
        $isFullCRMAccess = $activeUser->fullCRM();

        $link = '';

        if($this->request->input('order'))
            $order = ['id' => $this->request->input('order')];
        else
            $order = ['id' => 'DESC'];

        $condition = [];
        $conditionFilter = ['taxation' => [],'format_work' => ''];

        $listSearch = [];
        $searchData = ['search-select' => '', 'search' => ''];


        if($this->request->input('search') AND $this->request->input('search-select')){
            $searchText = $this->request->input('search');
            $searchSelect = $this->request->input('search-select');
            $searchModel = new SearchClient($searchSelect);

            $link .= '?search-select=' . $searchSelect . '&search=' . $searchText;

            $listSearch = $searchModel->search($searchText);

            $searchData['search-select'] = $searchSelect;
            $searchData['search'] = $searchText;
        }

        if(count($listSearch) > 0){
            $condition['id'] = $listSearch;
        }

        if(! $isFullCRMAccess){
            $condition['users_access'] = '%|' .$activeUser->id() .'|%';
        }

        if($this->request->input('taxation') != '' AND $this->request->input('taxation') != 'null'){
            $link .= '&taxation=' .$this->request->input('taxation');
            $condition['type_taxation_id'] = $this->request->input('taxation');
            $conditionFilter['taxation'] = $this->request->input('taxation');

        }

        if($this->request->input('format-work') != '' AND $this->request->input('format-work') != 'null'){
            $link .= '&format-work=' .$this->request->input('format-work');
            $condition['format_work'] = $this->request->input('format-work');
            $conditionFilter['format_work'] = $this->request->input('format-work');

        }

//        dd($conditionFilter);

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = $this->session->get('countElementsPage') ?? 25;

        $condition['in_work'] = 0;


        $this->extract([
            'controller' => $this,
            'titlePage' => 'База обзвона',
            'listClients' => $model->getClientsList($page, $condition, $order,$elementsPage),
            'countPage' => $model->countPageClients($elementsPage, $condition),
            'page' => $page,
            'link' => $link,
            'condition' => $conditionFilter,
            'searchData' => $searchData,
            'elementsPage' => $elementsPage,
            'isFullCRMAccess' => $isFullCRMAccess,
            'activeHeaderItem' => 4,
        ]);


        $this->view('Base/call-bases-new');
    }


    public function baseCallPage(){
        if($this->auth->user()->id() == 7)
            $this->redirect->to('/');

        $client_id = $this->request->input('id');
        $client = new Client(['id'=> $client_id], 'base_calls');

        if(! $client->get()['id'])
            $this->redirect->to('/base/call-bases');

        $documents = $this->database->select("files", ["client_id" => $client_id]);

        $listDatabaseContactFaces = $this->database->select('contact_faces', ['client_id' => $client_id]);

        $listContactFaces = [];
        foreach ($listDatabaseContactFaces as $contactFace){
            $temp = new ContactFace(['id'=> $contactFace['id']]);
            $listContactFaces[] = $temp->get();
        }

        $clientInfo = new ClientInfo(['client_id' => $client_id]);
        $clientInfoArray = $clientInfo->get();

        $listDatabaseApplications = $this->database->select(
            'applications',
            [
                "client_id_Client" => $client_id,
                'cancelled' => 0
            ],
            ['id' => 'DESC'],
            -1,
            ['id','date','application_number']

        );
        $listApplications = [];
        foreach ($listDatabaseApplications as $application){
            $listApplications[] = [
                'application_id' => $application['id'],
                'application_date' => $this->format_date($application['date'], "d.m.Y"),
                'application_number' => $application['application_number'],
            ];
        }

        $listDatabaseTypesTaxation = $this->database->select('type_taxation');
        $listTypesTaxation = [];
        foreach($listDatabaseTypesTaxation as $typeTaxation){
            $temp = new TypeTaxation(['id'=> $typeTaxation['id']]);
            $listTypesTaxation[] = $temp->get();
        }

        $userListModel = new UserList($this->database);

        $listManager = $userListModel->listManager();

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Клиент',
            'client' => $client->get(),
            'listContactFaces' => $listContactFaces,
            'documents' => $documents,
            'clientInfo' => $clientInfoArray,
            'listApplications' => $listApplications,
            'listTypesTaxation' => $listTypesTaxation,
            'listManager' => $listManager,

        ]);

        $this->view('Base/client');
    }

    public function ajaxAddClientBaseCalls(){
        $form_data = $this->request->post;


        $this->request->validate([
            "type_taxation_id" => ["required"],
            "format_work" => ['required'],
            "application_format" => ["min:1"],
            "client_name" => ["required", "min:3"],
            "client_inn" => ["required", "min:3"],
            "client_legal_address" => ["required", "min:3"],
            "client_full_name" => ["required", "min:3"],
            // "client_job_title" => ["required", "min:3"],
            "client_phone" => ["required", "min:3"],
            "client_email" => ["required", "min:3", "email"],
        ]);

        if (empty($this->request->errors())){
            $existenceOrganization = new Client(['inn' => $form_data['client_inn']]);

            if(! $existenceOrganization->get(['id'])['id'] ) {
                $client = new Client([],);
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
                    'users_access' => "|" . $this->auth->user()->get(["id"])["id"] . "|",
                    'in_work' => 0
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


    public function ajaxClientLoadComment(){
        $applicationId = $this->request->input("id_client");

        $user = $this->auth->user();
        $userId = $user->id();

        $commentsDB = $this->database->select(
            'clients_comments',
            [
                'id_client' => $applicationId,
                'visible' => 1
            ],
            [
                'important' => 'DESC',
                'id' => 'DESC'
            ],
            -1 ,
            ['*']
        ) ?? [];


        $listComment = [];

        foreach($commentsDB as $comment){
           
            $user = new User(['id' => $comment['id_user']]);


            $comment['user_data'] = [
                'name' => $user->get()['name'] . ' ' . $user->get()['surname'],
                'avatar' => $user->avatar(),
                'role' => $user->getRole()
            ];

            $comment['date_time'] = date('d.m.Y', strtotime($comment['datetime_created']));

            if($user->fullCRM() and in_array($user->get()['role'], [7,6,3]))
                $comment['edit_access'] = true;
            else{
                if($comment['id_user'] == $userId)
                    $comment['edit_access'] = true;
                else
                    $comment['edit_access'] = false;
            }


            $listComment[] = $comment;
        }

        print json_encode($listComment);
    }


    public function ajaxClientAddComment(){
        $clientId = $this->request->input("id_client");
        $commentText = $this->request->input("comment");

        $userId = $this->auth->user()->id();

        if($commentText == ''){
            print json_encode(['result' => false]);
            return false;
        }

        $newComment = $this->database->insert(
            'clients_comments',
            [
                'id_client' => $clientId,
                'comment' => $commentText,
                'id_user' => $userId,
                'datetime_created' => date('Y-m-d H:i:s')
            ]
        );


        if($newComment){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }

    public function ajaxClientEditComment(){
        $clientId = $this->request->input("id_client");
        $commentText = $this->request->input("comment");

        $commentId = $this->request->input('comment_id');

        $userId = $this->auth->user()->id();

        $updateComment = $this->database->update(
            'clients_comments',
            [
                'comment' => $commentText,
            ],
            [
                'id_client' => $clientId,
                'id' => $commentId
            ]
        );


        if($updateComment){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }

    public function ajaxClientChangeImportantComment(){
        $commentId = $this->request->input('id');

        $comment = $this->database->first('clients_comments',['id' => $commentId]);

        if($comment['important'] == 0){
            print json_encode([
                'result' => $this->database->update('clients_comments',['important' => 1],['id' => $commentId])
            ]);
        }
        else{
            print json_encode([
                'result' => $this->database->update('clients_comments',['important' => 0],['id' => $commentId])
            ]);
        }
    }

    public function ajaxClientChangeInWork(){
        $idClient = $this->request->input('idClient');

        print $this->database->update('clients',['in_work' => 1],['id' => $idClient]);
    }

    public function configGet($key)
    {
        return $this->config->get($key);
    }
}