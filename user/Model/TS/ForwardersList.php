<?php

namespace App\User\Model\TS;

use App\Database\DatabaseInterface;
use App\Model\TSApplication\Forwarder;
use App\User\Model\Model;

class ForwardersList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listForwarders(): array
    {
        $carriersDB = $this->database->select('forwarders', [], [], -1, ['id']);
        $listCarriers = array();

        foreach ($carriersDB as $carrier) {
            $listCarriers[] = new Forwarder(['id' => $carrier['id']]);
        }

        return $listCarriers;
    }

    public function simpleListForwarders(): array
    {
        return $this->database->select('forwarders', [], [], -1, ['id','name', 'inn']);
    }
}