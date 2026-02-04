<?php

namespace App\User\Model\Application;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;
use App\Model\User\User;

class Comment extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;

    private int $idApplication = 0;

    private int $idUser = 0;

    private string $comment = '';

    private $dateTime = null;

    private int $important =  0;
    private int $visible =  1;

    private int $typeComment = 0;

    private string $prefix = '';

    public array $fields = [
        'id', 'idApplication', 'idUser',
        'dateTime', 'important', 'comment', 'visible',
        'typeComment'
    ];

    public function __construct(array $data = [], string $prefix = '')
    {
        $this->prefix = $prefix;
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $comment = $this->database->first($this->prefix ."comments_application", $data);

            foreach ($comment as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }
        }
    }

    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $newKey = $this->sqlToPhpNameConvert($key);
            $this->$newKey = $value;
        }
    }

    public function get(array $conditions = []): array|string
    {
        if (empty($conditions)) {

            $data = [];
            foreach ($this->fields as $field) {
                $data[$this->phpToSqlNameConvert($field)] = $this->$field;
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $returnedArray[$this->phpToSqlNameConvert($value)] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    public function getUserData(): array
    {
        $user = new User(['id' => $this->idUser]);

        return [
            'name' => $user->get()['name'] . ' ' . $user->get()['surname'],
            'avatar' => $user->avatar(),
            'role' => $user->getRole()
        ];
    }

    public function save()
    {
        $newData = $this->get();

        if ($this->id > 0) {
            $stmt = $this->database->update($this->prefix ."comments_application", $newData, ["id" => $this->id]);
            return $stmt;
        }
        else{
            if($newData['date_time'] == null)
                $newData['date_time'] = date("Y-m-d H:i:s");

            $stmt = $this->database->insert($this->prefix. "comments_application", $newData);

            if ($stmt) {
                return true;
            }

            return $stmt;
        }
    }
}