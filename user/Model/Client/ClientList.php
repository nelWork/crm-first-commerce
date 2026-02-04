<?php

namespace App\User\Model\Client;

use App\Database\DatabaseInterface;
use App\Model\Client\Client;
use App\Model\User\User;
use App\User\Model\Model;

class ClientList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listClients(): array
    {
        $clientsDB = $this->database->select('clients', [], [], -1, ['id']);
        $listClients = array();

        foreach ($clientsDB as $client) {
            $listClients[] = new Client(['id' => $client['id']]);
        }

        return $listClients;
    }

    public function simpleListClients(int $idUser = 0): array
    {
        if ($idUser != 0)
            return $this->database->select('clients', ['users_access' => '%|' .$idUser .'|%'], [], -1, ['id','name','inn'], 0,'AND', 'LIKE');

        return $this->database->select('clients', [], [], -1, ['id','name','inn']);
    }
}