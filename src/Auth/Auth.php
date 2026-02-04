<?php

namespace App\Auth;

use App\Auth\AuthInterface;
use App\Config\ConfigInterface;
use App\Database\DatabaseInterface;
use App\Model\User\User;
use App\Session\Session;
use App\Session\SessionInterface;


/**
 * Описание класса Auth
 *
 *  Класс для авторизации пользователя
 *
 * */

class Auth implements AuthInterface
{

    public function __construct(
        private readonly DatabaseInterface $database,
        private readonly SessionInterface  $session,
        private readonly ConfigInterface $config
    )
    {

    }
    /**
     * Функция для авторизации пользователя
     *
     * @param string $username имя пользователя
     * @param string $password пароль пользователя
     *
     * */
    public function attempt(string $username, string $password): bool
    {
        $user = $this->database->first(
            $this->table(),
            [$this->username() => $username,'active' => 1],
        );

        if(! $user){
            return false;
        }

        if(! password_verify($password, $user['password'])){
            return false;
        }

        $this->session->set($this->sessionField(), $user['id']);

        return true;
    }
    /**
     * Функция возвращает объект класса User если пользователь авторизован
     */
    public function user(): ?User
    {
        if(! $this->check()){
            return null;
        }

        $user = $this->database->first(
            $this->table(),
            ['id' => $this->session->get($this->sessionField())],
        );

        if($user){
            return new User(['id' => $user['id']]);
        }

        return null;
    }
    /**
     * Функция выхода из аккаунта пользователя
     *
     * */
    public function logout(): void
    {
        $this->session->remove($this->sessionField());
    }
    /**
     * Функция для проверки наличия сессии которая отвечает за авторизацию пользователя
     *
     * */
    public function check(): bool
    {
        return $this->session->has($this->sessionField());

    }

    /**
     * Функция берет из конфига название таблицы для пользователей
     *
     * */
    public function table(): string
    {
        return $this->config->get('auth.table','users');

    }
    /**
     * Функция берет из конфига название поля из таблицы пользователей отвечающее за имя пользователя
     *
     * */
    public function username(): string
    {
        return $this->config->get('auth.username','login');

    }
    /**
     * Функция берет из конфига название поля из таблицы пользователей отвечающее за пароль пользователя
     *
     * */
    public function password(): string
    {
        return $this->config->get('auth.password','password');

    }
    /**
     * Функция берет из конфига название сессии которая создается при авторизации пользователя
     *
     * */
    public function sessionField(): string
    {
        return $this->config->get('auth.session_field','user_id');

    }


}