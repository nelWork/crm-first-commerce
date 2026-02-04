<?php

namespace App\Config;
/**
 * Описание класса ConfigInterface
 *
 *  Класс для работы с файлами в папке configs
 *
 * */
interface ConfigInterface
{
    /**
     *  Метод для получения данных конфигураций
     *  @param string $key  принимает строку, имя файла затем поле через точку (file.key)
     *
     */
    public function get(string $key, $default = null);

}