<?php

namespace App\User\Model\Application;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\AdditionalExpenses\AdditionalExpenses;
use App\Model\Client\Client;
use App\Model\Carrier\Carrier;
use App\Model\Driver\Driver;
use App\Model\Fines\Fines;
use App\Model\Marshrut\Marshrut;
use App\Model\User\User;
use App\User\Model\Model;

class ApplicationPage extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;


    public int $id = 0;
    public string $numApp;

    public string $natureCargo;

    public string $date;

    public array $main = [];

    public array $manager = ['name' => '', 'img' => ''];

    public array $status = [];

    public int $idUser = 0;

    public int $ttnSent = 0;

    public int $ttnReceived = 0;

    public int $applicationSectionJournal = 0;

    public $dateReceiptAllDocumentsCarrier = NULL;

    public int $prepaymentCarrier = 0;

    public array $documentComments = [];

    public array $client = [
        'name'=> '',
        'type_taxation' => '',
        'inn' => '',
        'legal_address' => '',
        'contact' => '',
        'phone' => '',
        'transportation' => [
            'routes' => [

            ],
            'transportation_cost' => '',
            'cargo_cost' => '',
            'application_date_actual_unloading' => ''

        ],
        'driver' => [
            'transport' => '',
            'name' => '',
            'phone' => '',
            'driver_license' => '',
            'passport' => ''
        ],
        'conditions' => [
            'terms_payment' => '',
            'special_conditions' => ''
        ],
        'additional_expenses' => [

        ]
    ];
    public array $carrier = [
        'name'=> '',
        'type_taxation' => '',
        'inn' => '',
        'legal_address' => '',
        'contact' => '',
        'phone' => '',
        'transportation' => [
            'routes' => [

            ],
            'transportation_cost' => '',
            'cargo_cost' => '',
            'application_date_actual_unloading' => ''
        ],
        'driver' => [
            'transport' => '',
            'name' => '',
            'phone' => '',
            'driver_license' => '',
            'passport' => ''
        ],
        'conditions' => [
            'terms_payment' => '',
            'special_conditions' => ''
        ],
        'additional_expenses' => [

        ],
        'fines' => [
            'from' => [],
            'to' => []
        ]
    ];

    public array $documents = [];

    public array $documentsName = [
        'application-carrier' => '',
        'application-client' => '',
        'attorney-driver' => '',
        'forwarding-receipt' => '',
        'receipt-services' => '',
        'insurance' => '',
        'info-driver' => '',
        'attorney-m2' => ''
    ];


    public function __construct($id){
        $this->config = new Config();
        $this->database = new Database($this->config);

        if ($id > 0) {
            $application = $this->database->first("applications", ['id' => $id]);
//            dd($application);
            if(! $application)
                return false;

            $this->id = $id;
            $this->numApp = $application['application_number'];
            $this->applicationSectionJournal = $application['application_section_journal'];


            if($application['application_number'] < 500)
                $this->numApp = $application['application_number'] .'-Т';

            if($application['id'] == 3323)
                $this->numApp = $application['application_number'] .'/1';

            if($application['application_number_client'] != ''){
                $this->numApp .= ' / ' .$application['application_number_client'];
            }
            $this->natureCargo = $application['nature_cargo_Client'];
            $this->idUser = $application['id_user'];

//            $this->date = date('d.m.Y', strtotime($application['date_Carrier']));
            $this->date = $this->format_date($application['date'], "d.m.Y");
            $this->clientData($application['client_id_Client']);

            $this->client['type_taxation'] = $application['taxation_type_Client'];

            $this->client['transportation']['transportation_cost'] = number_format(
                $application['transportation_cost_Client'],
                0,
                '.',
                ' '
            );
            $this->client['transportation']['cargo_cost'] = number_format(
                $application['cost_cargo_Client'],
                0,
                '.',
                ' '
            );

            $this->carrierData($application['carrier_id_Carrier']);

            $this->carrier['type_taxation'] = $application['taxation_type_Carrier'];

            $this->carrier['transportation']['transportation_cost'] = number_format(
                $application['transportation_cost_Carrier'],
                0,
                '.',
                ' '
            );

            $this->driverData($application['driver_id_Client'],$application['driver_id_Carrier']);

            $this->client['conditions']['terms_payment'] = $application['terms_payment_Client'];
            $this->client['conditions']['special_conditions'] = $application['special_conditions_Client'];


            $this->carrier['conditions']['terms_payment'] = $application['terms_payment_Carrier'];
            $this->carrier['conditions']['special_conditions'] = $application['special_conditions_Carrier'];


            $this->client['driver']['transport'] =
                $application['car_brand_id_Client'] .' ' . $application['government_number_Client'];
            $this->carrier['driver']['transport'] =
                $application['car_brand_id_Carrier'] .' ' . $application['government_number_Carrier'];

            $this->userData($application['id_user']);

            $this->routesData($application['id']);

            $this->finesData($application['id']);

            $this->additionalExpensesData($application['id']);

            $this->status['application-status'] = $application['application_status'];
            $this->status['client-payment-status'] = $application['client_payment_status'];
            $this->status['carrier-payment-status'] = $application['carrier_payment_status'];
            $this->status['client-documents-status'] = $application['client_documents_status'];
            $this->status['carrier-documents-status'] = $application['carrier_documents_status'];

            if($application['application_date_actual_unloading']) {
                $this->client['transportation']['application_date_actual_unloading'] =
                    date('d.m.Y', strtotime($application['application_date_actual_unloading']));
                $this->carrier['transportation']['application_date_actual_unloading'] =
                    $this->client['transportation']['application_date_actual_unloading'];
            }
            $routeTextClient = '';
            foreach ($this->client['transportation']['routes'] as $route) {
                $city = explode(',',$route['city']);
                $routeTextClient .= $city[count($city) - 1] .'-';
            }

            $nameTextClient = '';
            $first = true;
            foreach (explode(' ', $this->client['name']) as $text ){
                if($first)
                    $first = false;
                else
                    $nameTextClient .= $text .'_';
            }

            $routeTextClient = trim($routeTextClient, '-');
            $dateApplicationText = date('dmY', strtotime($application['date']));
            $driverApplicationTextClient = ' (' .  explode(' ',$this->client['driver']['name'])[0] .')';

            $this->documentsName['application-client'] =  $nameTextClient .$routeTextClient .'_'
                .$dateApplicationText .$driverApplicationTextClient;

            $routeTextCarrier = '';
            foreach ($this->carrier['transportation']['routes'] as $route) {
                $city = explode(',',$route['city']);
                $routeTextCarrier .= $city[count($city) - 1] .'-';
            }
            $routeTextCarrier = trim($routeTextCarrier, '-');

            $driverApplicationCarrierText = ' (' .  explode(' ',$this->carrier['driver']['name'])[0] .')';

            $this->documentsName['application-carrier'] = $routeTextCarrier .'_' .$dateApplicationText .$driverApplicationCarrierText .' №' .$this->numApp;

            $this->documentsName['attorney-driver'] = 'Доверенность_' .$nameTextClient .' № ';

            $this->documentsName['forwarding-receipt'] = 'ЭР ' .$routeTextClient . '_' .$dateApplicationText .$driverApplicationTextClient;

            $this->documentsName['receipt-services'] = 'Расписка об оказании услуг № ';

            $this->documentsName['insurance'] = 'Страховка_' .$nameTextClient .$routeTextClient .'_'
                .$dateApplicationText .$driverApplicationTextClient;

            $this->documentsName['info-driver'] = 'Данные на вод.' .$driverApplicationTextClient;

            $this->documentsName['attorney-m2'] = 'Доверенность на водителя (М2 - ' .$this->numApp .') ' . $this->carrier['driver']['name'];

            $this->documentComments = $this->database->select('application_document_comment', ['id_application' => $this->id]);


            $this->ttnSent = $application['ttn_sent'] ?? 0;
            $this->ttnReceived = $application['ttn_received'] ?? 0;

            if($application['date_receipt_all_documents_Carrier']){
                $this->dateReceiptAllDocumentsCarrier = $application['date_receipt_all_documents_Carrier'];
            }

            $this->prepaymentCarrier = $application['prepayment_Carrier'] ?? 0;

//            dd($this);
        }

    }

    public function format_date($date, $format)
    {
        $intDate = strtotime($date);
        return date($format, $intDate);
    }

    private function clientData($id)
    {
        $client = new Client(['id' => $id]);

        $client = $client->get();

        $this->client['name'] = $client['name'];

        $this->client['inn'] = $client['inn'];
        $this->client['legal_address'] = $client['legal_address'];

        $this->client['contact'] = $client['full_name'];
        $this->client['phone'] = $client['phone'];

    }

    private function carrierData($id)
    {
        $carrier = new Carrier(['id' => $id]);

        $carrier = $carrier->get();


        $this->carrier['name'] = $carrier['name'];

        $this->carrier['inn'] = $carrier['inn'];
        $this->carrier['legal_address'] = $carrier['legal_address'];

    }


    private function driverData($idDriverClient, $idDriverCarrier)
    {
        $driverCarrier = new Driver(['id' => $idDriverCarrier]);
        $driverClient = $driverCarrier;
        if($idDriverCarrier !== $idDriverClient)
            $driverClient = new Driver(['id' => $idDriverClient]);

        $dataCarrierDrive = $driverCarrier->get();
        $dataClientDrive = $driverClient->get();

        $this->client['driver']['name'] = $dataClientDrive['name'];
        $this->carrier['driver']['name'] = $dataCarrierDrive['name'];

        $this->client['driver']['phone'] = $dataClientDrive['phone'];
        $this->carrier['driver']['phone'] = $dataCarrierDrive['phone'];

        $this->client['driver']['driver_license'] = $dataClientDrive['driver_license'];
        $this->carrier['driver']['driver_license'] = $dataCarrierDrive['driver_license'];

        $this->client['driver']['passport'] =
            $dataClientDrive['passport_serial_number'] .", "
            .date('d.m.Y',strtotime($dataClientDrive['issued_date']))
            .' ' .$dataClientDrive['issued_by']
            .' ' .$dataClientDrive['department_code']
        ;
        $this->carrier['driver']['passport'] =
            $dataCarrierDrive['passport_serial_number'] .", "
            .date('d.m.Y',strtotime($dataCarrierDrive['issued_date']))
            .' ' .$dataCarrierDrive['issued_by']
            .' ' .$dataCarrierDrive['department_code']
        ;



//        dd($dataCarrierDrive, $dataClientDrive);
    }
    private function routesData($id)
    {
        $listRoutes = $this->database->select('routes', ['id_application' => $id], ['sort' => 'ASC']);

        foreach($listRoutes as $route){
            $marshrut = new Marshrut(['id' => $route['id']]);
            $route = $marshrut->get();

            if($route['type_for'] == 1){
                $this->client['transportation']['routes'][] = $route;
            }
            else{
                $this->carrier['transportation']['routes'][] = $route;
            }

        }
    }
    private function userData($id)
    {
        $user = new User(['id' => $id]);
        $img = $user->avatar();

        $user = $user->get();

        $this->manager['name'] = $user['name'] .' ' .$user['surname'];
        $this->manager['img'] = $img;
    }

    private function finesData($idApplication)
    {
        $finesId = $this->database->select('fines', ['id_application' => $idApplication]);

        foreach ($finesId as $item){
            $fines = new Fines(['id' => $item['id']]);

            $data = $fines->get();

            if($data['type_for'])
                $this->carrier['fines']['to'][] = $data;
            else
                $this->carrier['fines']['from'][] = $data;
        }
    }

    private function additionalExpensesData($idApplication)
    {
        $expenses = $this->database->select('additional_expenses', ['id_application' => $idApplication]);

        foreach ($expenses as $item){
            $expense = new AdditionalExpenses(['id' => $item['id']]);

            $data = $expense->get();

            if($data['type_for'])
                $this->client['expenses'][] = $data;
            else
                $this->carrier['expenses'][] = $data;
        }
    }
}