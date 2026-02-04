<?php

namespace App\Model\Driver;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class DriverCar extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;

    private int $idDriver = 0;

    private string $carBrand = '';
    private string $governmentNumber = '';
    private string $semitrailer = '';
    private string $typeTransport = '';
    private string $typeCarcase = '';


    public array $fields = [
        'id', 'idDriver', 'carBrand', 'governmentNumber',
        'semitrailer', 'typeTransport', 'typeCarcase'
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $driverCar = $this->database->first("drivers_cars", $data) ?? [];

            if(! $driverCar){
                return false;
            }

            foreach ($driverCar as $key => $value) {
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


    public function save()
    {
        $newData = $this->get();

        if ($this->id > 0) {
            $stmt = $this->database->update("drivers_cars", $newData, ["id" => $this->id]);
            return $stmt;
        }
        else{
            $stmt = $this->database->insert("drivers_cars", $newData);
            if ($stmt) {
                $this->id = $stmt;
                return true;
            }

            return $stmt;
        }
    }
}