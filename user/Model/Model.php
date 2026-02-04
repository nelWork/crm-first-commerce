<?php

namespace App\User\Model;
use App\Database\DatabaseInterface;


class Model
{
    public DatabaseInterface $db;

    public function __construct(DatabaseInterface $database){
        $this->db = $database;
    }

    public function getClientName(int $clientId): string
    {
        return $this->db->first('clients',['id' => $clientId],['name'])['name'];
    }

    public function getCarrierName(int $carrierId): string{
        return $this->db->first('carriers',['id' => $carrierId],['name'])['name'];
    }

    public function getPrrName(int $prrId): string{
        return $this->db->first('prr_company',['id' => $prrId],['name'])['name'];
    }
}