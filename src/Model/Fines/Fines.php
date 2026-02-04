<?php

namespace App\Model\Fines;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class Fines extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;
    private int $id = 0;

    private int $sum = 0;

    private string $description = '';

    private string $datetime = '';

    private int $typeFor = 0;

    private int $idApplication = 0;


    public array $fields = [
        "sum", "description", "datetime",
        "typeFor", "idApplication"
    ];

    public function __construct(array $data = []){
        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $fines = $this->database->first('fines',$data);

            if($fines){
                foreach ($fines as $key => $value) {
                    $newKey = $this->sqlToPhpNameConvert($key);
                    $this->$newKey = $value;
                }
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

    public function get(array $conditions = []): array
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $newField = $this->phpToSqlNameConvert($field);
                $data[$newField] = $this->$field;
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $newValue = $this->phpToSqlNameConvert($value);
                $returnedArray[$newValue] = $this->$value;
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

        if($this->id > 0){
            $stmt = $this->database->update(
                'fines',
                $newData,
                ['id' => $this->id],
            );
        }
        else{
            $stmt = $this->database->insert(
                'fines',
                $newData
            );
        }


        return $stmt;
    }
}