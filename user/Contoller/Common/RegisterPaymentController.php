<?php

namespace App\User\Contoller\Common;

use App\User\Contoller\Controller;
use App\User\Model\RegisterPayment\RegisterPaymentApplicationList;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class RegisterPaymentController extends Controller
{
    public function index()
    {

        if($this->auth->user()->id() != 46 AND !$this->auth->user()->fullCRM()){
            $this->redirect->to('/');
        }

        $modelListApplication = new RegisterPaymentApplicationList($this->database);

//        dd($modelListApplication->listApplication());

        $this->extract([
            'controller' => $this,
            'listApplication' => $modelListApplication->listApplication(),
            'activeHeaderItem' => 14,
            'titlePage' => 'Реестр на оплату',
        ]);

        $this->view("RegisterPayment/index");
    }

    public function registerApplicationPayment(){
        
    }

    public function ajaxChangeApplicationComment()
    {
        echo $this->database->update(
            'applications',
            ['register_payment_comment' => $this->request->input('comment')],
            ['id' => $this->request->input('id')]
        );
    }

    public function ajaxChangeRegisterPaymentHistory()
    {
        echo $this->database->insert(
            'register_payment_history',
            [
                'id_application' => $this->request->input('id'),
                'date' => date('Y-m-d'),
                'comment' => $this->request->input('comment'),
                'id_user' => $this->auth->user()->id()
            ]
        );
    }

    public function ajaxAddCommentApplication()
    {
        $id = $this->request->input('id');
        $comment = $this->request->input('comment');

        $idUser = $this->auth->user()->id();
        $datetime = date('Y-m-d H:i:s');


        print $this->database->insert('register_payment_application_comment', [
                'id_application' => $id,
                'id_user' => $idUser,
                'comment' => $comment,
                'datetime_comment' => $datetime
        ]);
    }

    public function ajaxSetColorApplication(){
        $id = $this->request->input('id');

        $hasApplication = $this->database->first('register_payment_application_color',['id_application' => $id]);

        $color = 1;

        if($hasApplication){
            $color = 0;
            $this->database->delete('register_payment_application_color',['id_application' => $id]);
        }
        else{
            $this->database->insert('register_payment_application_color', [
                'id_application' => $id,
                'color' => 1
            ]);
        }

        print json_encode(['color' => $color]);
    }

    public function ajaxGetColorApplication(){
        print json_encode($this->database->select('register_payment_application_color'));
    }

    public function ajaxChangeClaimsApplication(){
        $id = $this->request->input('id');
        $comment = $this->request->input('comment');
        $type = $this->request->input('type');

        $this->database->update(
            'applications',
            [
                $type ."_claims" => $comment
            ],
            ['id' => $id]
        );
    }


    public function registerAdditionalExpenses(){

        $allAdditionalExpenses = $this->database->superSelect(
            'additional_expenses',
            [
                'is_paid' => 0,
                'type_payment' => ['Б/НДС','С НДС']
            ]
        );

        $listExpenses = [];

        foreach ($allAdditionalExpenses as $item){

            if($item['type_expenses'] == 'Страховка'){
                continue;
            }

            $application = $this->database->first('applications',['id' => $item['id_application']]);

            $user = $this->database->first('users',['id' => $application['id_user']]);

            $type_payment_Client = 1;

            if($application['actual_payment_Client'] > 0 && $application['actual_payment_Client'] < $application['transportation_cost_Client'])
                $type_payment_Client = 2;
            
            if($application['actual_payment_Client'] == $application['transportation_cost_Client'])
                $type_payment_Client = 3;

            $listExpenses[] = [
                'id' => $item['id'],
                'type_expenses' => $item['type_expenses'],
                'type_payment' => $item['type_payment'],
                'sum' => $item['sum'],
                'comment' => $item['comment'],
                'application_number' => $application['application_number'],
                'transportation_cost_Client' => $application['transportation_cost_Client'],
                'actual_payment_Client' => $application['actual_payment_Client'],
                'id_user' => $application['id_user'],
                'user' =>  $user['surname'] .' ' .$user['name'],
                'type_payment_Client' => $type_payment_Client,
                'id_application' => $item['id_application']
            ];
        }

        $allAdditionalExpensesPRR = $this->database->superSelect(
            'additional_expenses_prr',
            [
                // 'is_paid' => 0,
                'type_payment' => ['Б/НДС','С НДС']
            ]
        );


        foreach ($allAdditionalExpensesPRR as $item){

            if($item['type_expenses'] == 'Страховка'){
                continue;
            }

            $application = $this->database->first('prr_application',['id' => $item['id_application']]);

            $user = $this->database->first('users',['id' => $application['id_user']]);

            $type_payment_Client = 1;

            if($application['actual_payment_Client'] > 0 && $application['actual_payment_Client'] < $application['cost_Client'])
                $type_payment_Client = 2;
            
            if($application['actual_payment_Client'] == $application['cost_Client'])
                $type_payment_Client = 3;

            $listExpenses[] = [
                'id' => $item['id'],
                'type_expenses' => $item['type_expenses'],
                'type_payment' => $item['type_payment'],
                'sum' => $item['sum'],
                'comment' => $item['comment'],
                'application_number' => $application['application_number'],
                'transportation_cost_Client' => $application['cost_Client'],
                'actual_payment_Client' => $application['actual_payment_Client'],
                'id_user' => $application['id_user'],
                'user' =>  $user['surname'] .' ' .$user['name'],
                'type_payment_Client' => $type_payment_Client,
                'id_application' => $item['id_application']
            ];
        }

        // dd($listData);

        $this->extract([
            'controller' => $this,
            'listExpenses' => $listExpenses,
            'activeHeaderItem' => 16,
            'titlePage' => 'Реестр доп затрат',
        ]);

        $this->view('RegisterPayment/register-additional-expenses');
    }
}