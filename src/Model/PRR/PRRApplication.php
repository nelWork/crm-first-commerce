<?php

namespace App\Model\PRR;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;
use App\Model\User\User;

class PRRApplication extends Model

{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;
    private $date = null;

    private string $placePrr = '';
    private string $placeClient = '';


    private string $weightPrr = '';
    private string $numberLoadersPrr = '';
    private string $numberLoadersClient = '';
    private int $customerIdPrr = 0;
    private int $prrIdPrr = 0;
    private int $clientIdClient = 0;
    private string $natureCargoPrr = '';
    private string $specialConditionPrr = '';
    private string $termsPaymentPrr = '';
    private string $costPrr = '';
    private string $taxationTypePrr = '';
    private string $weightClient = '';
    private int $customerIdClient = 0;
    private string $natureCargoClient = '';
    private string $specialConditionClient = '';
    private string $termsPaymentClient = '';
    private string $costClient = '';
    private string $taxationTypeClient = '';
    private int $idUser = 0;
    private string $applicationNumber = '';

    private int $idApplication = 0;

    private array $additionalExpensesList = [];
    private $dateLastUpdate = null;

    private string $datePrr = '';
    private string $dateClient = '';

    private float $applicationWalrus = 0;

    private float $managerShare = 0;

    private float $applicationNetProfit = 0;

    private int $receiptServicesNum = 0;

    private string $chosenContactClient = '';
    private string $chosenContactPrr = '';

    private array $placeList = [];
    private string $applicationStatus = 'В работе';

    public array $fields = [
        'id', 'date', 'weightPrr',
        'customerIdPrr', 'prrIdPrr', 'clientIdClient', 'natureCargoPrr',
        'specialConditionPrr', 'termsPaymentPrr', 'costPrr', 'taxationTypePrr',
        'weightClient', 'customerIdClient', 'natureCargoClient', 'specialConditionClient',
        'termsPaymentClient', 'costClient', 'taxationTypeClient', 'idUser',
        'dateLastUpdate','applicationNumber', 'idApplication',
        'applicationWalrus', 'managerShare', 'applicationNetProfit','receiptServicesNum',
        'chosenContactClient','chosenContactPrr',
        'numberLoadersClient', 'numberLoadersPrr','placePrr', 'placeClient', "applicationStatus"
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $PRRApplication = $this->database->first("prr_application", $data);

            if(! $PRRApplication)
                return false;

            foreach ($PRRApplication as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }

            $this->additionalExpensesList = $this->database->select('additional_expenses_prr',['id_application' => $this->id]) ?? [];

        }
    }

    public function idUser(): int
    {
        return $this->idUser;
    }

    public function id()
    {
        return $this->id;
    }

    public function getAdditionalExpensesList()
    {
        return $this->additionalExpensesList;
    }

    public function getPRRPlaceList(): array
    {
        return $this->database->select('prr_place', ['id_application' => $this->id]);
    }

    public function edit(array $values): void
    {
        if(! empty($values['additionalExpenses']))
            $this->additionalExpensesList = [];
        foreach ($values as $key => $value) {
        if($key == 'additionalExpenses'){

            $data = json_decode($value, true);

            foreach ($data as $array) {
                $temp = $array;
                $temp['sum'] = str_replace(' ','',$array['sum']);

                $this->additionalExpensesList[] = $temp;
            }
        }

            if($key == 'placeListClient' || $key == 'placeListPrr'){
                $data = json_decode($value, true);

                foreach ($data as $array) {
                    $newPrrPlace = new PRRPlace();


                    $newPrrPlace->edit($array);
                    if($key == 'placeListClient'){
                        $newPrrPlace->edit(['typeFor' => 1]);
                    }
                    else{
                        $newPrrPlace->edit(['typeFor' => 0]);
                    }
                    $this->placeList[] = $newPrrPlace;
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


    private function getDeduction(): array
    {
        $additionalExpensesList = $this->additionalExpensesList;
        $sumExpenses = ['cash' => 0, 'withoutVAT' => 0];


        foreach ($additionalExpensesList as $additionalExpenses) {
//            $additionalExpenses = $additionalExpenses->get();
            if(isset($additionalExpenses['type-expenses'])){
                $additionalExpenses['type_expenses'] = $additionalExpenses['type-expenses'];
                $additionalExpenses['type_payment'] = $additionalExpenses['type-payment'];
            }
            $additionalExpenses['sum'] = (float)$additionalExpenses['sum'];
            if($additionalExpenses['type_expenses'] != 'Вычет')
                continue;
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

        return $sumExpenses;
    }

    private function countSumAdditionalExpenses(): array
    {

        $additionalExpensesList = $this->additionalExpensesList;
        $sumExpenses = ['cash' => 0, 'withoutVAT' => 0];


        foreach ($additionalExpensesList as $additionalExpenses) {
//            $additionalExpenses = $additionalExpenses->get();

            if(isset($additionalExpenses['type-expenses'])){
                $additionalExpenses['type_expenses'] = $additionalExpenses['type-expenses'];
                $additionalExpenses['type_payment'] = $additionalExpenses['type-payment'];
            }
            $additionalExpenses['sum'] = (float)$additionalExpenses['sum'];
            if($additionalExpenses['type_expenses'] == 'Вычет')
                continue;
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

        return $sumExpenses;
    }

    public function getWalrus(): float
    {
        $costClient = $this->costClient;
        $costPrr = $this->costPrr;

        $taxationTypeClient = $this->taxationTypeClient;

        $taxationTypePrr = $this->taxationTypePrr;

        $walrus = 0;
        $additionalProfit = ['cash' => 0, 'withoutVAT' => 0];
        $additionalExpenses = $this->countSumAdditionalExpenses();
        $deduction = $this->getDeduction();

        switch ($this->customerIdClient):
            case 1:
                if($taxationTypeClient == 'С НДС' AND $taxationTypePrr == 'Б/НДС'){
                    $clientVAT = $costClient * 20.0 / 120.0;

                    $revenueClient = $costClient - $clientVAT - $deduction['cash'] / 0.75;

                    $revenueDifference = $revenueClient - $costPrr +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];

                    $incomeTax = 0.25;

                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $walrus = $revenueDifference - ($revenueDifference * $incomeTax);
                    }

                    $walrus -= $additionalExpenses['cash'] / 0.75;

                }

                if(
                    $taxationTypeClient == 'С НДС' AND (
                        $taxationTypePrr == 'С НДС' OR $taxationTypePrr == 'НДС 5%' OR $taxationTypePrr == 'НДС 7%'
                    )
                ){
                    $clientVAT = $costClient * 20.0 / 120.0;

                    $revenueClient = $costClient - $clientVAT - $deduction['cash'] / 0.75;


                    $PrrVAT = $costPrr * 20.0 / 120.0;

                    if($taxationTypePrr == 'НДС 5%')
                        $PrrVAT = $costPrr * 5.0 / 105.0;

                    if($taxationTypePrr == 'НДС 7%')
                        $PrrVAT = $costPrr * 7.0 / 107.0;

                    $revenuePrr = $costPrr - $PrrVAT;


                    $revenueDifference = $revenueClient - $revenuePrr +
                        $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];

                    $incomeTax = 0.25;


                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $walrus = $revenueDifference - ($revenueDifference * $incomeTax);
                    }

                    $walrus -= $additionalExpenses['cash'] / 0.75;

                }

                if($taxationTypeClient == 'НДС 0%' AND $taxationTypePrr == 'НДС 0%'){

//                    $revenueDifference = $costClient - $costPrr + $additionalProfit['withoutVAT']
//                        - $additionalExpenses['withoutVAT'];
                    $revenueDifference = $costClient - $costPrr - $additionalExpenses['withoutVAT'];

                    $incomeTax = 0.25;


                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $walrus = $revenueDifference - ($revenueDifference * $incomeTax);
                    }


                    $walrus -= $additionalExpenses['cash'] / 0.75;
                }
                break;
            case 2:
            case 3:
                if($taxationTypeClient == 'Б/НДС' AND $taxationTypePrr == 'Б/НДС'
                    OR $taxationTypeClient == 'Б/НДС' AND $taxationTypePrr == 'С НДС'){

//                    $revenueDifference = $costClient - $costPrr + $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];
                    $revenueDifference = $costClient - $costPrr - $additionalExpenses['withoutVAT'];


                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $walrus = $revenueDifference - ($revenueDifference * 8.0 / 100.0);
                    }

                    $walrus -= $additionalExpenses['cash'] / 0.9;
                }

            if($taxationTypeClient == 'Б/НДС' AND $taxationTypePrr == 'НАЛ'){

                $revenueDifference = $costClient - $additionalExpenses['withoutVAT'];


                if($revenueDifference < 0){
                    $walrus = $revenueDifference;
                }
                else{
                    $walrus = $revenueDifference - ($revenueDifference * 8.0 / 100.0);
                }

                $walrus -= $costPrr / 0.9;

                $walrus -= $additionalExpenses['cash'] / 0.9;
            }

                break;
        endswitch;

        return  $walrus;

    }

    private function getNetProfit(float $walrus = 0): float
    {
        if($walrus < 0)
            return $walrus;

        $managerShare = $this->getManagerShare($walrus);

        return $walrus - $managerShare;
    }

    private function getManagerShare(float $walrus = 0): float
    {
        if($walrus < 0)
            return 0;


        $idManager = $this->idUser;

        $date = date('Y-m-d', strtotime($this->date));

        $manager = new User(['id' => $idManager]);

        $managerROP = in_array($manager->id(), [2,7,17]);


        if($date > date('Y-m-d', strtotime('2024-11-19')) AND !$managerROP){

            if(date('d', strtotime($this->date)) < 20){
                $date_start_plan = date("Y-m-20", strtotime("-1 month", strtotime($this->date)));
                $date_end_plan = date("Y-m-19");
            }
            else{
                $date_start_plan = date("Y-m-20");
                $date_end_plan = date("Y-m-19", strtotime("+1 month", strtotime($this->date)));
            }

            $planExecutionManager = $this->database->first('plan_execution_managers',[
                'id_user' => $idManager,
                'date_start' => $date_start_plan,
                'date_end' => $date_end_plan
            ]);

            if(! $planExecutionManager){
                $managerPercent = $manager->procent() / 100.0;
            }

            else
                $managerPercent = $planExecutionManager['percent'] / 100.0;
//            $managerPercent = 0;
        }
        else{
            $managerPercent = $manager->procent() / 100.0;

        }


        $taxPercent = 0.07;

        if($this->customerIdClient == 1)
            $taxPercent = 0.14;

        $walrus_withoutTax = $walrus - ($walrus * $taxPercent);


        return $walrus_withoutTax * $managerPercent;
    }

    private function getSetLastApplicationNumber(){
        $lastNumber = $this->database->first("document_flow")["application_prr_num"];

        $newNumber = $lastNumber + 1;

        if ($this->database->update("document_flow", ["application_prr_num" => $newNumber], ["id" => 1])){
            return $newNumber;
        }
        return null;
    }

    public function save(): bool
    {
        $newData = $this->get();



        if ($this->id > 0) {
            $newData['application_walrus'] = $this->getWalrus();

            $newData['manager_share'] = $this->getManagerShare($newData['application_walrus']);
            $newData['application_net_profit'] = $this->getNetProfit($newData['application_walrus']);
            $stmt = $this->database->update("prr_application", $newData, ["id" => $this->id]);

            if (!empty($this->placeList)){

                $this->database->delete("prr_place", ["id_application" =>  $this->id]);
                foreach($this->placeList as $placePrr) {
                    $placePrr->edit(['id_application' => $this->id]);
                    $placePrr->save();
                }
            }

            if (!empty($this->additionalExpensesList)) {
                $this->database->delete('additional_expenses_prr', ['id_application' => $this->id]);


                foreach ($this->additionalExpensesList as $additionalExpense) {
                    $this->database->insert('additional_expenses_prr',
                        [
                            'id_application' => $this->id,
                            'type_expenses' => $additionalExpense['type-expenses'],
                            'sum' => $additionalExpense['sum'],
                            'type_payment' => $additionalExpense['type-payment'],
                            'comment' => $additionalExpense['comment'],
                        ]
                    );
                }
            }

            return $stmt;
        }
        else{
            $this->date = date("Y-m-d H:i:s");
            $this->dateLastUpdate = date("Y-m-d H:i:s");
            $this->applicationNumber = $this->getSetLastApplicationNumber() .'-П';

            $newData = $this->get();

            $newData['application_walrus'] = $this->getWalrus();

            $newData['manager_share'] = $this->getManagerShare($newData['application_walrus']);
            $newData['application_net_profit'] = $this->getNetProfit($newData['application_walrus']);

            $stmt = $this->database->insert("prr_application", $newData);


            if ($stmt) {
                $this->id = (int)$stmt;


                $this->database->delete('additional_expenses_prr', ['id_application' => $this->id]);

                $id_application = (int)$stmt;
                foreach($this->placeList as $placePrr) {
                    $placePrr->edit(['id_application' => $id_application]);
                    $placePrr->save();
                }

                foreach ($this->additionalExpensesList as $additionalExpense) {
                    $this->database->insert('additional_expenses_prr',
                        [
                            'id_application' => $this->id,
                            'type_expenses' => $additionalExpense['type-expenses'],
                            'sum' => $additionalExpense['sum'],
                            'type_payment' => $additionalExpense['type-payment'],
                            'comment' => $additionalExpense['comment'],
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
        if ($this->id === 0){
            return false;
        }

        $prrPlaceList = $this->getPRRPlaceList();
        $this->id = 0;
        $this->applicationNumber = $this->getSetLastApplicationNumber();
        $this->date = date("Y-m-d H:i:s");
        $this->dateLastUpdate = date("Y-m-d H:i:s");
        $this->receiptServicesNum = 0;

        $additionalExpensesList = $this->getAdditionalExpensesList();

        foreach ($additionalExpensesList as $key => $additionalExpense) {
            $additionalExpensesList[$key]['type-expenses'] = $additionalExpense['type_expenses'];
            $additionalExpensesList[$key]['type-payment'] = $additionalExpense['type_payment'];
        }

        $prrPlaceClient = [];
        $prrPlacePRR = [];

        foreach ($prrPlaceList as $prrPlace) {

            $prrPlace['id_application'] = 0;
            $prrPlace['id'] = 0;

            if($prrPlace['type_for']){
                $prrPlacePRR[] = $prrPlace;
            }
            else{
                $prrPlaceClient[] = $prrPlace;
            }
        }

        $this->edit([
            'placeListClient' => json_encode($prrPlaceClient),
            'placeListPrr' => json_encode($prrPlacePRR),
            'additionalExpenses' => json_encode($additionalExpensesList)
        ]);



        if(!$this->save())
            return false;

        return $this->id();
    }
}