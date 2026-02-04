<?php

namespace App\Model\Search;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class SearchApplication extends Model
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private string $filter;


    public function __construct(string $filter = 'all'){
        $this->config = new Config();
        $this->database = new Database($this->config);
        $this->filter = $filter;

    }

    public function search(string $searchText): array{
        $searchText = trim($searchText);

        $listId = ['id' => 0];

        $applicationDB = [];

        switch($this->filter){
            case 'number':
                $applicationDB = $this->database->select('applications',['application_number' => $searchText],[],-1,['id']);
                break;
            case 'cost':
                $applicationDB = $this->database->select(
                    'applications',
                    [
                        'transportation_cost_Carrier' => $searchText,
                        'transportation_cost_Client' => $searchText
                    ],
                    [],
                    -1,
                    ['id'],
                    0,
                    'OR'
                );
                break;
            case 'driver':
                $driversId = $this->getDriverSearchID($searchText);

                if(count($driversId) > 0) {
                    $applicationDB = $this->database->superSelect(
                        'applications',
                        [
                            'driver_id_Carrier' => $driversId,
                            'driver_id_Client' => $driversId
                        ],
                        [],
                        -1,
                        ['id'],
                        0,
                        'OR'
                    );
                }
                break;
            default:
                $applicationDB = $this->database->select('applications',['application_number' => $searchText],[],-1,['id']);
                $applicationDB +=  $this->database->select(
                    'applications',
                    [
                        'transportation_cost_Carrier' => $searchText,
                        'transportation_cost_Client' => $searchText
                    ],
                    [],
                    -1,
                    ['id'],
                    0,
                    'OR'
                );

                $driversId = $this->getDriverSearchID($searchText);


                if(count($driversId) > 0) {

                    $applicationDB += $this->database->superSelect(
                        'applications',
                        [
                            'driver_id_Carrier' => $driversId,
                            'driver_id_Client' => $driversId
                        ],
                        [],
                        -1,
                        ['id'],
                        0,
                        'OR'
                    );

                }


                break;

        }


        foreach ($applicationDB as $application){
            $listId[] = $application['id'];
        }

        return $listId;
    }

    public function searchPRR(string $searchText): array{
        $searchText = trim($searchText);

        $listId = [0 => 0];

        $applicationDB = [];

        $applicationDB = $this->database->superSelect(
            'prr_application',
            ['application_number' => '%' .$searchText.'%'],
            [],
            -1,
            ['id'],
            0,
            'AND',
            'LIKE'
        );

        foreach ($applicationDB as $application){
            $listId[] = $application['id'];
        }

        return $listId;
    }

    private function getDriverSearchID(string $searchText): mixed
    {
        $name = '%' .$searchText  .'%';
        $driversDB = $this->database->select('drivers', ['name' => $name], [], -1, ['id'], 0 ,'AND','LIKE');

        $driversId = [];

        foreach($driversDB as $driver){
            $driversId[] = $driver['id'];
        }


        return $driversId;
    }
}