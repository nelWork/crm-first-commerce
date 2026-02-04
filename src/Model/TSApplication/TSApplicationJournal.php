<?php

namespace App\Model\TSApplication;

use App\Database\DatabaseInterface;
use App\Model\Model;

class TSApplicationJournal extends Model
{
    private DatabaseInterface $database;


    private int $id = 0;

    private string $date = '';


    private array $forwarderData = ['name' => '', 'inn' => ''];

    private int $idCustomer = 0;

    private int $idForwarder = 0;

    private array $transportationList = [];


    private float $additionalExpensesSum = 0;
    private float $additionalProfitSum = 0;
    private string $taxationType = '';

    private int $idUser = 0;

    private string $applicationNumber = '';

    private string $natureCargo = '';

    private int $applicationSectionJournal = 1;

    private array $eventsApplication = [];

    private array $additionalExpenses = [];


    private  $accountNumber = '';
    private  $updNumber = '';
    private float $actualPayment = 0;

    private $fullPaymentDate = '';

    private  $applicationStatusJournal = '';

    private  $applicationDateActualUnloading = '';

    private  $applicationClosedDate = '';
    private  $applicationClosedDocumentDate = '';

    private int $accountStatus = 0;


    public array $fields = [
        'id',
        'date',
        'idCustomer',
        'idForwarder',
        'forwarderData',
        'transportationList',
        'natureCargo',
        'additionalExpensesSum',
        'additionalExpenses',
        'additionalProfit',
        'additionalProfitSum',
        'transportationCost',
        'taxationType',
        'idUser',
        'applicationNumber',
        'applicationSectionJournal',
        'accountNumber',
        'updNumber',
        'actualPayment',
        'fullPaymentDate',
        'applicationStatusJournal',
        'applicationDateActualUnloading',
        'applicationClosedDate',
        'applicationClosedDocumentDate',
        'accountStatus',
    ];

    public array $fieldsSQL = [
        'id',
        'date',
        'id_customer',
        'id_forwarder',
        'transportation_cost',
        'taxation_type',
        'application_number',
        'application_section_journal',
        'id_user',
        'account_number',
        'upd_number',
        'actual_payment',
        'full_payment_date',
        'application_status_journal',
        'application_date_actual_unloading',
        'application_closed_date',
        'application_closed_document_date',
        'account_status',
    ];

    public function __construct($db, $isManager = true , array $data = [])
    {

        $this->database = $db;


        if (count($data) > 0) {

            $application = $this->database->first("ts_application", $data, $this->fieldsSQL);

            foreach ($application as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }



//            if($application['application_walrus'] > 0)
//                $this->marginality = number_format(
//                    $application['application_walrus'] / $this->CostClient * 100,
//                    2,
//                    '.',
//                    ' '
//                );
//            else
            $this->marginality = 0;

            $eventsApplication = $this->database->select('manager_journal_event',['application_id' => $this->id]);

            if($eventsApplication)
                $this->eventsApplication = $eventsApplication;


            $this->additionalExpenses = $this->database->select(
                'additional_expenses_ts',
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


            $this->forwarderData = $this->database->first('forwarders', ['id' => $this->idForwarder],['name','inn']);



            $this->idCustomer = $application['id_customer'];

            if($isManager)
                $tsRoutesDatabase =  $this->database->select('routes_ts', ['id_application' => $this->id ], [] , -1);
            else
                $tsRoutesDatabase =  $this->database->select('routes_ts', ['id_application' => $this->id], [] , -1, ['date','direction']);

            foreach ($tsRoutesDatabase as $route) {
                $this->transportationList[] = $route;
            }

//            $history = $this->database->select('history_payment', ['id_application' => $this->id]);
//
//            foreach ($history as $value) {
//                if($value['side'] == 0)
//                    $this->historyPaymentClient[] = $value;
//                else
//                    $this->historyPaymentCarrier[] = $value;
//            }



            if($isManager)
                if($application['application_section_journal'] == 4 OR $application['application_section_journal'] == 5){
                    $cancelledInfoApplication = $this->database->select('cancelled_applications', ['id_application' => $this->id]);
                    if($cancelledInfoApplication){
                        $this->commentCancel = $cancelledInfoApplication[count($cancelledInfoApplication) - 1]['comment'];
                    }
                }


        }
    }


    public function get(array $conditions = []): array|string
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $newField = $this->phpToSqlNameConvert($field);
//                if (str_contains($newField, '_client')){
//                    $newField = str_replace('_client', '_Client', $newField);
//                }
//                else if (str_contains($newField, '_carrier')){
//                    $newField = str_replace('_carrier', '_Carrier', $newField);
//                }



                $data[$newField] = $this->$field;
            }


            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $newValue = $this->phpToSqlNameConvert($value);
//                if (str_contains($newValue, '_client')){
//                    $newValue = str_replace('_client', '_Client', $newValue);
//                }
//                else if (str_contains($newValue, '_carrier')){
//                    $newValue = str_replace('_carrier', '_Carrier', $newValue);
//                }
                $returnedArray[$newValue] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }
}