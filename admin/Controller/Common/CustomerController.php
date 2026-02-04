<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;
use App\Admin\Model\Сustomer\CustomerList;
use App\Model\Customer\Customer;

class CustomerController extends Controller
{
    public function customerListPage(){

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Юридические лица',
            'itemActiveMenu' => 3
        ]);

        $this->view('Customer/list');
    }

    public function customerDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('type_carcase', ['id' => $id]);
        }
    }
    public function customerGetList()
    {
        $model = new CustomerList($this->database);

        $list = $model->listCustomer();

        $arrayCustomer = [];

        foreach ($list as $item) {
            $arrayCustomer[] = $item->get();
        }

        echo json_encode(["count" => count($arrayCustomer), "data" => $arrayCustomer]);
    }

    public function customerGet()
    {
        $customer = new Customer(['id' => $this->request->input('id')]);

        echo json_encode($customer->get());
    }

    public function customerAdd()
    {
        $customer = new Customer();

        $customer->edit([
            'name' => $this->request->input('name'),
            'inn' => $this->request->input('inn'),
            'mailing_address' => $this->request->input('mailing_address'),
            'legal_address' => $this->request->input('legal_address'),
            'contact_face' => $this->request->input('contact_face'),
            'phone' => $this->request->input('phone'),
        ]);
//        echo 'test';
        if($customer->save()){
            echo 'text';
        }
        else{
            echo 'text2';
        }

    }

    public function customerEdit(){

        // todo Валидация данных при редактировании пользователя

        $customer = new Customer(['id' => $this->request->input('id')]);

        $customer->edit([
            'name' => $this->request->input('name'),
            'inn' => $this->request->input('inn'),
            'mailing_address' => $this->request->input('mailing_address'),
            'legal_address' => $this->request->input('legal_address'),
            'contact_face' => $this->request->input('contact_face'),
            'phone' => $this->request->input('phone'),
            'initials' => $this->request->input('initials'),
//            'visible' => $this->request->input('visible'),
        ]);

        if($customer->save()){
            echo true;
        }
        else{
            echo false;
        }
    }
}