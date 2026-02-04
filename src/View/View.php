<?php

namespace App\View;

/**
 * Класс View
 *
 * Этот класс используется для работы с представлениями (view), включая подключение файлов представлений
 * и передачу данных в них.
 */
class View
{
    /**
     * @var array Ассоциативный массив данных, которые будут извлечены в файлы представлений.
     */
    private array $extractedElements = [];

    /**
     * Подключает файл представления и передает в него данные.
     *
     * @param string $fileName Имя файла представления (без расширения).
     * @param string $directory Директория, в которой находится файл.
     * @param array $extract Массив данных, которые будут доступны в представлении.
     * @return bool Возвращает false, если файл представления не найден.
     */
    public function page(string $fileName, string $directory, array $extract = [])
    {
        $filePath = APP_PATH . $directory . $fileName . '.php';

        if (!file_exists($filePath)) {
            return false; // Реализовать корректную обработку ошибки.
        }

        foreach ($this->extractedElements as $key => $element) {
            extract([$key => $element]);
        }

        require_once $filePath;
    }

    /**
     * Добавляет данные, которые будут извлечены в файлы представлений.
     *
     * @param array $extractedData Ассоциативный массив данных для извлечения.
     * @return void
     */
    public function addExtractList(array $extractedData): void
    {
        $this->extractedElements = array_merge($this->extractedElements, $extractedData);
    }
}
