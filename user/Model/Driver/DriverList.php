<?php

namespace App\User\Model\Driver;

use App\Database\DatabaseInterface;
use App\Model\Driver\Driver;
use App\Model\Model;

class DriverList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listDrivers(): array
    {
        $driversDB = $this->database->select('drivers', [], [], -1, ['id']);
        $listDrivers = array();

        foreach ($driversDB as $driver) {
            $listDrivers[] = new Driver(['id' => $driver['id']]);
        }

        return $listDrivers;
    }

    public function simpleListDrivers(array $condition = []): array
    {
        return $this->database->select('drivers', $condition, [], -1, ['id','name']) ?? [];
    }
}