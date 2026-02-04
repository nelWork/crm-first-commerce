<?php

namespace App\User\Model\User;

use App\Database\DatabaseInterface;
use App\Model\User\User;

class UserList
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listUsers(): array
    {
        $users = $this->database->select('users');
        $listUsers = array();

        foreach ($users as $user) {
            $listUsers[] = new User(['id' => $user['id']]);
        }

        return $listUsers;
    }

    public function listManager(): array
    {
        $managers = $this->database->select('users', ['role' => 1]);

        $listManagers = array();

        foreach ($managers as $manager) {
            $listManagers[] = new User(['id' => $manager['id']]);
        }

        return $listManagers;
    }

    public function listManagerJournal(): array
    {
        return $this->database->select('users', ['role' => 1]);
    }

    public function listROP(): array
    {
        return $this->database->select('users', ['is_rop' => 1]);
    }

    public function minListManager(): array
    {
        $managers = $this->database->select('users', ['role' => 1]);

        $listManagers = array();

        foreach ($managers as $manager) {
            $listManagers[] = [
                'id' => $manager['id'],
                'name' => $manager['name'],
                'surname' => $manager['surname']
            ];
        }

        return $listManagers;
    }
}