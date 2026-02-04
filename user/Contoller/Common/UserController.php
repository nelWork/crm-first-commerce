<?php

namespace App\User\Contoller\Common;

use App\Model\User\User;
use App\User\Contoller\Controller;
use App\User\Model\User\UserList;

class UserController extends Controller
{
    private UserList $model;

    public function loginPage(){
        $this->extract([
            'controller' => $this,
            'session' => $this->session,
            'titlePage' => 'Авторизация пользователя'
        ]);
        $this->view('Common/login');
    }

    public function login()
    {
        $login = $this->request->input('login');
        $password = $this->request->input('password');

        if(! $this->auth->attempt($login, $password)){
            $this->session->set('error', "Неверный логин или пароль");
            $this->session->set('login', $this->request->input('login'));


            $this->redirect->to('/login');
        }

        $this->redirect->to('/profile');

    }
    public function profilePage()
    {
        $this->extract([
            'user' => $this->auth->user()->get(),
            'controller' => $this,
            'titlePage' => 'Профиль'
        ]);
        $this->view("Common/profile");
    }


    public function usersUploadAvatar()
    {
        $newAvatar = $this->request->file('file')->upload("avatar");

        if(! $newAvatar)
            print json_encode(['result' => false]);

        $user = $this->auth->user();

        $user->edit(['img_avatar' => $newAvatar]);

        if(! $user->save()){
            print json_encode(['result' => false]);
        }

        print json_encode(['result' => true, 'avatar' => $user->avatar()]);

    }

    public function profileEdit()
    {
        // todo проверка на валидацию
        $user = $this->auth->user();
        $editArray = [];

        foreach ($user->fields as $field) {
            if($this->request->input($field) != ''){
                $editArray[$field] = $this->request->input($field);
            }
        }
        $user->edit($editArray);

        $user->save();

        $this->redirect->to('/profile');

        dd($editArray);
    }

    public function logout()
    {
        $this->auth->logout();
        $this->redirect->to('/login');
    }

    public function resetPasswordPage()
    {
        $this->extract([
            'session' => $this->session,
            'controller' => $this,
            'titlePage' => 'Сброс пароля'
        ]);
        $this->view("Common/resetPassword");
    }

    public function reset()
    {
        $user = new User(['reset_link' => $this->request->input('c')]);

        if(! $user->exists()){
            $this->session->set('reset','Неверные данные');

            $this->redirect->to('/404');
        }

        $this->session->set('reset_password_user', $user->get(['id'])['id']);
        $this->session->set('reset_link', $this->request->input('c'));

        $this->extract([
            'controller' => $this,
            'session' => $this->session,
            'titlePage' => 'Сброс пароля'
        ]);

        $this->view("Common/reset");

    }

    public function updateResetPassword()
    {
        $user = new User(['id' => $this->session->get('reset_password_user')]);

        if(! $user->exists()){
            $this->session->set('error-reset-password','Неизвестная ошибка, повторите попытку');
            $this->redirect->to('/reset-password');
        }

        if($this->request->input('password') !== $this->request->input('password-repeat')){
            $this->session->set('error-reset-repeat-password','Пароли не совпадают');
            $this->redirect->to('/reset?c=' .$this->session->get('reset_link'));
        }

        $validation = $this->request->validate([
            'password' => ['required', 'min:5', 'max:255'],
        ]);

        if(! $validation){
            $this->session->set('error-reset-repeat-password','');

            $this->redirect->to('/reset?c=' .$this->session->get('reset_link'));

        }

        $user->edit([
            'password' => password_hash($this->request->input('password'), PASSWORD_DEFAULT),
            'reset_link' => ''
        ]);
        $user->save();

        $this->session->set('login-success', 'Вы успешно сбросили пароль!');
        $this->redirect->to('/login');
    }

    public function resetPassword()
    {
        $userResetPassword = new User(['email' => $this->request->input('email')]);

        if(! $userResetPassword->exists()){
            $this->session->set('error-reset-password', 'Пользователя с таким Email не существует');
            $this->session->set('reset-password-email', $this->request->input('email'));

            $this->redirect->to('/reset-password');
        }
        // todo проверка на то существует ли такой пользователь, отправка кода для сброса пароля
        if(! $userResetPassword->resetPassword()){
            $this->session->set('error-reset-password', 'Ошибка при отправке сообщения');


            $this->redirect->to('/reset-password');

        }

        $this->session->set('success-reset-password', true);
        $this->session->set('reset-password-email', $this->request->input('email'));
        $this->redirect->to('/reset-password');

    }

    public function addUserPage()
    {
        $this->extract([
            'controller' => $this,
            'titlePage' => 'Добавление пользователей',
        ]);
        $this->view("Common/addUser");
    }

    public function addUser()
    {
        $validation = $this->request->validate([
            'login' => ['required', 'min:5', 'max:255','unique:users.login'],
            'email' => ['required', 'email', 'min:5', 'max:255','unique:users.email'],
            'password' => ['required', 'min:5', 'max:255'],
            'role' => ['required']
        ]);

        if(! $validation){
            foreach ($this->request->errors() as $field => $errors) {
                $this->session->set($field, $errors);
            }

            dd($_SESSION);
        }


        $newUser = new User();

        $newUser->edit([
            'login' => $this->request->input('login'),
            'email' => $this->request->input('email'),
            'role' => $this->request->input('role'),
            'password' => password_hash($this->request->input('password'), PASSWORD_DEFAULT),
        ]);

        dd($newUser->save());
    }

    public function users()
    {
        $this->model = new UserList($this->database);


        $this->extract([
            'userList' => $this->model->listUsers(),
        ]);

        $this->view("Common/users");
    }
}