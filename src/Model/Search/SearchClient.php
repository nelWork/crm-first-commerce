<?php

namespace App\Model\Search;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

class SearchClient extends Model
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

        switch($this->filter){
            case 'inn':
                $clientDB = $this->database->select('clients', ['inn' => $searchText], [], -1, ['id']);
                break;
            case 'name':
            case 'phone':
            case 'email':
            case 'legal_address':
                $value = '%'.$searchText.'%';
                $clientDB = $this->database->select('clients', [$this->filter => $value], [], -1, ['id'], 0, 'AND', 'LIKE');
                break;

            default:
                $value = '%'.$searchText.'%';

                $clientDB = $this->database->select(
                    'clients',
                    [
                        'inn' => $searchText,
                        'name' => $value,
                        'phone' => $value,
                        'email' => $value,
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


        foreach ($clientDB as $client){
            $listId[] = $client['id'];
        }

        return $listId;
    }

}