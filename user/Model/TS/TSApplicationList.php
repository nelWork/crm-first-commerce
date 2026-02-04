<?php

namespace App\User\Model\TS;

use App\Database\DatabaseInterface;
use App\Model\Marshrut\Marshrut;
use App\Model\User\User;
use App\User\Model\Model;

class TSApplicationList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listApplication(int $page = 1, array $conditions = [], array $order = ['id' => 'DESC'], int $limit = 25): array
    {
        $applications = $this->database->superSelect('ts_application',$conditions, $order , $limit,
            [
                'id',
                'date',
                'application_number',
                'id_forwarder',
                'id_driver',
                'application_section',
                'id_user'
            ],
            ($page - 1) * $limit);

        $listApplications = array();


        foreach ($applications as $application) {

            $tempData = [];

            $tempData['id'] = $application['id'];

            $tempData['date'] = date('d.m.Y', strtotime($application['date']));

            $tempData['application_number'] = $application['application_number'];

            $tempData['id_forwarder'] = $this->database->first(
                'forwarders',
                ['id' => $application['id_forwarder']],['name']
            )['name'];

            $driver = $this->database->first(
                'drivers',
                ['id' => $application['id_driver']],['name','phone']
            );
            $tempData['driver'] =  $driver['name'];
            $tempData['driver_phone'] =  $driver['phone'];

//            $tempData['application_section'] = $application['application_section'];

            $user = new User(['id' => $application['id_user']]);

            $tempData['user']['name'] = $user->fullName();
            $tempData['user']['avatar'] = $user->avatar();

            $listRoutes = $this->database->select(
                'routes_ts',
                [
                    'id_application' => $application['id']
                ],
                ['sort' => 'ASC'],
                -1,
                ['id']
            );

            foreach($listRoutes as $route){
                $marshrut = new Marshrut(['id' => $route['id']],true);
                $tempData['routes'][] = $marshrut->get();

            }


            $tempData['comments'] = [];
//            $comments = $this->database->select('comments_application', ['id_application' => $application['id'], 'visible' => 1, 'type_comment' => 0]);
//
//            if ($comments)
//                $tempData['comments'] =  $comments;

//            $tempData['client'] = $this->database->first(
//                'clients',
//                ['id' => $application['client_id_Client']],['name']
//            )['name'];

//            $driver = $this->database->first(
//                'drivers',
//                ['id' => $application['driver_id_Carrier']],['name','phone']
//            );
//            $tempData['driver'] =  $driver['name'];
//            $tempData['driver_phone'] =  $driver['phone'];

//            $tempData['route_client'] = [];
//            $tempData['route_carrier'] = [];

            $tempData['application_status'] = 'Ожидается счет';
            $tempData['client_payment_status'] = 'Ожидается счет';
            $tempData['carrier_payment_status'] = 'Ожидается счет';
            $tempData['client_documents_status'] = 'Ожидается счет';
            $tempData['carrier_documents_status'] = 'Ожидается счет';
//
//
            switch ($application['application_section']) {
                case 1:
                    $tempData['application_status'] = 'Актуальная';
                    break;
                case 2:
                    $tempData['application_status'] = 'Завершенная';
                    break;
                case 3:
                    $tempData['application_status'] = 'Закрытая под расчет';
                    break;
                case 4:
                    $tempData['application_status'] = 'Срыв';
                    break;
                case 5:
                    $tempData['application_status'] = 'Отмененная';
                    break;
                case 6:
                    $tempData['application_status'] = 'Закрытая под документы';
                    break;
            }
//
//            $listRoutes = $this->database->select(
//                'routes',
//                ['id_application' => $application['id']],
//                ['sort' => 'ASC'],
//                -1,
//                ['id']
//            );
//
//            foreach($listRoutes as $route){
//                $marshrut = new Marshrut(['id' => $route['id']]);
//                $route = $marshrut->get();
//
//                if($route['type_for'] == 1){
//                    $tempData['route_client'][] = $route;
//                }
//                else{
//                    $tempData['route_carrier'][] = $route;
//                }
//            }
//
//            $user = new User(['id' => $application['id_user']]);
//
//            $tempData['user']['name'] = $user->fullName();
//            $tempData['user']['avatar'] = $user->avatar();



            $listApplications[] = $tempData;
        }

        return $listApplications;
    }

    public function countPage(int $elementsOnPage = 20, array $conditions = [])
    {
        $elements = $this->database->superSelect('ts_application', $conditions, [] , -1, ['COUNT(*)']);

        return ceil($elements[0]['COUNT(*)'] / $elementsOnPage);
    }
}