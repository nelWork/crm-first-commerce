<?php

namespace App\Model\File;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class FIleBankStatement extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;
    private int $id = 0;

    private  int $idUserUpload = 0;

    private string $datetimeUpload = '';

    private string $datetimeLastOpen = '';

    private string $name = '';


    private string $link = '';


    public array $fields = [
        "id","idUserUpload","datetimeUpload","datetimeLastOpen","name","link"
    ];

    public function __construct(array $data = [], ){
        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $file = $this->database->first('files_bank_statement',$data);

            if($file){
                foreach ($file as $key => $value) {
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

    public function exists(): bool
    {
        if(! $this->id){
            return false;
        }

        if(! $this->database->first('files_bank_statement',['id' => $this->id])){
            return false;
        }

        return true;

    }
    public function save()
    {
        $data = $this->get();

        if($this->id > 0){
            $stmt = $this->database->update(
                'files_bank_statement',
                $data,
                ['id' => $this->id],
            );
            return $stmt;
        }
        else{
            $stmt = $this->database->insert(
                'files_bank_statement',
                $data
            );

            if($stmt){
                $this->id = $stmt;
                return true;
            }
            return $stmt;
        }
    }
}