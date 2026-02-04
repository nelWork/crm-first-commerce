<?php

namespace App\Admin\Model\DocumentFlow;

use App\Admin\Model\Model;
use App\Database\DatabaseInterface;

class DocumentFlow extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
}