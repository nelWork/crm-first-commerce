<?php

namespace App\Session;

/** Описание класса Session
 *  Класс используется для работы с Сессиями
 */
interface SessionInterface
{
    /** Функция для создания сессии
     * @param string $key ключ сессии
     * @param  $value | значение сессии
     * @return void
     */
    public function set(string $key, $value): void;

    /** Функция для получения данных из сессии
     * @param string $key ключ сессии
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /** Функция для получения данных из сессии и удаления после получения
     * @param string $key ключ сессии
     * @param $default
     * @return mixed
     */
    public function getFlash(string $key, $default = null);

    /** Функция для проверки наличия сессии
     * @param string $key ключ сессии
     * @return bool
     */
    public function has(string $key): bool;

    /** Функция для удаления одной сессии
     * @param string $key ключ для удаления
     * @return void
     */
    public function remove(string $key): void;

    /** Функция для удаления всех сессий
     * @return void
     */
    public function destroy(): void;
}