<?php

namespace App\Model\Carrier;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

/** Описание класса CarrierMin
 *
 *
 *  Класс для быстрой работы с перевозчиками, содержит только id и name
 */
class CarrierMin extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;

    private string $name = '';

    public array $fields = [
        'id', 'name'
    ];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $carrier = $this->database->first("carriers", $data);

            foreach ($carrier as $key => $value) {
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
            $stmt = $this->database->update("carriers", $newData, ["id" => $this->id]);
            return $stmt;
        }
        else{
            $stmt = $this->database->insert("carriers", $newData);

            if ($stmt) {
                return true;
            }

            return $stmt;
        }
    }
}