<?php

namespace App\Model\File;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class File extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;
    private int $id = 0;

    private  string $name = '';

    private string $link = '';

    private string $date = '';

    private int $applicationId = 0;

    private int $documentId = 0;

    private int $clientId = 0;

    private string $prefix = '';


    public array $fields = [
        "id","name", "link", "date", 'applicationId', 'documentId', 'clientId'
    ];

    public function __construct(array $data = [], string $prefix = ''){
        $this->prefix = $prefix;
        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $file = $this->database->first($this->prefix .'files',$data);

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

        if(! $this->database->first($this->prefix .'files',['id' => $this->id])){
            return false;
        }

        return true;

    }
    public function save()
    {
        $data = $this->get();

        if($this->id > 0){
            $stmt = $this->database->update(
                $this->prefix .'files',
                $data,
                ['id' => $this->id],
            );
            return $stmt;
        }
        else{
            $stmt = $this->database->insert(
                $this->prefix .'files',
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