<?php

namespace App\Upload;

use App\Upload\UploadInterface;

/**
 * Класс Upload
 *
 * Обеспечивает загрузку файлов на сервер, создание структуры каталогов на основе текущей даты и управление именами файлов.
 */
class Upload implements UploadInterface
{
    /**
     * Конструктор класса Upload.
     *
     * @param string $name Имя загружаемого файла.
     * @param string $type MIME-тип файла.
     * @param string $tmpName Временный путь к файлу.
     * @param int $size Размер файла в байтах.
     * @param int $error Код ошибки загрузки файла.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly string $tmpName,
        public readonly int $size,
        public readonly int $error
    )
    {
    }

    /**
     * Загружает файл в указанную директорию.
     *
     * @param string $newPath Путь относительно директории хранилища.
     * @param string|null $newFileName Новое имя файла (если не указано, генерируется автоматически).
     * @return string|false Путь к загруженному файлу или `false` в случае неудачи.
     */
    public function upload(string $newPath, string $newFileName = null): string|false
    {
        $dir = $this->getTimeFolder();
        $storage = APP_PATH . "/public/storage/$dir/$newPath";

        if (!is_dir($storage)) {
            mkdir($storage, 0777, true);
        }

        if ($newFileName === "") {
            $newFileName = null;
        }

        if ($newFileName !== null) {
            if (file_exists($storage . "/" . $newFileName . "." . $this->getExtension())) {
                $newFileName = $newFileName . $this->generateFileName();
            } else {
                $newFileName = $newFileName . "." . $this->getExtension();
            }
        }

        $fileName = $newFileName ?? $this->generateFileName();

        if (move_uploaded_file($this->tmpName, "$storage/$fileName")) {
            return "$dir/$newPath/$fileName";
        }

        return false;
    }

    /**
     * Возвращает путь к папке, основанный на текущей дате (год/месяц).
     *
     * @return string Путь к папке.
     */
    private function getTimeFolder(): string
    {
        $thisYear = date("Y");
        $thisMonth = date("m");
        $dir = $thisYear . "/" . $thisMonth;

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    /**
     * Возвращает расширение файла.
     *
     * @return string Расширение файла.
     */
    public function getExtension(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    /**
     * Генерирует уникальное имя для файла с использованием SHA1 и случайного числа.
     *
     * @return string Сгенерированное имя файла.
     */
    private function generateFileName(): string
    {
        return sha1(uniqid(mt_rand(), true)) . "." . $this->getExtension();
    }
}
