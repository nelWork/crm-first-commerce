<?php

namespace App\Model\TSApplication;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Driver\Driver;
use App\Model\Marshrut\Marshrut;
use App\Model\Model;

class TSApplication extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;
    private $date = null;
    private string $applicationNumber = '';
    private string $natureCargo = '';
    private int $idCustomer = 0;
    private int $idForwarder = 0;
    private string $place = '';
    private string $weight = '';
    private string $refMode = '';
    private string $specialConditions = '';
    private string $termsPayment = '';
    private float $transportationCost = 0;
    private string $taxationType = '';
    private float $costCargo = 0;
    private int $idDriver = 0;
    private string $carBrand = '';
    private string $governmentNumber = '';
    private string $semitrailer = '';
    private string $typeTransport = '';
    private string $typeCarcase = '';
    private int $applicationSection = 0;
    private int $idUser = 0;

    public array $transportationList = [];
    private array $additionalExpensesList = [];
    private array $additionalProfitList = [];

//    private $dateLastUpdate = null;
    private string $applicationStatus = 'В работе';
    private string $accountNumber = '';

    public array $fields = [
        'id', 'date', 'applicationNumber','applicationNumberForwarder', 'natureCargo', 'idCustomer', 'idForwarder',
        'place', 'weight', 'refMode', 'specialConditions', 'termsPayment', 'transportationCost',
        'taxationType', 'costCargo', 'idDriver', 'carBrand', 'governmentNumber',
        'semitrailer', 'typeTransport', 'typeCarcase', 'applicationSection', 'idUser', "applicationStatus", "accountNumber"
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $TSApplication = $this->database->first("ts_application", $data);

            if(! $TSApplication)
                return false;

            foreach ($TSApplication as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }

            $this->additionalExpensesList = $this->database->select('additional_expenses_ts',['id_application' => $this->id]) ?? [];
            $this->additionalProfitList = $this->database->select('additional_profit_ts',['id_application' => $this->id]) ?? [];

        }
    }

    public function id()
    {
        return $this->id;
    }

    public function getDocumentsComment(): array
    {
        return $this->database->select('ts_application_document_comment',['id_application' => $this->id]);
    }


    public function getFiles(): array
    {
        return $this->database->select('ts_files',['application_id' => $this->id]);
    }

    public function getDriverInfo()
    {
        $driver = new Driver(['id' => $this->idDriver]);
        return $driver->get();
    }

    public function getAdditionalExpensesList()
    {
        return $this->additionalExpensesList;
    }
    public function getAdditionalProfitList()
    {
        return $this->additionalProfitList;
    }

    public function getTransportationList()
    {
        return $this->database->select('routes_ts',['id_application' => $this->id]);
    }

    public function edit(array $values): void
    {
        if(! empty($values['additionalExpenses']))
            $this->additionalExpensesList = [];
        if(! empty($values['additionalProfit']))
            $this->additionalProfitList = [];

        foreach ($values as $key => $value) {
            if($key == 'transportationList'){

                $data = json_decode($value, true);

                foreach ($data as $array) {
                    $newMarshrut = new Marshrut([],true);

                    $newMarshrut->edit($array);

                    $this->transportationList[] = $newMarshrut;
                }
                continue;
            }

            if($key == 'additionalExpenses'){

                $data = json_decode($value, true);

                foreach ($data as $array) {
                    $temp = $array;
                    $temp['sum'] = str_replace(' ','',$array['sum']);

                    $this->additionalExpensesList[] = $temp;
                }
            }

            if($key == 'additionalProfit'){

                $data = json_decode($value, true);

                foreach ($data as $array) {
                    $temp = $array;
                    $temp['sum'] = str_replace(' ','',$array['sum']);

                    $this->additionalProfitList[] = $temp;
                }
            }

            $newKey = $this->sqlToPhpNameConvert($key);
//            var_dump([$newKey => $value]);
            $this->$newKey = $value;
        }
    }

    public function get(array $conditions = []): array|string
    {
        if (empty($conditions)) {

            $data = [];
            foreach ($this->fields as $field) {
                $data[$this->phpToSqlNameConvert($field)] = $this->$field;
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $returnedArray[$this->phpToSqlNameConvert($value)] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    private function getSetLastApplicationNumber(){
        $lastNumber = $this->database->first("document_flow")["application_ts_num"];

        $newNumber = $lastNumber + 1;

        if ($this->database->update("document_flow", ["application_ts_num" => $newNumber], ["id" => 1])){
            return $newNumber;
        }
        return null;
    }

    public function save($idCopy = 0): bool
    {
        $newData = $this->get();
//        var_dump($newData);
        if ($this->id > 0) {

            $stmt = $this->database->update("ts_application", $newData, ["id" => $this->id]);

            if (!empty($this->transportationList)){

                $this->database->delete("routes_ts", ["id_application" => $this->id]);
                foreach($this->transportationList as $transportation) {
                    $transportation->edit(['id_application' => $this->id]);
                    $transportation->save();
                }
            }

            if (!empty($this->additionalExpensesList)) {

                $this->database->delete('additional_expenses_ts', ['id_application' => $this->id]);

                foreach ($this->additionalExpensesList as $additionalExpense) {
                    $this->database->insert('additional_expenses_ts',
                        [
                            'id_application' => $this->id,
                            'type_expenses' => $additionalExpense['type_expenses'],
                            'sum' => $additionalExpense['sum'],
                            'type_payment' => $additionalExpense['type_payment'],
                            'comment' => $additionalExpense['comment'],
                        ]
                    );
                }
            }

            if (!empty($this->additionalProfitList)) {

                $this->database->delete('additional_profit_ts', ['id_application' => $this->id]);
                foreach ($this->additionalProfitList as $additionalProfit) {
                    $this->database->insert('additional_profit_ts',
                        [
                            'id_application' => $this->id,
                            'type' => $additionalProfit['type'],
                            'sum' => $additionalProfit['sum'],
                            'type_payment' => $additionalProfit['type-payment'],
                            'comment' => $additionalProfit['comment'],
                        ]
                    );
                }
            }

            return $stmt;
        }
        else{
            $this->date = date("Y-m-d H:i:s");
            $this->dateLastUpdate = date("Y-m-d H:i:s");
            $this->applicationNumber = $this->getSetLastApplicationNumber() .'-ТС';

            $newData = $this->get();


            if($idCopy) {
                $this->id = $idCopy;
//                dd($this->id);
                $this->transportationList = $this->getTransportationList();
                $this->id = 0;
            }

//            dd($this->transportationList);

            $stmt = $this->database->insert("ts_application", $newData);


            if ($stmt) {
                $this->id = $stmt;

                foreach($this->transportationList as $transportation) {

                    if($idCopy) {
                        $id = $transportation['id'];
                        $transportation = new Marshrut(['id' => $id],true);
                        $transportation->edit(['id' => 0]);
                    }

                    $transportation->edit(['id_application' => $this->id]);
                    $transportation->save();
                }

//                $this->database->delete('additional_expenses_ts', ['id_application' => $this->id]);
                foreach ($this->additionalExpensesList as $additionalExpense) {
                    if(isset($additionalExpense['type_expenses'])){
                        $additionalExpense['type-expenses'] = $additionalExpense['type_expenses'];
                    }
                    if(isset($additionalExpense['type_payment'])){
                        $additionalExpense['type-payment'] = $additionalExpense['type_payment'];
                    }
                    $this->database->insert('additional_expenses_ts',
                        [
                            'id_application' => $this->id,
                            'type_expenses' => $additionalExpense['type-expenses'],
                            'sum' => $additionalExpense['sum'],
                            'type_payment' => $additionalExpense['type-payment'],
                            'comment' => $additionalExpense['comment'],
                        ]
                    );
                }

                foreach ($this->additionalProfitList as $additionalProfit) {
                    $this->database->insert('additional_profit_ts',
                        [
                            'id_application' => $this->id,
                            'type' => $additionalProfit['type'],
                            'sum' => $additionalProfit['sum'],
                            'type_payment' => $additionalProfit['type-payment'],
                            'comment' => $additionalProfit['comment'],
                        ]
                    );
                }

                return true;
            }

            return $stmt;
        }
    }

    public function copy(): int|false
    {
        $id = $this->id;
        $this->id = 0;

        $this->save($id);

        return false;
    }
}