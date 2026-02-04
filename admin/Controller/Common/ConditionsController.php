<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;
use App\Admin\Model\Condition\ConditionList;
use App\Model\Condition\Condition;

class ConditionsController extends Controller
{
    public function conditionListPage(){

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Обязательные условия',
            'itemActiveMenu' => 8
        ]);

        $this->view('Condition/list');
    }

    public function conditionDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('conditions', ['id' => $id]);
        }
    }
    public function conditionGetList()
    {
        $model = new ConditionList($this->database);

        $list = $model->listCondition();

        $arrayConditions = [];

        foreach ($list as $item) {
            $arrayConditions[] = $item->get(['id','name']);
        }

        echo json_encode(["count" => count($arrayConditions), "data" => $arrayConditions]);
    }

    public function conditionCopy()
    {
        $parentCondition = new Condition(['id' => $this->request->input('id')]);
        $parentData = $parentCondition->get(['name','description']);
        $parentData['name'] .= '(Копия)';

        $copyCondition = new Condition();

        $copyCondition->edit($parentData);

        if($copyCondition->save()){
            $this->redirect->to('/admin/condition/edit?id=' . $copyCondition->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/condition/list');
        }

    }

    public function conditionAddPage()
    {

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Добавить обязательные условия',
            'itemActiveMenu' => 8
        ]);

        $this->view('Condition/add');
    }

    public function conditionAdd()
    {
        $validation = $this->request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $condition = new Condition();

        $condition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description'),
        ]);
        if($condition->save()){
            $this->redirect->to('/admin/condition/edit?id=' . $condition->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/condition/add');
        }

    }
    public function conditionEditPage(){

        $condition = new Condition(['id' => $this->request->input('id')]);

        $status = -1;

        if($this->request->input('status') == 0 OR $this->request->input('status') == 1)
            $status = $this->request->input('status');

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Редактировать обязательные условия',
            'condition' => $condition->get(),
            'status' => $status,
            'itemActiveMenu' => 8
        ]);

        $this->view('Condition/edit');
    }
    public function conditionEdit(){

        $condition = new Condition(['id' => $this->request->input('id')]);

        $condition->edit([
            'name' => $this->request->input('name'),
            'description' => $this->request->input('description')
        ]);

        if($condition->save()){
            $this->redirect->to('/admin/condition/edit?status=1&id=' . $condition->get(['id'])['id']);
        }
        else{
            $this->redirect->to('/admin/condition/edit?status=0&id=' . $condition->get(['id'])['id']);
        }
    }
}