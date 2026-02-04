<?php

namespace App\Admin\Model\Condition;

use App\Admin\Model\Model;
use App\Database\DatabaseInterface;
use App\Model\Condition\Condition;

class ConditionList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listCondition(): array
    {
        $condition = $this->database->select('conditions',[],[],-1,['id']);
        $listCondition = array();

        foreach ($condition as $item) {
            $listCondition[] = new Condition(['id' => $item['id']]);
        }

        return $listCondition;
    }
}