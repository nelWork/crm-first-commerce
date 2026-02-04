<?php

namespace App\Admin\Model\TypeTransport;

use App\Admin\Model\Model;
use App\Database\DatabaseInterface;
use App\Model\TypeTransport\TypeTransport;

class TypeTransportList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listTypeTransport(): array
    {
        $typeTransports = $this->database->select('type_transport');
        $listTypeTransport = array();

        foreach ($typeTransports as $type) {
            $listTypeTransport[] = new TypeTransport(['id' => $type['id']]);
        }

        return $listTypeTransport;
    }
}