<?php

namespace App\User\Model\PRR;

use App\Database\DatabaseInterface;
use App\Model\PRR\PRRCompany;
use App\User\Model\Model;

class PRRCompanyList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listPRRCompany(): array
    {
        $carriersDB = $this->database->select('prr_company', [], [], -1, ['id']);
        $listCarriers = array();

        foreach ($carriersDB as $carrier) {
            $listCarriers[] = new PRRCompany(['id' => $carrier['id']]);
        }

        return $listCarriers;
    }

    public function simpleListPRRCompany(): array
    {
        return $this->database->select('prr_company', [], [], -1, ['id','name']);
    }
}