<?php

namespace App\Model\TSApplication;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class Forwarder extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;

    private string $name = '';

    private string $inn = '';

    private string $legalAddress = '';

    private string $contact = '';

    public array $fields = [
        'id', 'name', 'inn', 'legalAddress', 'contact'
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $forwarders = $this->database->first("forwarders", $data);

            if(! $forwarders)
                return false;

            foreach ($forwarders as $key => $value) {
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
            $stmt = $this->database->update("forwarders", $newData, ["id" => $this->id]);
            return $stmt;
        }
        else{
            $stmt = $this->database->insert("forwarders", $newData);

            if ($stmt) {
                $this->id = $stmt;
                return true;
            }

            return $stmt;
        }
    }
}