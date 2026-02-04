<?php
namespace App\User\Model\Analytics;

use App\Database\DatabaseInterface;
use App\User\Model\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Model\Application\ApplicationJournal;
use App\Model\PRR\PRRApplicationJournal;
use DateTime;

use function PHPSTORM_META\map;

class DebtorCreditor extends Model
{

    public function __construct(DatabaseInterface $database){
        parent::__construct($database);
    }


    public function getReportDebCred(): array{
        $conditions = [
            'application_section_journal' => [1,2,3,6],
            'application_status_journal' => ['В пути', 'Выгрузился', 'В работе']
        ];

        $applications = $this->db->superSelect('applications', $conditions);

        $applicationsPrr = $this->db->superSelect('prr_application', $conditions);

        foreach($applicationsPrr as $key => $application){
            $applicationsPrr[$key]['transportation_cost_Carrier'] = $application['cost_Prr'];
            $applicationsPrr[$key]['transportation_cost_Client'] = $application['cost_Client'];
            $applicationsPrr[$key]['actual_payment_Carrier'] = $application['actual_payment_Prr'];
            $applicationsPrr[$key]['carrier_id_Carrier'] = $application['prr_id_Prr'];
            $applicationsPrr[$key]['is_prr'] = true;
        }

        $applications = array_merge($applications,$applicationsPrr);
        

        // dd($applicationsPrr);
        $applications = $this->filterForDebCred($applications,[
            'transportation_cost_Carrier' => 'actual_payment_Carrier',
            'transportation_cost_Client' => 'actual_payment_Client',
        ]);

        $debtor = $this->makePivotTabel($applications);
        $debtor = $this->addPivotTableName($debtor);

        $debtor = $this->addDataPivotTable($debtor);

        $debtor = $this->sortByApplicationsCount($debtor);

        $creditor = $this->makePivotTabel($applications,'carrier_id_Carrier', ['transportation_cost_Carrier' => 'actual_payment_Carrier']);
        $creditor = $this->addPivotTableName($creditor,'getCarrierName');

        $creditor = $this->addDataPivotTable($creditor,['transportation_cost_Carrier' => 'actual_payment_Carrier']);
        $creditor = $this->sortByApplicationsCount($creditor);
        // dd($debtor);
        // dd($creditor);
        return [
            'debtor' => $debtor,
            'creditor' => $creditor,
        ];
    }

    public function filterForDebCred(array $applications = [], array $fieldForFilter = ['transportation_cost_Carrier' => 'actual_payment_Carrier']): array{
        
        foreach($applications as $key => $application){
            $isFullPayment = true;

            foreach($fieldForFilter as $field => $fieldFor){
                if($application[$field] != $application[$fieldFor]){
                    $isFullPayment = false;
                    break;
                }
            }

            if($isFullPayment)
                unset($applications[$key]);
            
        }

        return $applications;
    }

    public function makePivotTabel(
        array $applications = [],
        string $nameField = 'client_id_Client',
        array $nameFieldForSum = ['transportation_cost_Client' => 'actual_payment_Client']
    ): array
    {
        $result = [];

        foreach ($applications as $item) {

            if (!isset($item[$nameField])) {
                continue;
            }

            $key = $item[$nameField];

            // создаём структуру
            if (!isset($result[$key])) {
                $result[$key] = [
                    'items' => [],
                    'sum'   => 0,
                    'id_text_list' => '',
                    'id_prr_text_list' => ''
                ];

                // инициализируем сумму нулями
                foreach ($nameFieldForSum as $field => $fieldFor) {
                    $result[$key]['sum'] = 0;
                }
            }

            $needAddApplication = true;
            // добавляем элемент
            
            // dd($item);
            // считаем суммы
            
            foreach ($nameFieldForSum as $field => $fieldFor) {
                // dump($item,(float)$item[$field] - (float)$item[$fieldFor]);
                if((float)$item[$field] - (float)$item[$fieldFor] > 0){
                    
                    $result[$key]['sum'] += (float)$item[$field] - (float)$item[$fieldFor];
                    if(isset($item['is_prr']))
                        $result[$key]['id_prr_text_list'] .= $item['id'].',';

                    else
                        $result[$key]['id_text_list'] .= $item['id'].',';
                }
                else
                    $needAddApplication = false;
            }

            if($needAddApplication)
                $result[$key]['items'][] = $item;

            if($result[$key]['sum'] == 0)
                unset($result[$key]);
        }

        return $result;
    }


    public function addPivotTableName(array $applications = [],string $nameFunction = 'getClientName') : array {
        $result = [];

        foreach($applications as $key => $application){
            if(isset($application['is_prr'])){
                $name = $this->getPrrName($key);
            }
            else{
                $name = $this->$nameFunction($key);
            }
            $result[$name] = $application;
        }

        return $result;
    }

    public function addDataPivotTable(array $table = [],array $fields = ['transportation_cost_Client' => 'actual_payment_Client']) : array {

        $result = [
            'sum' => 0,
            'quantityCustomer' => count($table),
            'quantityApplication' => 0,
            'list' => [],
            'id_text_list' => '',
            'id_prr_text_list' => ''
        ];

        foreach($table as $key => $customer){

            $result['quantityApplication'] += count($customer['items']);
            foreach($customer['items'] as $application){

                foreach($fields as $field => $fieldFor){
                    $result['sum'] += $application[$field] - $application[$fieldFor];
                    if(isset($application['is_prr']))
                        $result['id_prr_text_list'] .= $application['id'].',';
                    else
                        $result['id_text_list'] .= $application['id'].',';
                }
            }

            $result['list'][$key] = $customer;
        }
        return $result;
    }

    public function sortByApplicationsCount(array $data): array
    {
        // Проверяем, что ключ list существует и это массив
        if (!isset($data['list']) || !is_array($data['list'])) {
            return $data;
        }

        // Сортируем
        uasort($data['list'], function ($a, $b) {
            $countA = isset($a['items']) ? count($a['items']) : 0;
            $countB = isset($b['items']) ? count($b['items']) : 0;

            // Сортировка по убыванию
            return $countB <=> $countA;
        });

        return $data;
    }

}