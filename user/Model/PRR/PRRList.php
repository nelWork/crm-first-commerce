<?php

namespace App\User\Model\PRR;

use App\Database\DatabaseInterface;
use App\Model\PRR\PRRPlace;
use App\Model\User\User;
use App\User\Model\Model;

class PRRList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listPRR(int $page = 1, array $conditions = [], array $order = ['id' => 'DESC'], int $limit = 25): array
    {

//        dd($conditions);
        $prrDB = $this->database->superSelect(
            'prr_application',
            $conditions,
            $order ,
            $limit,
            ['*'],
            ($page - 1) * $limit
        );

//        dd($prrDB);

        $listPRR = array();


        foreach ($prrDB as $prr) {

            $tempData = [];

            $tempData['id'] = $prr['id'];

            $tempData['date'] = date('d.m.Y', strtotime($prr['date']));

            $tempData['application_number'] = $prr['application_number'];

            $tempData['client'] = $this->database->first(
                'clients',
                ['id' => $prr['client_id_Client']],['name']
            )['name'];
            $tempData['prr'] = $this->database->first(
                'prr_company',
                ['id' => $prr['prr_id_Prr']],['name']
            )['name'];


            $tempData['chosen_contact_Prr'] = $prr['chosen_contact_Prr'];

            $tempData['nature_cargo'] = $prr['nature_cargo_Client'];

            $tempData['number_loaders'] = 'Кол-во человек: ' . $prr['number_loaders_Client'];
            $tempData['weight'] = 'Вес: ' . $prr['weight_Client'];

            $user = new User(['id' => $prr['id_user']]);

            $tempData['user']['name'] = $user->fullName();
            $tempData['user']['avatar'] = $user->avatar();



            $listPlacePRR = $this->database->select(
                'prr_place',
                ['id_application' => $prr['id']],
                ['sort' => 'ASC'],
                -1,
                ['id']
            );
            $tempData['place_client'] = [];
            $tempData['place_prr'] = [];
            foreach($listPlacePRR as $placePrr){
                $placeModel = new PRRPlace(['id' => $placePrr['id']]);
                $placeData = $placeModel->get();

                if($placeData['type_for'] == 1){
                    $tempData['place_client'][] = $placeData;
                }
                else{
                    $tempData['place_prr'][] = $placeData;
                }

            }


            $listPRR[] = $tempData;
        }
        
        //dd($listPRR);

        return $listPRR;
    }

    public function countPage(int $elementsOnPage = 20, array $conditions = [])
    {
        $elements = $this->database->superSelect('prr_application', $conditions, [] , -1, ['COUNT(*)']);

        return ceil($elements[0]['COUNT(*)'] / $elementsOnPage);
    }
}