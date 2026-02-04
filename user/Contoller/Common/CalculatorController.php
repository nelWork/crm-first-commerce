<?php

namespace App\User\Contoller\Common;

use App\User\Contoller\Controller;
use App\User\Model\Application\Plan;
use App\User\Model\User\ManagerPlan;

class CalculatorController extends Controller
{
    public function calculatorPage()
    {

        $activeUser = $this->auth->user();

        $isFullCRMAccess = $activeUser->fullCRM();

        $plan = false;
        $planExecution = false;

        if(! $isFullCRMAccess){
            $model = new ManagerPlan($this->database);
            $model->checkPlanExecutionManager($activeUser->id());


            $planModel = new Plan($this->database, $activeUser->id());

            $planExecution = $planModel->getInfoFromDB();
            if($planExecution['id_plan'] > 2){
                $planExecution = false;
            }

            else{
                $plan = $this->database->first('types_plan',['id' => $planExecution ['id_plan']]);
            }

        }


        $this->extract([
            'controller' => $this,
            'planExecution' => $planExecution,
            'plan' => $plan,
            'titlePage' => 'Калькулятор',
            'comments' => [],
            'activeHeaderItem' => 13,
        ]);

        $this->view("Calculator/index");
    }
}