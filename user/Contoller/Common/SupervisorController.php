<?php

namespace App\User\Contoller\Common;

use App\Model\Search\SearchApplication;
use App\Model\User\User;
use App\User\Contoller\Controller;
use App\User\Model\Application\ApplicationList;
use App\User\Model\Carrier\CarrierList;
use App\User\Model\Client\ClientList;
use App\User\Model\User\UserList;

class SupervisorController extends Controller
{
    public function index()
    {
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
        }

        if(is_array($this->request->input('manager'))){
            foreach ($this->request->input('manager') as $item){
                $link .= '&manager%5B%5D=' .$item;
                $condition['id_user'][] = $item;
                $conditionFilter['manager'][] = $item;
            }

        }

        if(! $isFullCRMAccess) {
            $userLogist = new User(['id' => $activeUser->id()]);

            if(count($userLogist->getSubordinatesList()) > 1){
                $condition['id_user'] = [];

                $conditionFilter['id_user'][] = $activeUser->id();
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
            $searchSelect = $this->request->input('search-select');
            $searchModel = new SearchApplication($searchSelect);

            $link .= '?search-select=' . $searchSelect . '&search=' . $searchText;

            $listSearch = $searchModel->search($searchText);

            $searchData['search-select'] = $searchSelect;
            $searchData['search'] = $searchText;
        }


        if($this->request->input('application-section-journal') == 6){
            $condition['application_section_journal'] = 6;
            $conditionFilter['application-section-journal'] = true;
        }
        else{
            $condition['application_section_journal'] = [1,2,3,4,5];
            $conditionFilter['application-section-journal'] = false;
        }

        if(count($listSearch) > 0){
            $condition['id'] = $listSearch;
        }


        $condition['client_id_Client'] = 310;

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = $this->session->get('countElementsPage') ?? 25;

        $carrierListModel = new CarrierList($this->database);
        $clientListModel = new ClientList($this->database);

        $listClients = $clientListModel->simpleListClients();

        if(! $isFullCRMAccess)
            $listClients = $clientListModel->simpleListClients($activeUser->id());

        $this->extract([
            'controller'=> $this,
            'titlePage' => 'Перевозки для себя',
            'applicationList' => $applicationListModel->listApplication($page, $condition, $order,$elementsPage),
            'userList' => $userListModel->listManager(),
            'carrierList' => $carrierListModel->simpleListCarriers(),
            'clientList' => $listClients,
            'condition' => $conditionFilter,
            'countPage' => $applicationListModel->countPage($elementsPage, $condition),
            'searchData' => $searchData,
            'elementsPage' => $elementsPage,
            'page' => $page,
            'link' => $link,
            'isFullCRMAccess' => $isFullCRMAccess,
            'activeHeaderItem' => 8
        ]);

        $this->view('Supervisor/applications-list');
    }

    public function ajaxGetCommentCompleteSupervisorApplication()
    {
        $idApplication = $this->request->input('id');

        $comment = $this->database->select('comments_application_compete_supervisor',['id_application' => $idApplication]);

        if($comment) {
            $idUser = $comment[0]['id_user'];

            $userComment = new User(['id' => $idUser]);

            $comment[0]['datetime'] = date('d.m.Y H:i', strtotime($comment[0]['datetime']));

            print json_encode(['result' => true, 'comment' => $comment[0],
                'user' => $userComment->get(['name', 'lastname'])]);
        }
        else
            print json_encode(['result' => false]);
    }

    public function ajaxCompleteApplicationSupervisor()
    {
        $idApplication = $this->request->input('id');
        $comment = $this->request->input('comment');
        $idUser = $this->auth->user()->id();

        $updateApplication = $this->database->update(
            'applications',
            ['application_section_journal' => 6,
                'application_closed_date' => date('Y-m-d'),
                'application_closed_document_date' => date('Y-m-d')],
            [
                'id' => $idApplication
            ]
        );

        $insertComment = $this->database->insert(
            'comments_application_compete_supervisor',
            [
                'id_application' => $idApplication,
                'comment' => $comment,
                'id_user' => $idUser,
                'datetime' => date('Y-m-d H:i:s')
            ]
        );

        if($updateApplication && $insertComment){
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }

    }

}