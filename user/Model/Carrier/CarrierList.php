<?php

namespace App\User\Model\Carrier;

use App\Database\DatabaseInterface;
use App\Model\Carrier\Carrier;
use App\User\Model\Model;

class CarrierList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listCarriers(): array
    {
        $carriersDB = $this->database->select('carriers', [], [], -1, ['id']);
        $listCarriers = array();

        foreach ($carriersDB as $carrier) {
            $listCarriers[] = new Carrier(['id' => $carrier['id']]);
        }

        return $listCarriers;
    }

    public function simpleListCarriers(): array
    {
        return $this->database->select('carriers', [], [], -1, ['id','name','inn']);
    }
}