<?php

namespace App\Model\PRR;

use App\Database\DatabaseInterface;
use App\Model\Model;

class PRRApplicationJournal extends Model
{
    private DatabaseInterface $database;


    private int $id = 0;

    private string $date = '';


    private array $prrData = ['name' => '', 'info' => '', 'inn' => ''];

    private array $clientData = ['name' => '', 'inn' => ''];

    private int $customerIdClient = 0;

    private int $clientIdClient = 0;

    private int $prrIdPrr = 0;

    private array $prrPlaceList = [];

    private array $historyPaymentClient = [];
    private array $historyPaymentPrr = [];


    private float $additionalExpensesSum = 0;
    private string $taxationTypePrr = '';
    private string $taxationTypeClient = '';

    private int $idUser = 0;

    private string $applicationNumber = '';

    private string $natureCargoPrr = '';

    private int $idCustomer = 1;

    private int $applicationSectionJournal = 1;

    private array $eventsApplication = [];

    private array $additionalExpenses = [];


    private float $applicationNetProfit  = 0;

    private float $marginality = 0;

    private  $accountNumberClient = '';
    private  $updNumberClient = '';
    private float $actualPaymentClient = 0;

    private $fullPaymentDateClient = '';


    private  $accountNumberPrr = '';
    private  $updNumberPrr = '';
    private float $actualPaymentPrr = 0;

    private $fullPaymentDatePrr = '';

    private float $costClient = 0;
    private float $costPrr = 0;

    private  $applicationStatusJournal = '';

    private  $applicationDateActualUnloading = '';

    private  $applicationClosedDate = '';
    private  $applicationClosedDocumentDate = '';

    private int $accountStatusClient = 0;
    private float $taxation = 0;


    private $dateInvoiceClient = null;

    public array $fields = [
        'id',
        'date',
        'prrIdPrr',
        'prrData',
        'customerIdClient',
        'clientIdClient',
        'clientData',
        'prrPlaceList',
        'natureCargoPrr',
        'additionalExpensesSum',
        'additionalExpenses',
        'additionalProfit',
        'costPrr',
        'costClient',
        'taxationTypePrr',
        'taxationTypeClient',
        'idUser',
        'applicationNumber',
        'managerShare',
        'applicationWalrus',
        'applicationNetProfit',
        'idCustomer',
        'applicationSectionJournal',
        'marginality',
        'accountNumberClient',
        'updNumberClient',
        'actualPaymentClient',
        'fullPaymentDateClient',
        'accountNumberPrr',
        'updNumberPrr',
        'actualPaymentPrr',
        'fullPaymentDatePrr',
        'applicationStatusJournal',
        'applicationDateActualUnloading',
        'applicationClosedDate',
        'applicationClosedDocumentDate',
        'accountStatusClient',
        'dateInvoiceClient',
        'historyPaymentClient',
        'historyPaymentPrr',
        'taxation'
    ];

    public array $fieldsSQL = [
        'id',
        'date',
        'customer_id_Client',
        'client_id_Client',
        'prr_id_Prr',
        'cost_Prr',
        'cost_Client',
        'taxation_type_Client',
        'taxation_type_Prr',
        'application_number',
        'application_walrus',
        'manager_share',
        'application_net_profit',
        'application_section_journal',
        'id_user',
        'account_number_Client',
        'upd_number_Client',
        'actual_payment_Client',
        'full_payment_date_Client',
        'account_number_Prr',
        'upd_number_Prr',
        'actual_payment_Prr',
        'full_payment_date_Prr',
        'application_status_journal',
        'application_date_actual_unloading',
        'application_closed_date',
        'application_closed_document_date',
        'account_status_Client',
        'date_invoice_Client',
    ];

    public function __construct($db, $isManager = true , array $data = [])
    {

        $this->database = $db;


        if (count($data) > 0) {

            $application = $this->database->first("prr_application", $data, $this->fieldsSQL);

            if(!$application)
                return false;

//            dd($application);

            foreach ($application as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }



            if($application['application_walrus'] > 0)
                $this->marginality = number_format(
                    $application['application_walrus'] / $this->costClient * 100,
                    2,
                    '.',
                    ' '
                );
            else
                $this->marginality = 0;

            $eventsApplication = $this->database->select('manager_journal_event',['application_id' => $this->id]);

            if($eventsApplication)
                $this->eventsApplication = $eventsApplication;


            $this->additionalExpenses = $this->database->select(
                'additional_expenses_prr',
                ['id_application' => $this->id],
                [],
                -1,
                ['id','type_expenses','sum','type_payment'],

            );
//            $this->additionalExpenses[] = ['type_expenses' => 'Страховка', 'sum' => 500, 'type_payment' => 'Б/НДС'];

            foreach ($this->additionalExpenses as  $expense) {
                $this->additionalExpensesSum += (float)$expense['sum'];
            }

            $this->additionalProfit = [];


            $this->prrData = $this->database->first('prr_company', ['id' => $this->prrIdPrr],['name','contact','inn']);
            $this->clientData = $this->database->first('clients', ['id' => $this->clientIdClient],['name','inn','format_work']);

//            $this->carrierData = ['name' => '','info' => '','inn' => ''];
//            $this->clientData = ['name' => '','inn' => '','format_work' => ''];


            $this->idCustomer = $application['customer_id_Client'];

            if($isManager)
                $prrPlaceDatabase =  $this->database->select('prr_place', ['id_application' => $this->id, 'type_for' => 0], [] , -1);
            else
                $prrPlaceDatabase =  $this->database->select('prr_place', ['id_application' => $this->id, 'type_for' => 0], [] , -1, ['date','direction']);

            foreach ($prrPlaceDatabase as $place) {
                $this->prrPlaceList[] = $place;
            }

            $history = $this->database->select('prr_history_payment', ['id_application' => $this->id]);

            foreach ($history as $value) {
                if($value['side'] == 0)
                    $this->historyPaymentClient[] = $value;
                else
                    $this->historyPaymentPrr[] = $value;
            }



            if($isManager)
                if($application['application_section_journal'] == 4 OR $application['application_section_journal'] == 5){
                    $cancelledInfoApplication = $this->database->select('cancelled_applications', ['id_application' => $this->id]);
                    if($cancelledInfoApplication){
                        $this->commentCancel = $cancelledInfoApplication[count($cancelledInfoApplication) - 1]['comment'];
                    }
                }


            
            $costClient = $this->costClient;
            $costCarrier = $this->costPrr;

            $taxationTypeClient = $this->taxationTypeClient;

            $taxationTypeCarrier = $this->taxationTypePrr;

            $additionalProfit = $this->countSumAdditionalProfit();
            $additionalExpenses = $this->countSumAdditionalExpenses($taxationTypeClient);
            $deduction = $this->getDeduction();

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
                        $incomeTax = 0.25;
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
                        $incomeTax = 0.25;
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
                        $incomeTax = 0.25;
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