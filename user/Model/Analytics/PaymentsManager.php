<?php

namespace App\User\Model\Analytics;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class PaymentsManager extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;
    private int $idUserCreate = 0;
    private int $idManager = 0;

    private int $type = 0;

    private float $quantity = 0;

    private int $idSalary = 0;

    private $dateCreate = null;

    public array $fields = [
        'id', 'idUserCreate', 'idManager', 'type', 'quantity','idSalary' , 'dateCreate'
    ];
    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $paymentsManager = $this->database->first("payments_managers", $data);

            if(! $paymentsManager)
                return false;

            foreach ($paymentsManager as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }
        }
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
            foreach ($conditions as  $value) {
                $returnedArray[$this->phpToSqlNameConvert($value)] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    public function save(): bool
    {
        $newData = $this->get();

        if ($this->id > 0) {
            $stmt = $this->database->update("payments_managers", $newData, ["id" => $this->id]);
            return $stmt;
        }
        else{
            $stmt = $this->database->insert("payments_managers", $newData);
            if ($stmt) {
                $this->id = $stmt;
                return true;
            }

            return $stmt;
        }
    }
}