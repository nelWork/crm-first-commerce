<?php

namespace App\Admin\Model\Users;

use App\Database\DatabaseInterface;
use App\Model\User\User;
use App\User\Model\Model;
use Couchbase\Role;

class UsersList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listUsers(): array
    {
        $users = $this->database->select('users');
        $listUsers= array();

        foreach ($users as $user) {
            $listUsers[] = new User(['id' => $user['id']]);
        }

        return $listUsers;
    }

    public function listRoles(): array
    {
        return $this->database->select('roles');
    }

    public function simpleListUsers(): array
    {
        return $this->database->select('users',[],[],-1,['id','name','surname','email']);
    }
}