<?php

namespace App\User\Model\Base;

use App\Database\DatabaseInterface;
use App\Model\Carrier\Carrier;
use App\Model\Client\Client;
use App\User\Model\Model;

class Base extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }


    public function getClientsList(int $page = 1, array $conditions = [], array $order = ['id' => 'DESC'], int $limit = 20): array
    {
        $clientsDatabaseId = $this->database->superSelect(
            'clients',
            $conditions,
            $order,
            $limit,
            ['id'],
            ($page - 1) * $limit,
            'AND',
            'LIKE'
        );

        if(!$clientsDatabaseId)
            $clientsDatabaseId = [];

        $clientsList = [];

        foreach ($clientsDatabaseId as $clientId) {
            $temp = new Client(['id'=>$clientId['id']]);

            $data = $temp->get();
            $data['last_comment'] = $temp->getLastComment();

            $clientsList[] = $data;
        }

        return $clientsList;
    }

    public function countPageClients(int $elementsOnPage = 20, array $conditions = [])
    {
        $elements = $this->database->superSelect('clients', $conditions, [] , -1, ['COUNT(*)']);

        if(!$elements)
            return 0;

        
        return ceil($elements[0]['COUNT(*)'] / $elementsOnPage);
    }


    public function getCarriersList(int $page = 1, array $conditions = [], array $order = ['id' => 'DESC'], int $limit = 20): array
    {

        return $this->database->superSelect(
            'carriers',
            $conditions,
            $order,
            $limit,
            ['id','name','inn','info'],
            ($page - 1) * $limit
        );
    }

    public function countPageCarriers(int $elementsOnPage = 20, array $conditions = [])
    {
        $elements = $this->database->superSelect('carriers', $conditions, [] , -1, ['COUNT(*)']);

        return ceil($elements[0]['COUNT(*)'] / $elementsOnPage);
    }
}