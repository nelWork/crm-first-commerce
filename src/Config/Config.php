<?php

namespace App\Config;

use App\Config\ConfigInterface;
/**
 * Описание класса Config
 *
 *  Класс для работы с файлами в папке configs
 *
 * */
class Config implements ConfigInterface
{

/**
 *  Метод для получения данных конфигураций
 *  @param string $key  принимает строку, имя файла затем поле через точку (file.key)
 *
 */
    public function get(string $key, $default = null)
    {
        [$file, $key] = explode('.', $key);

        $configPath =  APP_PATH."/configs/$file.php";

        if (! file_exists($configPath)) {
            return $default;
        }

        $config = require $configPath;

        return $config[$key] ?? $default;
    }
}