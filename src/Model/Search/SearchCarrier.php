<?php

namespace App\Model\Search;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class SearchCarrier extends Model
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

        $listId = ['id' => 0];

        $searchText = trim($searchText);
        $carrierDB = [];

        switch($this->filter){
            case 'inn':
                $carrierDB = $this->database->select('carriers', ['inn' => $searchText], [], -1, ['id']);
                break;
            case 'name':
            case 'legal_address':
                $value = '%'.$searchText.'%';
                $carrierDB = $this->database->select('carriers', [$this->filter => $value], [], -1, ['id'], 0, 'AND', 'LIKE');
                break;
            case 'phone':
            case 'email':
            case 'ati':
                $value = '%'.$searchText.'%';
                if($this->filter == 'ati')
                    $value = '%АТИ '.$searchText.'%';
                $carrierDB = $this->database->select('carriers', ['info' => $value], [], -1, ['id'], 0, 'AND', 'LIKE');
                break;

            default:
                $value = '%'.$searchText.'%';

                $carrierDB = $this->database->select(
                    'carriers',
                    [
                        'inn' => $searchText,
                        'name' => $value,
                        'info' => $value,
                        'legal_address' => $value,
                    ],
                    [],
                    -1,
                    ['id'],
                    0,
                    'OR',
                    'LIKE'
                );

                break;

        }


        foreach ($carrierDB as $carrier){
            $listId[] = $carrier['id'];
        }

        return $listId;
    }
}