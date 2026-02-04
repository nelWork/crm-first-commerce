<?php

namespace App\Http;

use App\Upload\Upload;
use App\Upload\UploadInterface;

/**
 * Описание класса Request
 *
 * Класс для работы с запросами на страницу, нужен чтобы брать
 * информацию которая хранится в массивах POST, GET, SERVER, COOKIE, FILES
 */
interface RequestInterface
{
    public static function createFromGlobals(): static;

    /** Функция для получения адреса страницы
     * @return string
     */
    public function uri(): string;

    /** Функция для определения как был отправлен запрос на страницу (POST либо GET)
     * @return string
     */
    public function method(): string;

    /** Функция для поиска по ключу данных в массиве POST либо GET
     * @param string $key ключ
     * @param $default
     * @return mixed
     */
    public function input(string $key, $default = null);

    /** Функция для поиска по ключу данных в массиве FILES
     * @param string $name
     * @return UploadInterface|null
     */
    public function file(string $name):?UploadInterface;
}