<?php

namespace App\Model\Driver;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class DriverMin extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;

    private string $name = '';

    private string $driverLicense = "";

    private string $phone = '';

    private string $passportSerialNumber = '';

    private string $issuedBy = '';

    private string $issuedDate = '';

    private string $departmentCode = '';

    public array $fields = [
        'id', 'name'
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $driver = $this->database->first("drivers", $data);

            foreach ($driver as $key => $value) {
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
            $stmt = $this->database->update("drivers", $newData, ["id" => $this->id]);
            return $stmt;
        }
        else{
            $stmt = $this->database->insert("drivers", $newData);

            if ($stmt) {
                return true;
            }

            return $stmt;
        }
    }
}