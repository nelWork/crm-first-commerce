<?php

namespace App\User\Contoller\Common;

use App\User\Contoller\Controller;

class PlanController extends Controller
{
    public function index()
    {

        $planExecution  = $this->database->first('plan_execution_managers',['id_user' => 41]);
        $plan = $this->database->first('types_plan',['id' => $planExecution ['id_plan']]);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'План',
            'planExecution' => $planExecution,
            'plan' => $plan,
            'activeHeaderItem' => 11
        ]);

        $this->view("Plan/index");
    }
}