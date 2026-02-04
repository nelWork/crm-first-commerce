<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;
use App\Admin\Model\Users\UsersList;
use App\Model\User\User;

class UsersController extends Controller
{
    public function usersListPage(){
        
        
        
        $model = new UsersList($this->database);

        $listRoles = $model->listRoles();



        $usersList = $this->database->select('users',['role' => 1],[],-1,['id','name','surname','login']);

        $this->extract([
            'controller' => $this,
            'titlePage' => 'Пользователи',
            'itemActiveMenu' => 11,
            'usersList' => $usersList,
            'listRoles' => $listRoles,
        ]);
        

        $this->view('Users/list');
    
        
    }

    public function ajaxResetPassword()
    {
        $password = password_hash($this->request->input('password'), PASSWORD_DEFAULT);
        $result = $this->database->update('users', ['password' => $password], ['id' => $this->request->input('id')]);

        print json_encode(['result' => $result]);
    }

    public function usersDelete()
    {
        $idArray = $this->request->input('data');

        foreach ($idArray as $id) {
            $this->database->delete('type_carcase', ['id' => $id]);
        }
    }

    public function usersUploadAvatar()
    {
        $newAvatar = $this->request->file('file')->upload("avatar");
        if(! $newAvatar)
            print json_encode(['result' => false]);

        $user = new User(['id' => $this->request->input('id')]);

        $user->edit(['img_avatar' => $newAvatar]);

        if(! $user->save()){
            print json_encode(['result' => false]);
        }

        print json_encode(['result' => true, 'avatar' => $user->avatar()]);

    }
    public function usersGetList()
    {
        $model = new UsersList($this->database);

        $list = $model->listUsers();

        $arrayUsers = [];

        foreach ($list as $item) {
            $temp = $item->get();
            $temp['img_avatar'] = $item->avatar();
            $temp['role'] = $item->getRole();
            $arrayUsers[] = $temp;
        }

        echo json_encode(["count" => count($arrayUsers), "data" => $arrayUsers]);
    }

    public function usersGet()
    {
        $user = new User(['id' => $this->request->input('id')]);

        $userData = $user->get();

        $userData['subordinates'] = $user->getSubordinatesList();


        $userData['img_avatar'] = $user->avatar();

        echo json_encode($userData);
    }

    public function usersAdd()
    {
    // todo Валидация данных при добавлении пользователя


        $user = new User();

        $user->edit([
            'login' => $this->request->input('login'),
            'email' => $this->request->input('email'),
            'name' => $this->request->input('name'),
            'surname' => $this->request->input('surname'),
            'lastname' => $this->request->input('lastname'),
            'phone' => $this->request->input('phone'),
            'salary' => $this->request->input('salary'),
            'procent' => $this->request->input('procent'),
            'role' => $this->request->input('role'),
            'password' => password_hash($this->request->input('password'), PASSWORD_DEFAULT),
        ]);

        if($user->save()){
            echo true;
        }
        else{
            echo false;
        }

    }

    public function usersEdit(){

        // todo Валидация данных при редактировании пользователя

        $user = new User(['id' => $this->request->input('id')]);

        $subordinates = '';

        if(count($this->request->input('subordinates')) > 0){
            foreach ($this->request->input('subordinates') as $subordinate){
                if($subordinate != ''){
                    $subordinates .= '|' .$subordinate.'|,';
                }
            }
        }

        $user->edit([
            'login' => $this->request->input('login'),
            'email' => $this->request->input('email'),
            'name' => $this->request->input('name'),
            'surname' => $this->request->input('surname'),
            'lastname' => $this->request->input('lastname'),
            'phone' => $this->request->input('phone'),
            'salary' => $this->request->input('salary'),
            'procent' => $this->request->input('procent'),
            'role' => $this->request->input('role'),
            'subordinates' => $subordinates,
        ]);

        if($user->save()){
            echo true;
        }
        else{
            echo false;
        }
    }
}