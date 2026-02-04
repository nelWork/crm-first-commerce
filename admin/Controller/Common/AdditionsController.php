<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;
use App\Admin\Model\Addition\AdditionList;
use App\Admin\Model\Users\UsersList;
use App\Model\Addition\Addition;

class AdditionsController extends Controller
{
    public function additionListPage(){

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Приложения',
            'itemActiveMenu' => 10
        ]);

        $this->view('Addition/list');
    }

    public function additionDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('addition', ['id' => $id]);
        }
    }
    public function additionGetList()
    {
        $model = new AdditionList($this->database);

        $list = $model->listAddition();

        $arrayAdditions = [];

        foreach ($list as $item) {
            $arrayAdditions[] = $item->get(['id','name']);
        }

        echo json_encode(["count" => count($arrayAdditions), "data" => $arrayAdditions]);
    }

    public function additionCopy()
    {
        $parentAddition = new Addition(['id' => $this->request->input('id')]);
        $parentData = $parentAddition->get(['name','description','users_access']);
        $parentData['name'] .= '(Копия)';

        $copyAddition = new Addition();

        $copyAddition->edit($parentData);

        if($copyAddition->save()){
            $this->redirect->to('/admin/addition/edit?id=' . $copyAddition->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/addition/list');
        }

    }

    public function additionAddPage()
    {
        $userListModel = new UsersList($this->database);
        $this->extract([
            'controller' => $this,
            'titlePage' => 'Добавить приложение',
            'itemActiveMenu' => 10,
            'users' => $userListModel->simpleListUsers()
        ]);

        $this->view('Addition/add');
    }

    public function additionAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $addition = new Addition();


        $addition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'users_access' => $this->request->input('users-access'),
        ]);
        if($addition->save()){
            $this->redirect->to('/admin/addition/edit?id=' . $addition->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/addition/add');
        }

    }
    public function additionEditPage(){

        $addition = new Addition(['id' => $this->request->input('id')]);

        $status = -1;

        if($this->request->input('status') == 0 OR $this->request->input('status') == 1)
            $status = $this->request->input('status');

        $userListModel = new UsersList($this->database);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Редактировать приложение',
            'addition' => $addition->get(),
            'status' => $status,
            'itemActiveMenu' => 10,
            'users' => $userListModel->simpleListUsers()
        ]);

        $this->view('Addition/edit');
    }
    public function additionEdit(){

        $addition = new Addition(['id' => $this->request->input('id')]);

        $addition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
            'users_access' => $this->request->input('users-access'),
        ]);

        if($addition->save()){
            $this->redirect->to('/admin/addition/edit?status=1&id=' . $addition->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/addition/edit?status=0&id=' . $addition->get(['id'])['id']);
        }
    }
}