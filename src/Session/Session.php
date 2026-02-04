<?php

namespace App\Session;

use App\Session\SessionInterface;

class Session implements SessionInterface
{

    public function __construct()
    {
        session_start();
    }

//    Задает сессию
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

//  Получает сессию
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

//  Получает сессию, затем удаляет эту сессию
    public function getFlash(string $key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->remove($key);

        return $value;
    }

//    Проверка на наличие сессии
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

//    Удаляет сессию
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

//    Удаляет все сессии
    public function destroy(): void
    {
        session_destroy();
    }
}