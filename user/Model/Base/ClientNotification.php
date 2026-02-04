<?php

namespace App\User\Model\Base;

use App\Database\DatabaseInterface;
use App\User\Model\Model;

class ClientNotification extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {

    }


}