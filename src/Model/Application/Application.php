<?php

namespace App\Model\Application;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Addition\Addition;
use App\Model\AdditionalExpenses\AdditionalExpenses;
use App\Model\AdditionalProfit\AdditionalProfit;
use App\Model\Client\Client;
use App\Model\Condition\Condition;
use App\Model\Fines\Fines;
use App\Model\Marshrut\Marshrut;
use App\Model\Model;
use App\Model\User\User;

use function PHPSTORM_META\map;

class Application extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;
    private int $id = 0;
    private string $date = '';
    private int $customerIdCarrier = 0;
    private string $applicationTitleCarrier = '';
    private int $carrierIdCarrier = 0;
    private int $customerIdClient = 0;
    private string  $applicationTitleClient = '';
    private string $proxyClient = '';
    private int $clientIdClient = 0;
    private int $driverIdCarrier = 0;
    private int $driverIdClient = 0;
    private string $carBrandIdCarrier = '';
    private string $governmentNumberCarrier = '';
    private string $semitrailerCarrier = '';
    private string $typeTransportIdCarrier = '';
    private string $typeCarcaseIdCarrier = '';
    private string $typeCarcaseIdClient = '';
    private string $carBrandIdClient = '';
    private string $governmentNumberClient = '';
    private string $semitrailerClient = '';
    private string $typeTransportIdClient = '';

    private string $natureCargoCarrier = '';
    private string $natureCargoClient = '';

    private string $placeCarrier = '';
    private string $placeClient = '';

    private string $weightCarrier = '';
    private string $weightClient = '';

    private string $refModeCarrier = '';
    private string $refModeClient = '';

    private string $specialConditionsCarrier = '';
    private string $specialConditionsClient = '';

    public array $transportationList = [];
    public array $finesList = [];

    public array $additionalExpensesList = [];

    public array $additionalProfitList = [];
    private string $termsPaymentCarrier = '';
    private string $termsPaymentClient = '';

    private float $transportationCostCarrier = 0;
    private float $transportationCostClient = 0;

    private int $costCargoClient = 0;

    private string $taxationTypeCarrier = '';
    private string $taxationTypeClient = '';

    private string $prerequisitesCarrier = '';

    private string $addition = '';

    private string $applicationStatus = 'В работе';
    private string $applicationStatusJournal = 'Е.Н.П';
    private string $clientPaymentStatus = 'Ожидается счет';
    private string $carrierPaymentStatus = 'Ожидается счет';
    private string $clientDocumentsStatus = 'Ожидается счет';
    private string $carrierDocumentsStatus = 'Ожидается счет';
    private string $applicationNumber = '';

    private $applicationNumberClient = NULL;

    private int $isClassic = 1;
    private int $showDriverNumber = 1;
    private int $forwardingReceipt = 0;
    private int $applicationCondition = 0;
    private float $applicationWalrus = 0;

    private float $applicationNetProfit = 0;
    private float $managerShare = 0;

    private string $carrierChosenInfo = '';
    private string $clientChosenInfo = '';

    private int $hideTitle = 0;

    private int $attorneyNumber = 0;

    private int $receiptServicesNum = 0;

    private int $idUser = 0;

    private int $cancelled = 0;

    private string $accountNumberClient = '';
    private string $accountNumberCarrier = '';
    private string $updNumberClient = '';
    private string $updNumberCarrier = '';
    private float $actualPaymentClient = 0.0;
    private float $actualPaymentCarrier = 0.0;

    private int $applicationSectionJournal = 1;

    private $fullPaymentDateClient = null;
    private $fullPaymentDateCarrier = null;

    private int $forSales = 0;

    private float $shareForSales = 0;


    public array $fields = [
        "id",
        "date",
        'customerIdCarrier',
        'applicationTitleCarrier',
        'carrierIdCarrier',
        'customerIdClient',
        'applicationTitleClient',
        'proxyClient',
        'clientIdClient',
        'driverIdClient',
        'driverIdCarrier',
        'carBrandIdCarrier',
        'governmentNumberCarrier',
        'semitrailerCarrier',
        'typeTransportIdCarrier',
        'typeCarcaseIdCarrier',
        'typeCarcaseIdClient',
        'carBrandIdClient',
        'governmentNumberClient',
        'semitrailerClient',
        'typeTransportIdClient',
        'natureCargoCarrier',
        'natureCargoClient',
        'placeCarrier',
        'placeClient',
        'weightCarrier',
        'weightClient',
        'refModeCarrier',
        'refModeClient',
        'specialConditionsCarrier',
        'specialConditionsClient',
        'termsPaymentCarrier',
        'termsPaymentClient',
        'transportationCostCarrier',
        'transportationCostClient',
        'costCargoClient',
        'taxationTypeCarrier',
        'taxationTypeClient',
        'prerequisitesCarrier',
        'addition',
        'isClassic',
        'showDriverNumber',
        "applicationStatus",
        "applicationStatusJournal",
        "clientPaymentStatus",
        "carrierPaymentStatus",
        "clientDocumentsStatus",
        "carrierDocumentsStatus",
        "applicationNumber",
        "applicationNumberClient",
        "forwardingReceipt",
        'carrierChosenInfo',
        'clientChosenInfo',
        "attorneyNumber",
        "receiptServicesNum",
        'hideTitle',
        "applicationWalrus",
        'applicationNetProfit',
        'managerShare',
        'idUser',
        'cancelled',
        'accountNumberClient',
        'accountNumberCarrier',
        'updNumberClient',
        'updNumberCarrier',
        'actualPaymentClient',
        'actualPaymentCarrier',
        'applicationSectionJournal',
//        'fullPaymentDateClient',
//        'fullPaymentDateCarrier'
        'forSales',
        'shareForSales'
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $application = $this->database->first("applications", $data);

            foreach ($application as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }

            $additionalProfitDB = $this->database->select('additional_profit', ['id_application' => $this->id],[],-1,['id']);

            foreach ($additionalProfitDB as $profitId) {
                $temp = new AdditionalProfit(['id' => $profitId['id']]);
                $this->additionalProfitList[] = $temp;
            }

            $additionalExpensesDB = $this->database->select('additional_expenses', ['id_application' => $this->id],[],-1,['id']);

            foreach ($additionalExpensesDB as $expensesId) {
                $temp = new AdditionalExpenses(['id' => $expensesId['id']]);
                $this->additionalExpensesList[] = $temp;
            }
//
        }
    }

    public function id()
    {
        return $this->id;
    }


    public function checkForSales():bool 
    {
        $client = new Client(['id' => $this->clientIdClient]);

        $managerAccess = $client->getManagersAccess();
        
        $forSales = 0;

        // var_dump($managerAccess);

        foreach($managerAccess as $user){
            if($user['id'] == 55){
                $forSales = 1;

                break;
            }
        }

        $this->forSales = $forSales;

        return $forSales;
    }

    public function firstForClient(){
        $countApplicationClient = $this->database->first('applications',['client_id_Client' => $this->clientIdClient],['COUNT(id)']);

        if($countApplicationClient['COUNT(id)'] == 0){
            $this->database->update('clients',['date_first_application' => date('Y-m-d H:i:s')],['id' => $this->clientIdClient]);
        }
    }


    public function isDeduction(): bool
    {
        $isDeduction = false;

        foreach ($this->additionalExpensesList as $expenses) {
            if($expenses->get()['type_expenses'] == 'Вычет') {
                $isDeduction = true;
                break;
            }
        }

        return $isDeduction;
    }

    public function getDeductionList(): array
    {
        $listDeduction = [];

        foreach ($this->additionalExpensesList as $expenses) {
            if($expenses->get()['type_expenses'] == 'Вычет') {
                $listDeduction[] = $expenses->get();
            }
        }

        return $listDeduction;
    }

    public function isFullPaymentClient(): bool
    {
        $isFullPayment = false;

        if($this->transportationCostClient == $this->actualPaymentClient AND $this->actualPaymentClient > 0){
            $isFullPayment = true;
        }

        return $isFullPayment;

    }

    public function edit(array $values): void
    {
        if(! empty($values['expensesCarrier']))
            $this->additionalExpensesList = [];

        if(! empty($values['additionalProfit']))
            $this->additionalProfitList = [];



        foreach ($values as $key => $value) {
            if($key == 'transportationCarrier' || $key == 'transportationClient'){

                $data = json_decode($value, true);

                foreach ($data as $array) {
                    $newMarshrut = new Marshrut();

                    $newMarshrut->edit($array);
                    if($key == 'transportationClient'){
                        $newMarshrut->edit(['typeFor' => 1]);
                    }
                    else{
                        $newMarshrut->edit(['typeFor' => 0]);
                    }
                    $this->transportationList[] = $newMarshrut;
                }
            }
            elseif ($key == 'fines'){
                $data = json_decode($value, true);

                foreach ($data as $array) {
                    $fine  = new Fines();

                    $fine->edit($array);

                    $this->finesList[] = $fine;
                }
            }
            elseif($key == 'expensesCarrier' || $key == 'expensesClient'){

                $data = json_decode($value, true);



                foreach ($data as $array) {
                    $newAdditionalExpense = new AdditionalExpenses();
                    $array['sum'] = str_replace(' ','',$array['sum']);

                    $newAdditionalExpense->edit($array);

                    $this->additionalExpensesList[] = $newAdditionalExpense;
                }

            }
            elseif($key == 'additionalProfit'){
                $this->additionalProfitList = [];
                $data = json_decode($value, true);

                foreach ($data as $array) {
                    $newAdditionalProfit = new AdditionalProfit();
                    $array['sum'] = str_replace(' ','',$array['sum']);
                    $newAdditionalProfit->edit($array);

                    $this->additionalProfitList[] = $newAdditionalProfit;
                }
            }
            elseif ($key == 'additionId'){
                if (is_int($value)){
                    $addition = new Addition(['id'=>"$value"]);

                    $additionText = $addition->get(["description"])['description'];
                    $this->addition = $additionText;
                }
                else{
                    $this->addition = $value;
                }
            }
            $newKey = $this->sqlToPhpNameConvert($key);
            $this->$newKey = $value;
        }

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

            if($this->id == 3323)
                $data['application_number'] = $this->applicationNumber .'/1';


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

    private function countSumAdditionalProfit(): array
    {

        $additionalProfitList = $this->additionalProfitList;


        $sumProfit = ['cash' => 0, 'withoutVAT' => 0];

        foreach ($additionalProfitList as $additionalProfit) {
            $additionalProfit = $additionalProfit->get();
            $additionalProfit['sum'] = (float)$additionalProfit['sum'];
            switch ($additionalProfit['type_payment']):
                case  "С НДС":
                    $sumProfit['withoutVAT'] += $additionalProfit['sum'] - $additionalProfit['sum'] / 6;
                    break;
                case "Б/НДС":
                    $sumProfit['withoutVAT'] += $additionalProfit['sum'];
                    break;
                case "НАЛ":
                    $sumProfit['cash'] += $additionalProfit['sum'];
                    break;
            endswitch;

        }

        return $sumProfit;
    }

    private function getDeduction(): array
    {
        $additionalExpensesList = $this->additionalExpensesList;
        $sumExpenses = ['cash' => 0, 'withoutVAT' => 0];


        foreach ($additionalExpensesList as $additionalExpenses) {
            $data = $additionalExpenses->get();
            $data['sum'] = (float)$data['sum'];

            if ($data['type_payment'] != 'НАЛ' OR $this->customerIdClient != 1) {
                continue;
            }

//            var_dump($data);

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

        $additionalExpensesList = $this->additionalExpensesList;
        $sumExpenses = ['cash' => 0, 'withoutVAT' => 0];


        foreach ($additionalExpensesList as $additionalExpenses) {
            $additionalExpenses = $additionalExpenses->get();
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

    public function getWalrus(): float
    {
        $costClient = $this->transportationCostClient;
        $costCarrier = $this->transportationCostCarrier;

        $taxationTypeClient = $this->taxationTypeClient;

        $taxationTypeCarrier = $this->taxationTypeCarrier;

        $walrus = 0;
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

//                  налог на прибыль начиная с 26.08.2025 составляет 5%
                    if(strtotime($this->date) > strtotime('2025-08-26')){
                        $incomeTax = 0.05;
                    }

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

//                  налог на прибыль в 2024 году 20%
                    $incomeTax = 0.2;
//                  налог на прибыль в 2025 25%
                    if(strtotime($this->date) > strtotime('2025-01-01')){
                        $incomeTax = 0.25;
                    }

//                  налог на прибыль начиная с 26.08.2025 составляет 5%
                    if(strtotime($this->date) > strtotime('2025-08-26')){
                        $incomeTax = 0.05;
                    }

                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $walrus = $revenueDifference - ($revenueDifference * $incomeTax);
                    }

                    $walrus -= $additionalExpenses['cash'] / 0.75;

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
//                  налог на прибыль начиная с 26.08.2025 составляет 5%
                    if(strtotime($this->date) > strtotime('2025-08-26')){
                        $incomeTax = 0.05;
                    }

                    if($revenueDifference < 0){
                        $walrus = $revenueDifference;
                    }
                    else{
                        $walrus = $revenueDifference - ($revenueDifference * $incomeTax);
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
                        $walrus = $revenueDifference - ($revenueDifference * $incomeTax);
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
                        $walrus = $revenueDifference - ($revenueDifference * 8.0 / 100.0);
                    }

                    $walrus -= $additionalExpenses['cash'] / 0.9;
                }

            if($taxationTypeClient == 'Б/НДС' AND $taxationTypeCarrier == 'НАЛ'){

                $revenueDifference = $costClient  +
                    $additionalProfit['withoutVAT'] - $additionalExpenses['withoutVAT'];


                if($revenueDifference < 0){
                    $walrus = $revenueDifference;
                }
                else{
                    $walrus = $revenueDifference - ($revenueDifference * 8.0 / 100.0);
                }

                $walrus -= $costCarrier / 0.9;

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

        $ropShare = 0;

        $taxPercent = 0.07;

        if($this->customerIdClient == 1)
            $taxPercent = 0.14;

        $userIsRop = $this->database->superSelect(
            'users',
            ['subordinates' => '%|' .$this->idUser .'|%'],
            [],
            1,
            ['id'],
            0,
            'AND',
            'LIKE'
        );

        if(count($userIsRop) > 0)
            return $walrus - ($walrus  * 0.95 * $taxPercent) - $managerShare;

        return $walrus - $managerShare;
    }

    private function getManagerShare(float $walrus = 0): float
    {
        if($walrus < 0)
            return 0;


        $idManager = $this->idUser;

        $date = date('Y-m-d', strtotime($this->date));

        $manager = new User(['id' => $idManager]);

        $managerROP = in_array($manager->id(), [2,7,17,21]);


        if($date > date('Y-m-d', strtotime($this->date))){
            $managerROP = in_array($manager->id(), [2,7,17]);
        }


        if($date > date('Y-m-d', strtotime('2024-11-19')) AND !$managerROP){


            if(date('d', strtotime($this->date)) < 20){
                $date_start_plan = date("Y-m-20", strtotime("-1 month", strtotime($this->date)));
                $date_end_plan = date("Y-m-19", strtotime($this->date));
            }
            else{
                $date_start_plan = date("Y-m-20", strtotime($this->date));
                $date_end_plan = date("Y-m-19", strtotime("first day of next month", strtotime($this->date)));
            }

            $planExecutionManager = $this->database->first('plan_execution_managers',[
                'id_user' => $idManager,
                'date_start' => $date_start_plan,
                'date_end' => $date_end_plan
            ]);


            if(! $planExecutionManager){
                $managerPercent = $manager->procent() / 100.0;
            }

            else {
                $managerPercent = $planExecutionManager['percent'] / 100.0;
            }
        }
        else{
            $managerPercent = $manager->procent() / 100.0;

        }

        if($this->forSales)
            $managerPercent = 0.15;

        $taxPercent = 0.07;

        if($this->customerIdClient == 1)
            $taxPercent = 0.14;

        $walrus_withoutTax = $walrus - ($walrus * $taxPercent);

        return $walrus_withoutTax * $managerPercent;
    }

    private function getSalesShare(float $walrus = 0): float
    {
        if($walrus < 0)
            return 0;

        if(!$this->forSales)
            return 0;

        $salesPercent = 0.15;

        $taxPercent = 0.07;

        if($this->customerIdClient == 1)
            $taxPercent = 0.14;

        $walrus_withoutTax = $walrus - ($walrus * $taxPercent);

        return $walrus_withoutTax * $salesPercent;
    }


    public function save()
    {
        // Определяется это заявка идет в счет продажника или нет
        $this->checkForSales();

        $newData = $this->get();

        $newData['application_walrus'] = $this->getWalrus();

        $newData['manager_share'] = $this->getManagerShare($newData['application_walrus']);
        $newData['application_net_profit'] = $this->getNetProfit($newData['application_walrus']);


        $newData['share_for_sales'] = $this->getSalesShare($newData['application_walrus']);

        if(empty($this->additionalExpensesList)){
            $tempAdditionalExpenses = new AdditionalExpenses();
            $tempAdditionalExpenses->edit([
                'typeExpenses' => 'Страховка',
                'sum' => 500,
                'typePayment' => 'Б/НДС'
            ]);
            $this->additionalExpensesList[] = $tempAdditionalExpenses;
        }

        if ($this->id > 0) {

            $stmt = $this->database->update("applications", $newData, ["id" => $this->id]);

            if ($stmt){
                $id_application = $this->id;

                if (!empty($this->transportationList)){

                    $this->database->delete("routes", ["id_application" => $id_application]);
                    foreach($this->transportationList as $transportation) {
                        $transportation->edit(['id_application' => $id_application]);
                        $transportation->save();
                    }
                }

                if (!empty($this->finesList)){
                    $this->database->delete("fines", ["id_application" => $id_application]);
                    foreach($this->finesList as $fine) {
                        $fine->edit(['id_application' => $id_application]);
                        $fine->save();
                    }
                }

                if (!empty($this->additionalExpensesList)){
                    $this->database->delete("additional_expenses", ["id_application" => $id_application]);

                    foreach($this->additionalExpensesList as $additionalExpense) {

                        $additionalExpense->edit(['id_application' => $id_application, 'id' => 0]);
                        $additionalExpense->save();
                    }
                }

                if (!empty($this->additionalProfitList)){

                    $this->database->delete("additional_profit", ["id_application" => $id_application]);

                    foreach($this->additionalProfitList as $additionalProfit) {
                        $additionalProfit->edit(['id_application' => $id_application, 'id' => 0]);
                        $additionalProfit->save();
                    }
                }
                return $stmt;
            }
        }
        else{
            $this->date = date("Y-m-d H:i:s");

            $this->applicationNumber = $this->getSetLastApplicationNumber();

            $newData = $this->get();
            $newData['application_walrus'] = $this->getWalrus();

            $newData['manager_share'] = $this->getManagerShare($newData['application_walrus']);
            $newData['application_net_profit'] = $this->getNetProfit($newData['application_walrus']);


            $stmt = $this->database->insert("applications", $newData);

            if ($stmt){
                $id_application = (int)$stmt;
                foreach($this->transportationList as $transportation) {
                    $transportation->edit(['id_application' => $id_application]);
                    $transportation->save();
                }

                foreach($this->finesList as $fine) {
                    $fine->edit(['id_application' => $id_application]);
                    $fine->save();
                }

                foreach($this->additionalExpensesList as $additionalExpense) {
                    $additionalExpense->edit(['id_application' => $id_application]);
                    $additionalExpense->save();
                }
                foreach($this->additionalProfitList as $additionalProfit) {
                    $additionalProfit->edit(['id_application' => $id_application]);
                    $additionalProfit->save();
                }

                if ($stmt) {
                    $this->id = $stmt;
                    $this->firstForClient();
                }
                return $stmt;

            }
        }
    }

    private function getSetLastApplicationNumber(){
        $lastNumber = $this->database->first("document_flow")["application_num"];

        $newNumber = $lastNumber + 1;

        if ($this->database->update("document_flow", ["application_num" => $newNumber], ["id" => 1])){
            return $newNumber;
        }
        return null;
    }

    public function copy(): int|false{
        if ($this->id === 0){
            return false;
        }

        $newData = $this->get();
        $newData['application_walrus'] = $this->getWalrus();

        $newData['manager_share'] = $this->getManagerShare($newData['application_walrus']);
        $newData['application_net_profit'] = $this->getNetProfit($newData['application_walrus']);
        $newData["transportationCarrier"] = "[]";
        $newData["transportationClient"] = "[]";
        $newData["fines"] = "[]";
        $newData["expensesCarrier"] = "[]";
        $newData["expensesClient"] = "[]";
        $newData['full_payment_date_Client'] = NULL;
        $newData['full_payment_amount_Carrier'] = NULL;

        $newApplication = new Application();

        $newApplication->edit($newData);


    // обнуление данных
        $newApplication->edit(["id" => 0]);
        $newApplication->edit(["attorney_number" => 0]);
        $newApplication->edit(["application_section_journal" => 1]);
        $newApplication->edit(["application_status_journal" => 'Е.Н.П']);

        $newApplication->edit([
            'account_number_Client' => '',
            'account_number_Carrier' => '',
            'upd_number_Client' => '',
            'upd_number_Carrier' => '',
            'actual_payment_Client' => 0,
            'actual_payment_Carrier' => 0,
            "cancelled" => 0,
            "account_status_Client" => 0,
            "date_invoice_Client" => null,
            'application_closed_date' => null,
            'full_payment_date_Client' => null,
            'full_payment_date_Carrier' => null,
        ]);


        $marshrutsDB = $this->database->select("routes", ["id_application" => $this->id]);
        $listMarshruts = [];

        foreach($marshrutsDB as $marshrut){
            $oldMarshrut = new Marshrut(['id'=> $marshrut['id']]);

            $newMarshrut = new Marshrut();

            $newMarshrut->edit($oldMarshrut->get());
            $newMarshrut->edit(["id" => 0]);

            $listMarshruts[] = $newMarshrut;
        }

        $finesDB = $this->database->select("fines", ["id_application" => $this->id]);
        $listFines = [];

        foreach($finesDB as $fine){
            $oldFine = new Fines(['id'=> $fine['id']]);

            $newFine = new Fines();
            $newFine->edit($oldFine->get());
            $newFine->edit(["id" => 0]);
            $listFines[] = $newFine;
        }

        $expensesDB = $this->database->select("additional_expenses", ["id_application" => $this->id]);
        $listExpenses = [];

        foreach($expensesDB as $expense){


            $oldExpense = new AdditionalExpenses(['id'=> $expense['id']]);
            $oldExpenseData = $oldExpense->get();

            if($oldExpenseData['type_expenses'] === 'Страховка'){
                $newExpense = new AdditionalExpenses();
                $newExpense->edit($oldExpense->get());
                $newExpense->edit(["id" => 0]);
                $newExpense->edit(["id_application" => 0]);
                $listExpenses[] = $newExpense;
            }

        }
//        var_dump($listExpenses);

//        exit;

        $newApplication->edit(["transportationList" => $listMarshruts, "finesList" => $listFines, "additionalExpensesList" => []]);

        return $newApplication->save();
    }

}