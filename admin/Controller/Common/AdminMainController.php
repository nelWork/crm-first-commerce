<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;
use App\Model\Addition\Addition;
use App\Model\CarBrands\CarBrands;
use App\Model\Condition\Condition;
use App\Model\TermsPayment\TermsPayment;
use App\Model\TypeTransport\TypeTransport;

class AdminMainController extends Controller
{
    public function index(){
        $this->extract([
            'controller' => $this,
            'titlePage' => 'Админ. панель'
        ]);
        $this->view("Common/main");
    }

    public function testUploadFile(){
        dump($this->request->files);
        dd($this->request->file('file')->upload("photos", $this->request->post['name']));
    }

    public function termsPaymentPage()
    {

    }

    public function termsPaymentAddPage(){
        $this->view("TermsPayment/add");
    }

    public function termsPaymentAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:500'],
            'type_for' => ['min:1', 'max:1'],
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            dd($_SESSION);
        }

        $termsPayment = new TermsPayment();

        $termsPayment->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'type_for' => $this->request->input('type_for'),
        ]);

        if($termsPayment->save()){
            $this->redirect->to('/admin/terms-of-payment/edit?id=' .$termsPayment->get(['id'])['id']);
        }



    }

    public function termsPaymentEditPage(){
        $termsPayment = new TermsPayment(['id' => $this->request->input('id')]);
        if(! $termsPayment->exists()){
            $this->redirect->to('/admin/terms-payment/list');
        }

        $this->extract([
            'controller' => $this,
            'termsPayment' => $termsPayment->get(),

        ]);
        $this->view("TermsPayment/edit");
    }

    public function termsPaymentEdit(){

        $termsPayment = new TermsPayment(['id' => $this->request->input('id')]);
        if(! $termsPayment->exists()){
            $this->redirect->to('/admin/terms-of-payment/list');
        }

        $validation = $this->request->validate([
            'name' => ['required', 'max:500'],
            'type_for' => ['min:1', 'max:1'],
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            $this->redirect->to('/admin/terms-of-payment/edit?id=' .$termsPayment->get(['id'])['id']);
        }

        $termsPayment->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'type_for' => $this->request->input('type_for'),
        ]);


        if($termsPayment->save()){
            $this->redirect->to('/admin/terms-of-payment/edit?id=' .$termsPayment->get(['id'])['id']);
        }
    }


    public function conditionAddPage(){
        $this->view("Condition/add");
    }
    public function conditionAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:500'],
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            dd($_SESSION);
        }

        $condition = new Condition();

        $condition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
        ]);

        if($condition->save()){
            $this->redirect->to('/admin/condition/edit?id=' .$condition->get(['id'])['id']);
        }



    }

    public function conditionEditPage(){
        $condition = new Condition(['id' => $this->request->input('id')]);
        if(! $condition->exists()){
            $this->redirect->to('/admin/condition/list');
        }

        $this->extract([
            'controller' => $this,
            'condition' => $condition->get(),

        ]);
        $this->view("Condition/edit");
    }

    public function conditionEdit(){

        $condition = new Condition(['id' => $this->request->input('id')]);
        if(! $condition->exists()){
            $this->redirect->to('/admin/condition/list');
        }

        $validation = $this->request->validate([
            'name' => ['required', 'max:500']
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            $this->redirect->to('/admin/condition/edit?id=' .$condition->get(['id'])['id']);
        }

        $condition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
        ]);


        if($condition->save()){
            $this->redirect->to('/admin/condition/edit?id=' .$condition->get(['id'])['id']);
        }
    }


    public function additionAddPage(){
        $this->view("Addition/add");
    }
    public function additionAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:500'],
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            dd($_SESSION);
        }

        $addition = new Addition();

        $users_access = '';


        foreach ($this->request->input('users_access') as $user){
            $users_access .=  '|' .$user . '|,';
        }

        $addition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'users_access' => $this->request->input('users_access'),
        ]);

        if($addition->save()){
            $this->redirect->to('/admin/addition/edit?id=' .$addition->get(['id'])['id']);
        }



    }

    public function additionEditPage(){
        $addition = new Addition(['id' => $this->request->input('id')]);
        if(! $addition->exists()){
            $this->redirect->to('/admin/addition/list');
        }

        $this->extract([
            'controller' => $this,
            'addition' => $addition->get(),

        ]);
        $this->view("Addition/edit");
    }

    public function additionEdit(){

        $addition = new Addition(['id' => $this->request->input('id')]);
        if(! $addition->exists()){
            $this->redirect->to('/admin/addition/list');
        }

        $validation = $this->request->validate([
            'name' => ['required', 'max:500']
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            $this->redirect->to('/admin/addition/edit?id=' .$addition->get(['id'])['id']);
        }

        $addition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'users_access' => $this->request->input('users_access'),
        ]);


        if($addition->save()){
            $this->redirect->to('/admin/addition/edit?id=' .$addition->get(['id'])['id']);
        }
    }





    public function typeTransportAddPage(){
        $this->view("TypeTransport/add");
    }
    public function typeTransportAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:255'],
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            dd($_SESSION);
        }

        $typeTransport = new TypeTransport();

        $typeTransport->edit([
            'name' => $this->request->input('name'),
        ]);

        if($typeTransport->save()){
            $this->redirect->to('/admin/type-transport/edit?id=' .$typeTransport->get(['id'])['id']);
        }



    }

    public function typeTransportEditPage(){
        $typeTransport = new TypeTransport(['id' => $this->request->input('id')]);
        if(! $typeTransport->exists()){
            $this->redirect->to('/admin/type-transport/list');
        }

        $this->extract([
            'controller' => $this,
            'typeTransport' => $typeTransport->get(),

        ]);
        $this->view("TypeTransport/edit");
    }

    public function typeTransportEdit(){

        $typeTransport = new TypeTransport(['id' => $this->request->input('id')]);
        if(! $typeTransport->exists()){
            $this->redirect->to('/admin/type-transport/list');
        }

        $validation = $this->request->validate([
            'name' => ['required', 'max:255']
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors){
                $this->session->set($field, $errors);
            }

            $this->redirect->to('/admin/type-transport/edit?id=' .$typeTransport->get(['id'])['id']);
        }

        $typeTransport->edit([
            'name' => $this->request->input('name')
        ]);


        if($typeTransport->save()){
            $this->redirect->to('/admin/type-transport/edit?id=' .$typeTransport->get(['id'])['id']);
        }
    }

}