<?php

namespace App\User\Contoller\Common;

use App\Model\Application\Application;
use App\Model\Client\Client;
use App\Model\User\User;
use App\User\Contoller\Controller;
use App\User\Model\Carrier\CarrierList;
use App\User\Model\Client\ClientList;
use App\User\Model\User\UserList;

class HistoryController extends Controller
{
    public function index(){

        $page = 1;

        if($this->request->input('page') > 1)
            $page = $this->request->input('page');

        $elementsPage = $this->session->get('countElementsPage') ?? 25;


        $changes = $this->database->select(
            "changes",
            [],
            ['id' => 'DESC'],
            $elementsPage,
            ['*'],
            ($page - 1) * $elementsPage
        );


        $userListModel = new UserList($this->database);

        $carrierListModel = new CarrierList($this->database);
        $clientListModel = new ClientList($this->database);

        $listClients = $clientListModel->simpleListClients();

        $condition = [];
        $conditionFilter = ['manager' => [], 'client' => '', 'carrier' => '', 'date' => ''];

        $data = [];
        foreach($changes as $change){
            $application = new Application(["id" => $change["application_id"]]);

            $applicationData = $application->get(["applicationNumber", "date", "idUser", "id", "clientIdClient"]);

            $applicationDateInt = strtotime($applicationData["date"]);
            $applicationDateString = date("d.m.Y", $applicationDateInt);

            $manager = new User(["id" => $applicationData["id_user"]]);
            $managerData = $manager->get(["name", "img_avatar"]);

            $managerName = $managerData["name"];
            $managerAvatarLink = $managerData["img_avatar"];
            if ($managerName === ""){
                $managerName = "Admin";
            }
            if ($managerAvatarLink === ""){
                $managerAvatarLink = "assets/img/empty_avatar.png";
            }

            $client = new Client(["id" => $applicationData["client_id_Client"]]);
            $clientData = $client->get(["name"]);

            $marshruts = $this->database->select("routes", ["id_application" => $change["application_id"]]);

            $firstMarshrut = ["sort" => 1000];
            $lastMarshrut = ["sort" => -1];

            $datetime = strtotime($change["datetime"]);
            $datetimeFormated = date("d.m.Y", $datetime)."<span class ='gray'>".date("H:i", $datetime)."</span>";
            // dd($marshruts);
            foreach ($marshruts as $marshrut){
                if($marshrut["direction"] == "1"){
                    if ($marshrut["sort"] < $firstMarshrut["sort"]){
                        $firstMarshrut = $marshrut;
                    }
                }
                elseif($marshrut["direction"] == "0"){
                    if ($marshrut["sort"] > $lastMarshrut["sort"]){
                        $lastMarshrut = $marshrut;
                    }
                }
            }

            $marshrutString = $firstMarshrut["city"]." - ".$lastMarshrut["city"];

            $data[] = [
                "id" => $applicationData["id"],
                "application_number" => $applicationData["application_number"],
                "application_date" => $applicationDateString,
                "manager" => [
                    "name" => $managerName,
                    "avatar" => $managerAvatarLink
                ],
                "marshrut" => $marshrutString,
                "client_name" => $clientData["name"],
                "change_datetime" => $datetimeFormated
            ];
        }

        $link = '?';

        $this->extract([
            'controller' => $this,
            'titlePage' => 'История изменений',
            'data' => $data,
            'userList' => $userListModel->listUsers(),
            'carrierList' => $carrierListModel->simpleListCarriers(),
            'clientList' => $listClients,
            'condition' => $conditionFilter,
            'activeHeaderItem' => 3,
            'countPage' => $this->countPage($elementsPage, $condition),
            'elementsPage' => $elementsPage,
            'page' => $page,
            'link' => $link,
        ]);

        $this->view('History/index');
    }

    public function countPage($elementsPage, $condition)
    {
        $elements = $this->database->superSelect('changes', $condition, [] , -1, ['COUNT(*)']);

        return ceil($elements[0]['COUNT(*)'] / $elementsPage);
    }
}