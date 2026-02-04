<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;
use App\Admin\Model\CarBrands\CarBrandsList;
use App\Admin\Model\TypeCarcase\TypeCarcaseList;
use App\Admin\Model\TypeTransport\TypeTransportList;
use App\Model\CarBrands\CarBrands;
use App\Model\TypeCarcase\TypeCarcase;
use App\Model\TypeTransport\TypeTransport;

class TransportController extends Controller
{
    public function carBrandsListPage(){

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Марки',
            'itemActiveMenu' => 5

        ]);

        $this->view('CarBrands/list');
    }



    public function carBrandsDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('car_brands', ['id' => $id]);
        }
    }
    public function carBrandsGetList()
    {
        $model = new CarBrandsList($this->database);

        $list = $model->listCarBrands();

        $arrayCarBrands = [];

        foreach ($list as $brand) {
            $arrayCarBrands[] = ['id' => $brand->id(), 'name' => $brand->name()];
        }

        echo json_encode(["count" => count($arrayCarBrands), "data" => $arrayCarBrands]);
    }

    public function carBrandsGet()
    {
        $carBrand = new CarBrands(['id' => $this->request->input('id')]);

        echo json_encode($carBrand->get());
    }

    public function carBrandsAdd()
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

        $carBrand = new CarBrands();

        $carBrand->edit([
            'name' => $this->request->input('name'),
        ]);

        if($carBrand->save()){
            echo true;
        }
        else{
            echo false;
        }

    }

    public function carBrandsEdit(){

        $carBrand = new CarBrands(['id' => $this->request->input('id')]);
        if(! $carBrand->exists()){
            $this->redirect->to('/admin/card-brands/list');
        }

        $carBrand->edit([
            'name' => $this->request->input('name')
        ]);

        if($carBrand->save()){
            echo true;
        }
        else{
            echo false;
        }
    }

    public function typeTransportListPage(){

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Тип транспорта',
            'itemActiveMenu' => 6
        ]);

        $this->view('TypeTransport/list');
    }

    public function typeTransportDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('type_transport', ['id' => $id]);
        }
    }
    public function typeTransportGetList()
    {
        $model = new TypeTransportList($this->database);

        $list = $model->listTypeTransport();

        $arrayTypeTransport = [];

        foreach ($list as $item) {
            $arrayTypeTransport[] = $item->get();
        }

        echo json_encode(["count" => count($arrayTypeTransport), "data" => $arrayTypeTransport]);
    }

    public function typeTransportGet()
    {
        $typeTransport = new TypeTransport(['id' => $this->request->input('id')]);

        echo json_encode($typeTransport->get());
    }

    public function typeTransportAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:255'],
        ]);


        $typeTransport = new TypeTransport();

        $typeTransport->edit([
            'name' => $this->request->input('name'),
        ]);

        if($typeTransport->save()){
            echo true;
        }
        else{
            echo false;
        }

    }

    public function typeTransportEdit(){

        $typeTransport = new TypeTransport(['id' => $this->request->input('id')]);

        $typeTransport->edit([
            'name' => $this->request->input('name')
        ]);

        if($typeTransport->save()){
            echo true;
        }
        else{
            echo false;
        }
    }



    public function typeCarcaseListPage(){

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Тип кузова',
            'itemActiveMenu' => 7
        ]);

        $this->view('TypeCarcase/list');
    }

    public function typeCarcaseDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('type_carcase', ['id' => $id]);
        }
    }
    public function typeCarcaseGetList()
    {
        $model = new TypeCarcaseList($this->database);

        $list = $model->listTypeCarcase();

        $arrayTypeCarcase = [];

        foreach ($list as $item) {
            $arrayTypeCarcase[] = $item->get();
        }

        echo json_encode(["count" => count($arrayTypeCarcase), "data" => $arrayTypeCarcase]);
    }

    public function typeCarcaseGet()
    {
        $typeCarcase = new TypeCarcase(['id' => $this->request->input('id')]);

        echo json_encode($typeCarcase->get());
    }

    public function typeCarcaseAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:255'],
        ]);


        $typeCarcase = new TypeCarcase();

        $typeCarcase->edit([
            'name' => $this->request->input('name'),
        ]);

        if($typeCarcase->save()){
            echo true;
        }
        else{
            echo false;
        }

    }

    public function typeCarcaseEdit(){

        $typeCarcase = new TypeCarcase(['id' => $this->request->input('id')]);

        $typeCarcase->edit([
            'name' => $this->request->input('name')
        ]);

        if($typeCarcase->save()){
            echo true;
        }
        else{
            echo false;
        }
    }

}