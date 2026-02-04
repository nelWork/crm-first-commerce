<?php

namespace App\Model\Task;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class Task extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;
    private int $id = 0;

    private  string $name = '';

    private string $createDatetime = '';

    private string $deadline = '';

    private string $dateCompletion = '';

    private string $comment = '';

    private int $executorId = 0;

    private int $status = 0;

    private int $applicationId = 0;

    private string $prefix = '';


    public array $fields = [
        "id","name", "createDatetime", "deadline", "dateCompletion", 'comment', 'executorId', 'status', 'applicationId'
    ];

    public function __construct(array $data = [],string $prefix = ''){

        $this->prefix = $prefix;

        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $task = $this->database->first($this->prefix .'tasks',$data);

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

        if(! $this->database->first($this->prefix .'tasks',['id' => $this->id])){
            return false;
        }

        return true;

    }
    public function save()
    {
        $data = $this->get();

        if($this->id > 0){
            $stmt = $this->database->update(
                $this->prefix .'tasks',
                $data,
                ['id' => $this->id],
            );
        }
        else{
            $stmt = $this->database->insert(
                $this->prefix .'tasks',
                $data
            );
            if($stmt)
                $this->id = $stmt;
        }


        return $stmt;
    }
}