<?php

namespace App\User\Model\Application;

use App\Database\DatabaseInterface;
use App\Model\Marshrut\Marshrut;
use App\Model\User\User;
use App\User\Model\Model;

class ApplicationList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listApplication(int $page = 1, array $conditions = [], array $order = ['id' => 'DESC'], int $limit = 25): array
    {
        $customers = $this->database->select('customers',[],[],-1,['id','name']);


        $applications = $this->database->superSelect('applications',$conditions, $order , $limit,
            [
                'id',
                'application_title_Carrier',
                'date',
                'client_id_Client',
                'carrier_id_Carrier',
                'driver_id_Carrier',
                'id_user',
                'customer_id_Client',
                'transportation_cost_Client',
                'transportation_cost_Carrier',
                'actual_payment_Client',
                'actual_payment_Carrier',
                'account_number_Client',
                'taxation_type_Client',
                'taxation_type_Carrier',
                'application_status',
                'client_payment_status',
                'carrier_payment_status',
                'client_documents_status',
                'carrier_documents_status',
                'application_number',
                'application_number_client',
                'application_walrus',
                'application_section_journal',
                'manager_share'
            ],
            ($page - 1) * $limit);

        $listApplications = array();


        foreach ($applications as $application) {
            $tempData = [];

            $tempData['id'] = $application['id'];

            $tempData['transportation_cost_client'] = number_format($application['transportation_cost_Client'],0,'',' ');
            $tempData['transportation_cost_carrier'] = number_format($application['transportation_cost_Carrier'],0,'',' ');

            $tempData['actual_payment_carrier'] = number_format($application['actual_payment_Carrier'],0,'',' ');
            $tempData['actual_payment_client'] = number_format($application['actual_payment_Client'],0,'',' ');

            $tempData['taxation_type_client'] = $application['taxation_type_Client'];
            $tempData['taxation_type_carrier'] = $application['taxation_type_Carrier'];

            $tempData['account_number_client'] = $application['account_number_Client'];


            $tempData['customer'] = $customers[$application['customer_id_Client'] - 1]['name'];

            $tempData['application_number'] = $application['application_number'];

            if($application['application_number_client'] != ''){
                $tempData['application_number'] .= '/' . $application['application_number_client'];
            }
            $tempData['name'] = $application['application_title_Carrier'];
            $tempData['date'] = date('d.m.Y', strtotime($application['date']));
            $tempData['application_walrus'] = $application['application_walrus'];
            $tempData['manager_share'] = $application['manager_share'];
            $tempData['comments'] = [];

            $comments = $this->database->select('comments_application', ['id_application' => $application['id'], 'visible' => 1, 'type_comment' => 0]);


            if ($comments)
                $tempData['comments'] =  $comments;

            $client = $this->database->first(
                'clients',
                ['id' => $application['client_id_Client']],['name','inn']
            );

            $carrier = $this->database->first(
                'carriers',
                ['id' => $application['carrier_id_Carrier']],
                ['name','inn']
            );

            $tempData['client'] = $client['name'];
            $tempData['client_inn'] = $client['inn'];

            $tempData['carrier'] = $carrier['name'];
            $tempData['carrier_inn'] = $carrier['inn'];

            $driver = $this->database->first(
                'drivers',
                ['id' => $application['driver_id_Carrier']],['name','phone']
            );
            $tempData['driver'] =  $driver['name'];
            $tempData['driver_phone'] =  $driver['phone'];
            $tempData['route_client'] = [];
            $tempData['route_carrier'] = [];

            $tempData['application_status'] = $application['application_status'];
            $tempData['client_payment_status'] = $application['client_payment_status'];
            $tempData['carrier_payment_status'] = $application['carrier_payment_status'];
            $tempData['client_documents_status'] = $application['client_documents_status'];
            $tempData['carrier_documents_status'] = $application['carrier_documents_status'];


            switch ($application['application_section_journal']) {
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

            $listRoutes = $this->database->select(
                'routes',
                ['id_application' => $application['id']],
                ['sort' => 'ASC'],
                -1,
                ['id']
            );

            foreach($listRoutes as $route){
                $marshrut = new Marshrut(['id' => $route['id']]);
                $route = $marshrut->get();

                if($route['type_for'] == 1){
                    $tempData['route_client'][] = $route;
                }
                else{
                    $tempData['route_carrier'][] = $route;
                }

            }

            $user = new User(['id' => $application['id_user']]);

            $tempData['user']['name'] = $user->fullName();
            $tempData['user']['avatar'] = $user->avatar();


            $listApplications[] = $tempData;
        }

        return $listApplications;
    }

    public function countPage(int $elementsOnPage = 20, array $conditions = [])
    {
        $elements = $this->database->superSelect('applications', $conditions, [] , -1, ['COUNT(*)']);

        return ceil($elements[0]['COUNT(*)'] / $elementsOnPage);
    }
}