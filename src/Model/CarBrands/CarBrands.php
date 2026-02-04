<?php

namespace App\Model\CarBrands;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;

/**
 * Класс CarBrands
 *
 * Этот класс предоставляет функционал для работы с брендами автомобилей,
 * включая добавление, редактирование, получение данных и сохранение информации в базе данных.
 */
class CarBrands
{
    /**
     * @var DatabaseInterface Интерфейс для работы с базой данных.
     */
    private DatabaseInterface $database;

    /**
     * @var ConfigInterface Интерфейс конфигурации.
     */
    private ConfigInterface $config;

    /** @var int Идентификатор бренда. */
    private int $id = 0;

    /** @var string Название бренда. */
    private string $name = '';

    /**
     * @var array Поля бренда, доступные для работы.
     */
    public array $fields = [
        "id", "name"
    ];

    /**
     * Конструктор класса.
     *
     * @param array $data Массив данных для инициализации бренда.
     */
    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $brand = $this->database->first('car_brands', $data);

            if ($brand) {
                foreach ($brand as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Получает идентификатор бренда.
     *
     * @return int Идентификатор бренда.
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Получает название бренда.
     *
     * @return string Название бренда.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Редактирует данные бренда.
     *
     * @param array $values Ассоциативный массив с новыми значениями.
     * @return void
     */
    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Получает данные бренда на основе заданных условий.
     *
     * @param array $conditions Массив условий для фильтрации.
     * @return array Массив данных бренда.
     */
    public function get(array $conditions = []): array
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $data[$field] = $this->$field;
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as $value) {
                $returnedArray[$value] = $this->$value;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    /**
     * Проверяет, существует ли бренд в базе данных.
     *
     * @return bool Возвращает true, если бренд существует, иначе false.
     */
    public function exists(): bool
    {
        if (!$this->id) {
            return false;
        }

        if (!$this->database->first('car_brands', ['id' => $this->id])) {
            return false;
        }

        return true;
    }

    /**
     * Сохраняет данные бренда в базу данных.
     *
     * @return bool Возвращает true при успешном сохранении, иначе false.
     */
    public function save(): bool
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->$field;
        }

        if ($this->id > 0) {
            $stmt = $this->database->update(
                'car_brands',
                $data,
                ['id' => $this->id],
            );
        } else {
            $stmt = $this->database->insert(
                'car_brands',
                $data
            );
            if ($stmt) {
                $this->id = $stmt;
            }
        }

        return $stmt;
    }
}
