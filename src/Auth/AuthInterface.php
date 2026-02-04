<?php

namespace App\Auth;

use App\Model\User\User;

/**
 * Описание класса Auth
 *
 *  Класс для авторизации пользователя
 *
 * */
interface AuthInterface
{
    /**
     * Функция для авторизации пользователя
     *
     * @param string $username имя пользователя
     * @param string $password пароль пользователя
     *
     * */
    public function attempt(string $username, string $password): bool;
    /**
     * Функция выхода из аккаунта пользователя
     *
     * */
    public function logout(): void;
    /**
     * Функция для проверки наличия сессии которая отвечает за авторизацию пользователя
     *
     * */
    public function check(): bool;

    /**
     * Функция возвращает объект класса User если пользователь авторизован
     */
    public function user(): ?User;
    /**
     * Функция берет из конфига название таблицы для пользователей
     *
     * */
    public function table(): string;
    /**
     * Функция берет из конфига название поля из таблицы пользователей отвечающее за имя пользователя
     *
     * */
    public function username(): string;
    /**
     * Функция берет из конфига название поля из таблицы пользователей отвечающее за пароль пользователя
     *
     * */
    public function password(): string;
    /**
     * Функция берет из конфига название сессии которая создается при авторизации пользователя
     *
     * */
    public function sessionField(): string;
}