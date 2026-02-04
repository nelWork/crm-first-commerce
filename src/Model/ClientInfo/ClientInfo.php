<?php

namespace App\Model\ClientInfo;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class ClientInfo extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;

    private int $clientId  = 0;

    private string $clientCharacter = '';

    private string $accountantStandardContract = '';

    private string $accountantOriginalsContract = '';

    private string $accountantSendingClosingDocuments = '';

    private string $accountantAvailabilityTtnTrn = '';

    private string $accountantSendingTtnTrnEr = '';

    private string $accountantText = '';

    private string $qualityDepartmentText = '';
    private string $lawyersText =  '';

    public array $fields = [
        'id',
        'clientId',
        'clientCharacter',
        'accountantStandardContract',
        'accountantOriginalsContract',
        'accountantSendingClosingDocuments',
        'accountantAvailabilityTtnTrn',
        'accountantSendingTtnTrnEr',
        'accountantText',
        'qualityDepartmentText',
        'lawyersText'
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $client_info = $this->database->first("clients_info", $data);

            if(! $client_info)
                return false;

            foreach ($client_info as $key => $value) {
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
            $stmt = $this->database->update("clients_info", $newData, ["id" => $this->id]);
            return $stmt;
        }
        else{
            $stmt = $this->database->insert("clients_info", $newData);

            if ($stmt) {
                return true;
            }

            return $stmt;
        }
    }
}