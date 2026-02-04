<?php

namespace App\User\Model\Journal;

use App\Database\DatabaseInterface;
use App\User\Model\Model;

class ApplicationList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }


    public function journalList(array $conditions = []): array
    {
        $applications = $this->database->superSelect('applications',$conditions);



        return [];
    }
}