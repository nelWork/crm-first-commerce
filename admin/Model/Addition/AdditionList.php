<?php

namespace App\Admin\Model\Addition;

use App\Admin\Model\Model;
use App\Database\DatabaseInterface;
use App\Model\Addition\Addition;

class AdditionList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listAddition(): array
    {
        $additions = $this->database->select('addition',[],[],-1,['id']);
        $listAddition = array();

        foreach ($additions as $item) {
            $listAddition[] = new Addition(['id' => $item['id']]);
        }

        return $listAddition;
    }
}