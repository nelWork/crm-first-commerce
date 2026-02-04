<?php

namespace App\User\Model\Application;

use App\Database\DatabaseInterface;
use App\User\Model\Model;

class Plan extends Model
{
    private DatabaseInterface $database;

    private int $idUser;

    public function __construct(DatabaseInterface $database, int $idUser)
    {
        $this->database = $database;
        $this->idUser = $idUser;
    }


    private function findDateRange():array
    {
        $dayNow = (int)date('d');


        if($dayNow > 19){
            $dateStart = date('Y-m-20');
            $dateEnd = date('Y-m-19', strtotime('first day of next month'));

        }
        else{
            $dateStart = date('Y-m-20', strtotime('-1 month'));
            $dateEnd = date('Y-m-19');

        }


        return ['dateStart' => $dateStart, 'dateEnd' => $dateEnd];
    }

    public function getInfoFromDB($dateStart = null, $dateEnd = null)
    {
        if($dateStart != null AND $dateEnd != null){
            $rangeDate['dateStart'] = $dateStart;
            $rangeDate['dateEnd'] = $dateEnd;
        }
        else
            $rangeDate = $this->findDateRange();


        return $this->database->first(
            'plan_execution_managers',
            [
                'id_user' => $this->idUser,
                'date_start' => $rangeDate['dateStart'],
                'date_end' => $rangeDate['dateEnd']
            ]
        );


    }

}