<?php

namespace App\User\Model\NotificationModel;

use App\Database\DatabaseInterface;
use App\User\Model\Model;


class NotificationModel extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }


    public function getListNotifications(array $conditions = []): array
    {
        return $this->database->select('notifications', $conditions, ['status' => 'ASC']) ?? [];
    }

}