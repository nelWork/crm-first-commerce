<?php

namespace App\User\Contoller\Common;

use App\Model\Carrier\Carrier;
use App\Model\CarrierDetail\CarrierDetail;
use App\User\Contoller\Controller;
use App\User\Model\Carrier\CarrierList;

class RegistryController extends Controller
{
    public function index(){

        $listCarrierMode = new CarrierList($this->database);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Реестр',
            'listCarrier' => $listCarrierMode->simpleListCarriers(),
        ]);

        $this->view('Registry/index');
    }

    public function ajaxGetCarrierDetail()
    {
        $idCarrier = $this->request->input('id');

        $carrier = new Carrier(['id' => $idCarrier]);
        $carrierData = $carrier->get();

        $carrierDetailDB = $this->database->select('carriers_detail', ['carrier_id' => $idCarrier]);

        if(! $carrierDetailDB)
            $carrierDetailDB = [];

        $listCarrierDetail = [];


        foreach ($carrierDetailDB as $carrierDetail) {
            $temp = new CarrierDetail(['id' => $carrierDetail['id']]);
            $listCarrierDetail[] = $temp->get();
        }

        print json_encode(
            [
                'title' => $carrierData['name'],
                'inn' => $carrierData['inn'],
                'requisites' => $listCarrierDetail
            ]
        );
    }

    public function ajaxAddCarrierDetail()
    {
        $carrierDetail =  new CarrierDetail();


        $carrierDetail->edit([
            'bankName' => $this->request->input('bank_name'),
            'bik' => $this->request->input('bik'),
            'сorrАccount' => $this->request->input('сorrespondent_account'),
            'accountNumber' => $this->request->input('account_number'),
            'carrierId' => (int)$this->request->input('id')
        ]);


        if ($carrierDetail->save()) {
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }

    public function ajaxChangeMainDetail()
    {

        $this->database->update(
            'carriers_detail',
            ['is_main' => 0],
            ['carrier_id' => $this->request->input('id')]
        );


        if ($this->database->update(
            'carriers_detail',
            ['is_main' => 1],
            ['id' => $this->request->input('num')]
        )) {
            print json_encode(['result' => true]);
        }
        else{
            print json_encode(['result' => false]);
        }
    }
}