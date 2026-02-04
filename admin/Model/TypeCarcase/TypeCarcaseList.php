<?php

namespace App\Admin\Model\TypeCarcase;

use App\Admin\Model\Model;
use App\Database\DatabaseInterface;
use App\Model\TypeCarcase\TypeCarcase;

class TypeCarcaseList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listTypeCarcase(): array
    {
        $typeCarcase = $this->database->select('type_carcase');
        $listTypeCarcase = array();

        foreach ($typeCarcase as $type) {
            $listTypeCarcase[] = new TypeCarcase(['id' => $type['id']]);
        }

        return $listTypeCarcase;
    }
}