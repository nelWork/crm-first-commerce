<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;
use App\Admin\Model\TermsPayment\TermsPaymentList;
use App\Model\TermsPayment\TermsPayment;

class TermsPaymentsController extends Controller
{
    public function termsPaymentListPage(){

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Условия оплаты',
            'itemActiveMenu' => 9
        ]);

        $this->view('TermsPayment/list');
    }

    public function termsPaymentDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('terms_payment', ['id' => $id]);
        }
    }
    public function termsPaymentGetList()
    {
        $model = new TermsPaymentList($this->database);

        $list = $model->listTermsPayment();

        $arrayTermsPayments = [];

        foreach ($list as $item) {
            $arrayTermsPayments[] = $item->get(['id','name']);
        }

        echo json_encode(["count" => count($arrayTermsPayments), "data" => $arrayTermsPayments]);
    }

    public function termsPaymentCopy()
    {
        $parentTermsPayment = new TermsPayment(['id' => $this->request->input('id')]);
        $parentData = $parentTermsPayment->get(['name','description','type_for','type_taxation']);
        $parentData['name'] .= '(Копия)';

        $copyTermsPayment = new TermsPayment();

        $copyTermsPayment->edit($parentData);

        if($copyTermsPayment->save()){
            $this->redirect->to('/admin/terms-of-payment/edit?id=' . $copyTermsPayment->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/terms-of-payment/list');
        }

    }

    public function termsPaymentAddPage()
    {

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Добавить условие оплаты',
            'itemActiveMenu' => 9

        ]);

        $this->view('TermsPayment/add');
    }

    public function termsPaymentAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $termsPayment = new TermsPayment();

        $termsPayment->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'type_for' => $this->request->input('type-for'),
            'type_taxation' => $this->request->input('type-taxation'),
        ]);
        if($termsPayment->save()){
            $this->redirect->to('/admin/terms-of-payment/edit?id=' . $termsPayment->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/terms-of-payment/add');
        }

    }
    public function termsPaymentEditPage(){
        $termsPayment = new TermsPayment(['id' => $this->request->input('id')]);

        $status = -1;

        if($this->request->input('status') == 0 OR $this->request->input('status') == 1)
            $status = $this->request->input('status');

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Редактировать условие оплаты',
            'termsPayment' => $termsPayment->get(),
            'status' => $status,
            'itemActiveMenu' => 9

        ]);

        $this->view('TermsPayment/edit');
    }
    public function termsPaymentEdit(){

        $termsPayment = new TermsPayment(['id' => $this->request->input('id')]);

        $termsPayment->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'type_for' => $this->request->input('type-for'),
            'type_taxation' => $this->request->input('type-taxation'),
        ]);

        if($termsPayment->save()){
            $this->redirect->to('/admin/terms-of-payment/edit?status=1&id=' . $termsPayment->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/terms-of-payment/edit?status=0&id=' . $termsPayment->get(['id'])['id']);
        }
    }
}