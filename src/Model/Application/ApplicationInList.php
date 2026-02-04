<?php

namespace App\Model\Application;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class ApplicationInList extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private int $id = 0;
    private string $numApp = '';
    private string $date = '';
    private string $client = '';
    private array $routes = [];
    private array $managerData = ['name' => '', 'img' => ''];

    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $application = $this->database->first("applications", $data);

            foreach ($application as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }
        }
    }
}