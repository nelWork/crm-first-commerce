<?php

namespace App\Model\Notification;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class Notification extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;
    private int $idFromUser = 0;

    private int $idApplication = 0;
    private int $idToUser = 0;

    private string $applicationNumber = '';
    private  string $name = '';
    private  string $text = '';

    private int $status = 0;

    private string $date = '';


    private int $forClient = 0;



    public array $fields = [
        "id","idFromUser","idToUser","idApplication","applicationNumber","name","text","status","date","forClient"
    ];


    public function __construct(array $data = []){
        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $task = $this->database->first('notifications',$data);

            if($task){
                foreach ($task as $key => $value) {
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

        if(! $this->database->first('notifications',['id' => $this->id])){
            return false;
        }

        return true;

    }
    public function save()
    {
        $data = $this->get();

        if($this->id > 0){
            $stmt = $this->database->update(
                'notifications',
                $data,
                ['id' => $this->id],
            );
        }
        else{
            $stmt = $this->database->insert(
                'notifications',
                $data
            );
            if($stmt)
                $this->id = $stmt;
        }


        return $stmt;
    }

}