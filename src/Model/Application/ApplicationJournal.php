<?php

namespace App\Model\Application;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Driver\Driver;
use App\Model\Fines\Fines;
use App\Model\Marshrut\Marshrut;
use App\Model\Model;

class ApplicationJournal extends Model
{
    private DatabaseInterface $database;


    private int $id = 0;

    private string $date = '';

    private int $carrierIdCarrier = 0;

    private array $carrierData = ['name' => '', 'info' => '', 'inn' => ''];

    private array $clientData = ['name' => '', 'inn' => ''];

    private int $customerIdClient = 0;
    private int $customerIdCarrier = 0;

    private int $clientIdClient = 0;
    private int $driverIdCarrier = 0;
    private int $driverIdClient = 0;

    private array $transportationList = [];


    private float $finesSum = 0;
    private float $additionalExpensesSumCarrier = 0;
    private float $additionalProfitSumClient = 0;

    private float $transportationCostCarrier = 0;
    private float $transportationCostClient = 0;

    private string $taxationTypeCarrier = '';
    private string $taxationTypeClient = '';

    private int $idUser = 0;

    private string $applicationNumber = '';
    private  $applicationNumberClient = NULL;

    private string $applicationStatusJournal = '';

    private string $natureCargoCarrier = '';

    private string $driverInfo = '';
    private string $driverNumber = '';
    private string $carInfo = '';

    private string $commentCancel = '';

    private int $idCustomer = 1;

    private int $applicationSectionJournal = 1;

    private array $eventsApplication = [];

    private array $additionalExpenses = [];
    private array $additionalProfit = [];

    private string $accountNumberClient = '';
    private string $accountNumberCarrier = '';

    private string $updNumberClient = '';
    private string $updNumberCarrier = '';

    private float $actualPaymentClient = 0;
    private float $actualPaymentCarrier = 0;

    private float $applicationNetProfit  = 0;

    private int $accountStatusClient = 0;

    private float $marginality = 0;

    private $fullPaymentDateCarrier = NULL;
    private $fullPaymentDateClient = NULL;
    private array $historyPaymentClient = [];
    private array $historyPaymentCarrier = [];

    private $dateInvoiceClient = null;

    private $applicationDateActualUnloading = NULL;

    private int $ttnSent = 0;

    private float $taxation = 0;

    private bool $strangeApplication = false;

    private int $forSales = 0;

    private float $shareForSales = 0;

    private float $balancePaymentClient = 0;

    private float $balancePaymentCarrier = 0;

    private string $termsPaymentCarrier = '';
    private string $termsPaymentClient = '';

    private string $datePaymentClient = '';
    private string $datePaymentCarrier = '';

    private $dateReceiptAllDocumentsCarrier;

    public array $fields = [
        'id',
        'date',
        'carrierIdCarrier',
        'carrierData',
        'customerIdClient',
        'customerIdCarrier',
        'clientIdClient',
        'clientData',
        'driverIdClient',
        'transportationList',
        'natureCargoCarrier',
        'finesSum',
        'additionalExpensesSumCarrier',
        'additionalProfitSumClient',
        'additionalExpenses',
        'additionalProfit',
        'transportationCostCarrier',
        'transportationCostClient',
        'taxationTypeCarrier',
        'taxationTypeClient',
        'idUser',
        'applicationNumber',
        'applicationNumberClient',
        'clientChosenInfo',
        'carrierChosenInfo',
        'managerShare',
        'managerComment',
        'applicationWalrus',
        'applicationNetProfit',
        'eventsApplication',
        'applicationStatusJournal',
        'driverInfo',
        'driverNumber',
        'commentCancel',
        'carInfo',
        'idCustomer',
        'applicationSectionJournal',
        'accountNumberClient',
        'accountNumberCarrier',
        'updNumberClient',
        'updNumberCarrier',
        'actualPaymentClient',
        'actualPaymentCarrier',
        'applicationDateActualUnloading',
        'accountStatusClient',
        'dateInvoiceClient',
        'historyPaymentClient',
        'historyPaymentCarrier',
        'fullPaymentDateCarrier',
        'fullPaymentDateClient',
        'marginality',
        'ttnSent',
        'strangeApplication',
        'taxation',
        'forSales',
        'shareForSales',
        'balancePaymentClient',
        'balancePaymentCarrier',
        'termsPaymentCarrier',
        'termsPaymentClient',
        'datePaymentClient',
        'datePaymentCarrier',
        'dateReceiptAllDocumentsCarrier'
    ];

    public array $fieldsSQL = [
        'id',
        'date',
        'carrier_id_Carrier',
        'customer_id_Client',
        'customer_id_Carrier',
        'client_id_Client',
        'driver_id_Client',
        'transportation_cost_Carrier',
        'transportation_cost_Client',
        'taxation_type_Carrier',
        'taxation_type_Client',
        'nature_cargo_Carrier',
        'id_user',
        'application_number',
        'application_number_client',
        'client_chosen_info',
        'carrier_chosen_info',
        'manager_share',
        'manager_comment',
        'application_walrus',
        'application_net_profit',
        'application_status_journal',
        'application_section_journal',
        'driver_id_Carrier',
        'car_brand_id_Carrier',
        'government_number_Carrier',
        'account_number_Client',
        'account_number_Carrier',
        'upd_number_Client',
        'upd_number_Carrier',
        'actual_payment_Client',
        'actual_payment_Carrier',
        'application_date_actual_unloading',
        'account_status_Client',
        'date_invoice_client',
        'full_payment_date_Client',
        'full_payment_date_Carrier',
        'ttn_sent',
        'for_sales',
        'share_for_sales',
        'terms_payment_Carrier',
        'terms_payment_Client',
        'date_receipt_all_documents_Carrier'
    ];

    public function __construct($db, $isManager = true , array $data = [], $eventsTable = 'manager_journal_event')
    {
        $this->database = $db;
        if(!$data['id'])
            return false;

        if (count($data) > 0) {
            $application = $this->database->first("applications", $data, $this->fieldsSQL);

            if(!$application)
                return false;

            foreach ($application as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }

            if($application['id'] == 37)
                $this->applicationNumber = $application['application_number'] .'/1';

            if($application['application_walrus'] > 0)
                $this->marginality = number_format(
                    $application['application_walrus'] / $this->transportationCostClient * 100,
                    2,
                    '.',
                    ' '
                );
            else
                $this->marginality = 0;

            $eventsApplication = $this->database->select($eventsTable, ['application_id' => $this->id]);

            if($eventsApplication)
                $this->eventsApplication = $eventsApplication;


            if($this->driverIdClient > 0) {
                $driverData = $this->database->first('drivers', ['id' => $this->driverIdClient], ['name', 'phone']);


                $this->driverInfo = $driverData['name'];
                $this->driverNumber = $driverData['phone'];
            }
            $this->carInfo = $application['car_brand_id_Carrier'] . ' ' . $application['government_number_Carrier'];

            $this->finesSum = 0;


            $this->additionalExpenses = $this->database->select(
                'additional_expenses',
                ['id_application' => $this->id, 'type_for' => 0],
                [],
                -1,
                ['id','type_expenses','sum','type_payment'],
                0,
                'AND',
                '=',
                ['type_expenses' => 'Страховка']

            );
            if($this->id != 49 AND $this->id != 105)
                $this->additionalExpenses[] = ['type_expenses' => 'Страховка', 'sum' => 500, 'type_payment' => 'Б/НДС'];

            foreach ($this->additionalExpenses as  $expense) {
                $this->additionalExpensesSumCarrier += (float)$expense['sum'];
            }

            $this->additionalProfit = $this->database->select('additional_profit', ['id_application' => $this->id]);

            foreach ($this->additionalProfit as $profit) {
                $this->additionalProfitSumClient += (float)$profit['sum'];
            }

            $this->carrierData = $this->database->first('carriers', ['id' => $this->carrierIdCarrier],['name','info','inn']);
            $this->clientData = $this->database->first('clients', ['id' => $this->clientIdClient],['name','inn','format_work']);

//            $this->carrierData = ['name' => '','info' => '','inn' => ''];
//            $this->clientData = ['name' => '','inn' => '','format_work' => ''];


            $this->idCustomer = $application['customer_id_Client'];

            if($isManager)
                $routesDatabase =  $this->database->select('routes', ['id_application' => $this->id, 'type_for' => 0], [] , -1);
            else
                $routesDatabase =  $this->database->select('routes', ['id_application' => $this->id, 'type_for' => 0], [] , -1, ['date','direction']);

            foreach ($routesDatabase as $route) {
                $this->transportationList[] = $route;
            }

            $history = $this->database->select('history_payment', ['id_application' => $this->id]);

            foreach ($history as $value) {
                if($value['side'] == 0)
                    $this->historyPaymentClient[] = $value;
                else
                    $this->historyPaymentCarrier[] = $value;
            }

            if($isManager)
                if($application['application_section_journal'] == 4 OR $application['application_section_journal'] == 5){
                    $cancelledInfoApplication = $this->database->select('cancelled_applications', ['id_application' => $this->id]);
                    if($cancelledInfoApplication){
                        $this->commentCancel = $cancelledInfoApplication[count($cancelledInfoApplication) - 1]['comment'];
                    }
                }

            $strangeApplication = false;

            $revenueInApplication = $this->transportationCostClient + $this->additionalProfitSumClient
                - $this->transportationCostCarrier - $this->additionalExpensesSumCarrier;


            if($this->applicationWalrus > $revenueInApplication AND $this->applicationWalrus > 0)
                $strangeApplication = true;

            $this->strangeApplication = $strangeApplication;


        }
        $this->balancePaymentClient =  $this->transportationCostClient - $this->actualPaymentClient;
        $this->balancePaymentCarrier =  $this->transportationCostCarrier - $this->actualPaymentCarrier;

        $costClient = $this->transportationCostClient;
        $costCarrier = $this->transportationCostCarrier;

        $taxationTypeClient = $this->taxationTypeClient;

        $taxationTypeCarrier = $this->taxationTypeCarrier;

        if($this->dateReceiptAllDocumentsCarrier)
            $this->datePaymentCarrier = $this->calculateBankDays($this->dateReceiptAllDocumentsCarrier, $this->termsPaymentCarrier);

        if($this->applicationDateActualUnloading)
            $this->datePaymentClient = $this->calculateBankDays($this->applicationDateActualUnloading, $this->termsPaymentClient);

        $additionalProfit = $this->countSumAdditionalProfit();
        $additionalExpenses = $this->countSumAdditionalExpenses($taxationTypeClient);
        $deduction = $this->getDeduction();

        $walrus = 0;

        switch ($this->customerIdClient):
            case 1:
                if($taxationTypeClient == 'С НДС' AND ($taxationTypeCarrier == 'Б/НДС' OR $taxationTypeCarrier == 'НДС 0%')){
                    $clientVAT = $costClient * 20.0 / 120.0;

                    $revenueClient = $costClient - $clientVAT - $deduction['cash'] / 0.75;

                    $revenueDifference = $revenueClient - $costCarrier +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];

//                  налог на прибыль в 2024 году 20%
                    $incomeTax = 0.2;
//                  налог на прибыль в 2025 25%
                    if(strtotime($this->date) > strtotime('2025-01-01')){
                        $incomeTax = 0.05;
                    }

                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $this->taxation = $revenueDifference * $incomeTax;
                    }
                }
                if(
                    $taxationTypeClient == 'С НДС' AND (
                        $taxationTypeCarrier == 'С НДС' OR $taxationTypeCarrier == 'НДС 5%' OR $taxationTypeCarrier == 'НДС 7%'
                    )
                ){
                    $clientVAT = $costClient * 20.0 / 120.0;

                    $revenueClient = $costClient - $clientVAT - $deduction['cash'] / 0.75;


                    $carrierVAT = $costCarrier * 20.0 / 120.0;

                    if($taxationTypeCarrier == 'НДС 5%')
                        $carrierVAT = $costCarrier * 5.0 / 105.0;

                    if($taxationTypeCarrier == 'НДС 7%')
                        $carrierVAT = $costCarrier * 7.0 / 107.0;

                    $revenueCarrier = $costCarrier - $carrierVAT;


                    $revenueDifference = $revenueClient - $revenueCarrier +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];



//                    налог на прибыль в 2024 году 20%
                    $incomeTax = 0.2;
//                  налог на прибыль в 2025 25%
                    if(strtotime($this->date) > strtotime('2025-01-01')){
                        $incomeTax = 0.05;
                    }

                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $this->taxation = ($revenueDifference * $incomeTax);
                    }

                }

                if($taxationTypeClient == 'НДС 0%' AND ($taxationTypeCarrier == 'НДС 0%' OR $taxationTypeCarrier == 'Б/НДС')){

                    $revenueDifference = $costClient - $costCarrier +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'] - $deduction['cash'] / 0.75;




//                  налог на прибыль в 2024 году 20%
                    $incomeTax = 0.2;
//                  налог на прибыль в 2025 25%
                    if(strtotime($this->date) > strtotime('2025-01-01')){
                        $incomeTax = 0.05;
                    }

                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $this->taxation = ($revenueDifference * $incomeTax);
                    }


                    $walrus -= $additionalExpenses['cash'] / 0.75;
                }

                if($taxationTypeClient == 'НАЛ' AND
                    ($taxationTypeCarrier == 'Б/НДС' OR $taxationTypeCarrier == 'С НДС' OR $taxationTypeCarrier == 'НАЛ')
                ){

                    $revenueDifference = $costClient - $costCarrier +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'] - $deduction['cash'] / 0.75;



                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $this->taxation = ($revenueDifference * $incomeTax);
                    }


                    $walrus -= $additionalExpenses['cash'] / 0.75;

                }

                break;
            case 2:
            case 3:
                if($taxationTypeClient == 'Б/НДС' AND $taxationTypeCarrier == 'Б/НДС'
                    OR $taxationTypeClient == 'Б/НДС' AND $taxationTypeCarrier == 'С НДС'
                    OR $taxationTypeClient == 'НАЛ' AND $taxationTypeCarrier == 'Б/НДС'){

                    $revenueDifference = $costClient - $costCarrier +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];


                    if($revenueDifference < 0 OR $taxationTypeClient == 'НАЛ'){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $this->taxation = ($revenueDifference * 8.0 / 100.0);
                    }
                }

                if($taxationTypeClient == 'Б/НДС' AND $taxationTypeCarrier == 'НАЛ'){

                    $revenueDifference = $costClient  +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];


                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $this->taxation = ($revenueDifference * 8.0 / 100.0);
                    }
                }

                break;
        endswitch;
    }


    private  function countSumAdditionalProfit(): array
    {
        $sumProfit = ['cash' => 0, 'withoutVAT' => 0];

        return $sumProfit;
    }

    private function getDeduction(): array
    {
        $additionalExpensesList = $this->additionalExpenses;
        $sumExpenses = ['cash' => 0, 'withoutVAT' => 0];


        foreach ($additionalExpensesList as $additionalExpenses) {
            $data = $additionalExpenses;
            $data['sum'] = (float)$data['sum'];


            if ($data['type_payment'] != 'НАЛ' OR $this->customerIdClient != 1) {
                continue;
            }

            switch ($data['type_payment']):
                case "С НДС":
                    $sumExpenses['withoutVAT'] += $data['sum'] - $data['sum'] / 6;
                    break;
                case "Б/НДС":
                    $sumExpenses['withoutVAT'] += $data['sum'];
                    break;
                case "НАЛ":
                    $sumExpenses['cash'] += $data['sum'];
                    break;
            endswitch;


        }

        return $sumExpenses;
    }

    private function countSumAdditionalExpenses(): array
    {

        $additionalExpensesList = $this->additionalExpenses;
        $sumExpenses = ['cash' => 0, 'withoutVAT' => 0];


        foreach ($additionalExpensesList as $additionalExpenses) {
            $additionalExpenses['sum'] = (float)$additionalExpenses['sum'];

            if($additionalExpenses['type_payment'] == 'НАЛ' AND $this->customerIdClient == 1)
                continue;

            if($this->customerIdClient == 1){
                switch ($additionalExpenses['type_payment']):
                    case  "С НДС":
                        $sumExpenses['withoutVAT'] += $additionalExpenses['sum'] - $additionalExpenses['sum'] / 6;
                        break;
                    case "Б/НДС":
                        $sumExpenses['withoutVAT'] += $additionalExpenses['sum'];
                        break;
                    case "НАЛ":
                        $sumExpenses['cash'] += $additionalExpenses['sum'];
                        break;
                endswitch;
            }
            else{
                switch ($additionalExpenses['type_payment']):
                    case  "С НДС":
                    case "Б/НДС":
                        $sumExpenses['withoutVAT'] += $additionalExpenses['sum'];
                        break;
                    case "НАЛ":
                        $sumExpenses['cash'] += $additionalExpenses['sum'];
                        break;
                endswitch;
            }
        }

        return $sumExpenses;
    }


    public function get(array $conditions = []): array|string
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $newField = $this->phpToSqlNameConvert($field);
                if (str_contains($newField, '_client')){
                    $newField = str_replace('_client', '_Client', $newField);
                }
                else if (str_contains($newField, '_carrier')){
                    $newField = str_replace('_carrier', '_Carrier', $newField);
                }



                $data[$newField] = $this->$field;
            }


            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $newValue = $this->phpToSqlNameConvert($value);
                if (str_contains($newValue, '_client')){
                    $newValue = str_replace('_client', '_Client', $newValue);
                }
                else if (str_contains($newValue, '_carrier')){
                    $newValue = str_replace('_carrier', '_Carrier', $newValue);
                }
                $returnedArray[$newValue] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }
}
