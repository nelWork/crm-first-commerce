<?php

namespace App\User\Model\Analytics;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\User\User;
use App\Model\Model;

class Salary extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;

    private float $salary = 0;

    private float $advance = 0;

    private float $official5 = 0;

    private float $official20 = 0;

    private float $dop = 0;

    private float $fines = 0;

    private $dateStart = null;
    private $dateEnd = null;

    private string $closedApplications = '';
    private string $closedPrrApplications = '';

    private float $percentApplication = 0;
    private float $percentPrrApplication = 0;


    private $subordinatesClosedApplications = null;

    private int $idUser = 0;

    private int $status = 0;

    public array $fields = [
        'id', 'salary', 'advance', 'official5', 'official20',
        'fines', 'dateStart', 'dateEnd', 'closedApplications', 'closedPrrApplications',
        'subordinatesClosedApplications', 'idUser', 'status', 'dop'
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $salary = $this->database->first("salary", $data);

            if (!$salary)
                return false;

            foreach ($salary as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }
        }
    }

    public function countSalarySales(){
        $user = new User(['id' => $this->idUser]);
        $userData = $user->get();

        $conditions = [
            'for_sales' => 1,
            'dateField' => [
                'name' => 'application_closed_date',
                'start' => $this->dateStart,
                'end' => $this->dateEnd
            ]
        ];

        $applicationSalaryDB = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            [
                'id_user', 'manager_share',
                'application_number', 'application_walrus',
                'customer_id_Client','share_for_sales'
            ]
        );
        $salaryPercent = 0;
        $closedApplications = '';

        foreach ($applicationSalaryDB as $application) {
            $salaryPercent += $application['share_for_sales'];
            $this->percentApplication += $application['share_for_sales'];
            $closedApplications .= '|' . $application['application_number'] . '|,';
        }

        $PrrApplicationSalaryDB = $this->database->superSelect(
            'prr_application',
            $conditions,
            ['id' => 'DESC'],
            -1,
            [
                'id_user', 'manager_share',
                'application_number', 'application_walrus',
                'customer_id_Client','share_for_sales'
            ]
        );

        $salaryPercentPRR = 0;
        $closedPrrApplications = '';

        foreach ($PrrApplicationSalaryDB as $application) {
            $salaryPercent += $application['share_for_sales'];
            $salaryPercentPRR += $application['share_for_sales'];
            $this->percentPrrApplication += $application['share_for_sales'];

            $closedPrrApplications .= '|' . $application['application_number'] . '|,';
        }

        if($this->id == 491){
            $this->dop = 6 * 5000;
            // dd($this);
        }

        if($this->id == 553){
            $this->dop = 1 * 5000;
            // dd($this);
        }

        $this->salary = $salaryPercent + $this->dop;
        $this->closedApplications = $closedApplications;
        $this->closedPrrApplications = $closedPrrApplications;

        $this->save();

        // dd($this->salary);
        return $this->salary;
    }


    public function countSalary(array $conditions = [])
    {
        if ($this->idUser == 0)
            return false;


        if($this->idUser == 55){
            return $this->countSalarySales();
            // return true;
        }

        $user = new User(['id' => $this->idUser]);


        $userData = $user->get();
        $salaryUser = $userData['salary'];

        

        if ($salaryUser == 0) {
            $idPlan = $this->database->first(
                'plan_execution_managers',
                [
                    'id_user' => $this->idUser,
                    'date_start' => $this->dateStart,
                    'date_end' => $this->dateEnd
                ]
            )['id_plan'] ?? 0;


            if ($idPlan > 0) {
                $plan = $this->database->first("types_plan", ['id' => $idPlan]);

                $salaryUser = $plan['fix_salary'];
            }

        }

        if(strtotime($this->dateStart) >= strtotime('2025-11-01')){
            $dataSalary = $this->database->first(
                'salary_user_settings',
                ['date_start' => $this->dateStart,'id_user' => $this->idUser]
            );

            if(!empty($dataSalary))
                $salaryUser = $dataSalary['fix_salary'];
        }


        $conditions = [
            'id_user' => $this->idUser,
            'dateField' => [
                'name' => 'application_closed_date',
                'start' => $this->dateStart,
                'end' => $this->dateEnd
            ]
        ];


        if (!empty($user->getSubordinatesList()) AND strtotime($this->dateStart) < strtotime('2025-11-01')) {
            $idSubordinatesList = $user->getSubordinatesList();

            $conditions['id_user'] = [$this->idUser];
            foreach ($idSubordinatesList as $id) {
                $conditions['id_user'][] = $id;
            }
        }


        $applicationSalaryDB = $this->database->superSelect(
            'applications',
            $conditions,
            ['id' => 'DESC'],
            -1,
            [
                'id_user', 'manager_share',
                'application_number', 'application_walrus',
                'customer_id_Client'
            ]
        );

        $PrrApplicationSalaryDB = $this->database->superSelect(
            'prr_application',
            $conditions,
            ['id' => 'DESC'],
            -1,
            [
                'id_user', 'manager_share',
                'application_number', 'application_walrus',
                'customer_id_Client'
            ]
        );


        $salaryPercent = 0;


        $salaryPercentSubordinates = 0;
        $closedApplications = '';
        $subordinatesClosedApplications = '';
        $countSubordinates = 0;
        foreach ($applicationSalaryDB as $application) {
            if ($application['id_user'] == $this->idUser) {
                $salaryPercent += $application['manager_share'];
                $this->percentApplication += $application['manager_share'];
                $closedApplications .= '|' . $application['application_number'] . '|,';
            } else {
                $taxPercent = 0.86;
                if ($application['customer_id_Client'] > 1)
                    $taxPercent = 0.93;

                if ($application['application_walrus'] <= 0)
                    continue;

                $salaryPercent += $application['application_walrus'] * 0.05 * $taxPercent;
                $salaryPercentSubordinates += $application['application_walrus'] * 0.05 * $taxPercent;

                $subordinatesClosedApplications .= '|' . $application['application_number'] . '|,';
            }
        }


        $salaryPercentPrrSubordinates = 0;
        $closedPrrApplications = '';
        $subordinatesClosedPrrApplications = '';
        $countPrrSubordinates = 0;
        foreach ($PrrApplicationSalaryDB as $application) {
            if ($application['id_user'] == $this->idUser) {
                $salaryPercent += $application['manager_share'];
                $this->percentPrrApplication += $application['manager_share'];
                $closedPrrApplications .= '|' . $application['application_number'] . '|,';
            } else {
                $taxPercent = 0.86;
                if ($application['customer_id_Client'] > 1)
                    $taxPercent = 0.93;

                if ($application['application_walrus'] <= 0)
                    continue;

                $salaryPercent += $application['application_walrus'] * 0.05 * $taxPercent;
                $salaryPercentSubordinates += $application['application_walrus'] * 0.05 * $taxPercent;

                $subordinatesClosedPrrApplications .= '|' . $application['application_number'] . '|,';
            }
        }
        // dd($salaryUser);

        $this->salary = $salaryPercent + $salaryUser + $this->dop;
        $this->closedApplications = $closedApplications;
        $this->closedPrrApplications = $closedPrrApplications;

        if ($subordinatesClosedApplications != '') {
            $this->subordinatesClosedApplications = $subordinatesClosedApplications;
        }

        $this->advance = $this->countAdvance();
        $this->official20 = $this->countOfficial20();
        $this->official5 = $this->countOfficial5();
        $this->fines = $this->countFines();


        $this->save();
        return $this->salary;
    }

    public function getPercentApplications(){
        return $this->percentApplication;
    }

    public function getPercentPrrApplications(){
        return $this->percentPrrApplication;
    }

    public function getFixSalary(): float{
        if(strtotime($this->dateStart) >= strtotime('2025-11-01')){
            $dataSalary = $this->database->first(
                'salary_user_settings',
                ['date_start' => $this->dateStart,'id_user' => $this->idUser]
            );

            return $dataSalary['fix_salary'];
        }
        
        return 0;
    }

    private function countAdvance(): float
    {
        return $this->database->first(
            'payments_managers',
            ['id_salary' => $this->id, 'type' => 0]
            , ['SUM(quantity)']
        )['SUM(quantity)'] ?? 0;
    }

    private function countOfficial20(): float
    {
        return $this->database->first(
            'payments_managers',
            ['id_salary' => $this->id, 'type' => 2]
            , ['SUM(quantity)']
        )['SUM(quantity)'] ?? 0;
    }

    private function countOfficial5(): float
    {
        return $this->database->first(
            'payments_managers',
            ['id_salary' => $this->id, 'type' => 1]
            , ['SUM(quantity)']
        )['SUM(quantity)'] ?? 0;
    }

    private function countFines(): float
    {
        return $this->database->first(
            'fines_manager',
            ['id_salary' => $this->id]
            , ['SUM(quantity)']
        )['SUM(quantity)'] ?? 0;
    }


    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $newKey = $this->sqlToPhpNameConvert($key);
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
            foreach ($conditions as $value) {
                $returnedArray[$this->phpToSqlNameConvert($value)] = $this->$value;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    public function save(): bool
    {
        $newData = $this->get();


        if ($this->id > 0) {
            $stmt = $this->database->update("salary", $newData, ["id" => $this->id]);
            return $stmt;
        } else {
            $stmt = $this->database->insert("salary", $newData);
            if ($stmt) {
                $this->id = $stmt;
                return true;
            }

            return $stmt;
        }
    }
}