<?php

namespace App\Model\ContactFace;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class ContactFace extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;
    private int $id = 0;

    private string $name = '';

    private string $job = '';

    private string $phone = '';

    private string $email = '';

    private int $client_id = 0;
//    private int $carrier_id = 0;

    private string $ati = '';

    public array $fields = [
        "id", "name", "job", "phone",
        "email", "clientId",'ati'
//        "carrierId"
    ];

    public function __construct(array $data = []){
        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $contactFaces = $this->database->first('contact_faces',$data);

            if($contactFaces){
                foreach ($contactFaces as $key => $value) {
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
                'contact_faces',
                $newData,
                ['id' => $this->id],
            );
        }
        else{
            $stmt = $this->database->insert(
                'contact_faces',
                $newData
            );
            if ($stmt){
                $this->id = (int)$stmt;
            }
        }


        return $stmt;
    }
}