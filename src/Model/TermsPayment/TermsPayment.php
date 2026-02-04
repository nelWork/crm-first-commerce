<?php

namespace App\Model\TermsPayment;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;

class TermsPayment
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;
    private string $name = '';
    private string $description = '';

    private int $type_for = 0;

    private string $type_taxation = '';

    public array $fields = [
        "id","name", "description", "type_for", "type_taxation"
    ];

    public function __construct(array $data = []){
        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $termsPayment = $this->database->first('terms_payment',$data);

            if($termsPayment){
                foreach ($termsPayment as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    public function get(array $conditions = []): array
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $data[$field] = $this->$field;
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $returnedArray[$value] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    public function exists(): bool
    {
        if(! $this->id){
            return false;
        }

        if(! $this->database->first('terms_payment',['id' => $this->id])){
            return false;
        }

        return true;

    }

    public function save(): bool
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->$field;
        }
        if($this->id > 0){
            $stmt = $this->database->update(
                'terms_payment',
                $data,
                ['id' => $this->id],
            );
        }
        else{
            $stmt = $this->database->insert(
                'terms_payment',
                $data
            );
            if($stmt)
                $this->id = $stmt;
        }


        return $stmt;
    }

}