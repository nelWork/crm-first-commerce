<?php

namespace App\Http;
/**
 * Описание класса Redirect
 *
 * Класс для перенаправления на другую страницу
 */
interface RedirectInterface
{
    /** Функция для перенаправления на другую страницу
     * @param string $url адрес по которому необходимо перейти
     * @return mixed ничего не возвращает
     */
    public function to(string $url);

}