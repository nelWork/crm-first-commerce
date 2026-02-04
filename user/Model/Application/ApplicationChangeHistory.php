<?php

namespace App\User\Model\Application;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Controller\Controller;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Application\Application;

class ApplicationChangeHistory
{
    private DatabaseInterface $database;
    private ConfigInterface $config;

    private int $oldApplicationId;
    private Application $oldApplication;
    private Application $newApplication;

    private array $changes;

    private int $userId;

    public function __construct($oldApplicationId, $newApplication, $userId){
        $this->config = new Config();
        $this->database = new Database($this->config);

        $this->oldApplication = new Application(["id" => $oldApplicationId]);
        $this->oldApplicationId = $oldApplicationId;
        $this->newApplication = $newApplication;

        $this->userId = $userId;
    }

    public function saveChangeHistory(){

        $applicationOldValues = $this->oldApplication->get();
        $applicationNewValues = $this->newApplication->get();


        foreach ($applicationOldValues as $key => $value) {
            if ($applicationNewValues[$key] !== $value){
                $this->changes[] = [
                    "key" => $key,
                    "oldValue" => $value,
                    "newValue" => $applicationNewValues[$key]
                ];
            }
        }

        $this->changesTransportation();
        $this->changesFines();
        $this->changesExpenses();

        if (!empty($this->changes)){
            $this->database->insert("changes", [
                "changes" => json_encode($this->changes, JSON_UNESCAPED_UNICODE),
                "application_id" => $this->oldApplicationId,
                "datetime" =>  date("Y-m-d H:i:s"),
                "user_id" => $this->userId
            ]);
        }
    }

    private function changesTransportation(){
        $oldTransportations = $this->database->select("routes", ["id_application" => $this->oldApplicationId]);

        $newTransportationsList = [];
        foreach($this->newApplication->transportationList as $item){
            $newTransportationsList[] = $item->get();
        }

        if (count($oldTransportations) !== count($newTransportationsList)){
            $routesUpdated = true;
        }
        else{
            $routesUpdated = false;

            for ($i = 0; $i < count($oldTransportations); $i++){
                foreach ($oldTransportations[$i] as $key => $value) {
                    if ($key === "id" || $key === "id_application"){
                        continue;
                    }
                    if (strval($newTransportationsList[$i][$key]) !== (string)$value){
                        $routesUpdated = true;
                        break;
                    }
                }
            }
        }

        if ($routesUpdated){
            $this->changes[] =[
                "key" => "transportation",
                "oldValue" => $oldTransportations,
                "newValue" => $newTransportationsList
            ];
        }
    }

    private function changesFines(){
        $oldFines = $this->database->select("fines", ["id_application" => $this->oldApplicationId]);

        $newFinesList = [];
        foreach($this->newApplication->finesList as $item){
            $newFinesList[] = $item->get();
        }

        if (count($oldFines) !== count($newFinesList)){
            $finesUpdated = true;
        }
        else{
            $finesUpdated = false;

            for ($i = 0; $i < count($oldFines); $i++){
                foreach ($oldFines[$i] as $key => $value) {
                    if ($key === "id" || $key === "id_application"){
                        continue;
                    }
                    if ($newFinesList[$i][$key] !== $value){
                        $finesUpdated = true;
                        break;
                    }
                }
            }
        }
        if ($finesUpdated){
            $this->changes[] =[
                "key" => "fines",
                "oldValue" => $oldFines,
                "newValue" => $newFinesList
            ];
        }
    }

    private function changesExpenses(){
        $oldExpenses = $this->database->select("additional_expenses", ["id_application" => $this->oldApplicationId]);

        $newExpensesList = [];
        foreach($this->newApplication->additionalExpensesList as $item){
            $newExpensesList[] = $item->get();
        }

        if (count($oldExpenses) !== count($newExpensesList)){
            $expensesUpdated = true;
        }
        else{
            $expensesUpdated = false;

            for ($i = 0; $i < count($oldExpenses); $i++){
                foreach ($oldExpenses[$i] as $key => $value) {
                    if ($key === "id" || $key === "id_application"){
                        continue;
                    }
                    if ($newExpensesList[$i][$key] !== $value){
                        $expensesUpdated = true;
                        break;
                    }
                }
            }
        }
        if ($expensesUpdated){
            $this->changes[] =[
                "key" => "additional_expenses",
                "oldValue" => $oldExpenses,
                "newValue" => $newExpensesList
            ];
        }
    }
}
