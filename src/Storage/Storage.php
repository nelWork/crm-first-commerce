<?php

namespace App\Storage;

use App\Config\ConfigInterface;
use App\Storage\StorageInterface;

/**
 * Класс Storage
 *
 * Реализует методы для работы с хранилищем файлов, включая генерацию URL-адресов и получение содержимого файлов.
 */
class Storage implements StorageInterface
{
    /**
     * Конструктор класса Storage.
     *
     * @param ConfigInterface $config Конфигурация приложения.
     */
    public function __construct(
        private ConfigInterface $config
    )
    {
    }

    /**
     * Генерирует полный URL для указанного пути в хранилище.
     *
     * @param string $path Путь к файлу в хранилище.
     * @return string Полный URL файла.
     */
    public function url(string $path): string
    {
        $url = $this->config->get('app.url');
        return $url . "storage/$path";
    }

    /**
     * Получает содержимое файла из хранилища.
     *
     * @param string $path Путь к файлу в хранилище.
     * @return string Содержимое файла.
     * @throws \RuntimeException Если файл не существует.
     */
    public function get(string $path): string
    {
        $filePath = $this->storagePath($path);
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Файл не найден: $filePath");
        }
        return file_get_contents($filePath);
    }

    /**
     * Возвращает полный путь к файлу в файловой системе.
     *
     * @param string $path Путь к файлу в хранилище.
     * @return string Полный путь к файлу.
     */
    private function storagePath(string $path): string
    {
        return APP_PATH . '/storage/' . $path;
    }
}
