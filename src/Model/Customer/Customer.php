<?php

namespace App\Model\Customer;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;
use App\Storage\Storage;

class Customer extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private Storage $storage;

    private int $id = 0;
    private string $name = '';

    private string $inn = '';

    private string $mailing_address = '';
    private string $legal_address = '';
    private string $contact_face = '';

    private string $initials = '';
    private string $phone = '';
    private string $link_seal = '';
    private string $link_signature = '';
    private string $visible = '';


    public array $fields = [
        "id","name","inn", "mailing_address",
        "legal_address", "contact_face", "initials", "phone", "link_seal",
        "link_signature", "visible"
    ];

    public function __construct(array $data = []){
        $this->config = new Config();
        $this->database = new Database($this->config);
        $this->storage = new Storage($this->config);

        if(count($data) > 0){
            $condition = $this->database->first('customers',$data);

            if($condition){
                foreach ($condition as $key => $value) {
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

    public function get(array $conditions = []): array|string
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                switch($field){
                    case 'link_seal':
                        $data[$field] = $this->getSeal();
                        break;
                    case 'link_signature':
                        $data[$field] = $this->getSignature();
                        break;
                    default:
                        $data[$field] = $this->$field;
                        break;
                }

            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                switch($value){
                    case 'link_seal':
                        $returnedArray[$value] = $this->getSeal();
                        break;
                    case 'link_signature':
                        $returnedArray[$value] = $this->getSignature();
                        break;
                    default:
                        $returnedArray[$value] = $this->$value;
                        break;
                }

            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }
    public function getSeal(): string
    {
        return  $this->storage->url($this->link_seal);
    }

    public function getSignature(): string
    {
        return  $this->storage->url($this->link_signature);
    }

    public function exists(): bool
    {
        if(! $this->id){
            return false;
        }

        if(! $this->database->first('customers',['id' => $this->id])){
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
                'customers',
                $data,
                ['id' => $this->id],
            );
        }
        else{
            $stmt = $this->database->insert(
                'customers',
                $data
            );
            if($stmt)
                $this->id = $stmt;
        }


        return $stmt;
    }
}