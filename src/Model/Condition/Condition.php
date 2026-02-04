<?php

namespace App\Model\Condition;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;

/**
 * Класс Condition
 *
 * Представляет модель обязательных условий для работы с базой данных.
 */
class Condition
{
    /**
     * Экземпляр базы данных.
     *
     * @var DatabaseInterface
     */
    private DatabaseInterface $database;

    /**
     * Экземпляр конфигурации.
     *
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Идентификатор условия.
     *
     * @var int
     */
    private int $id = 0;

    /**
     * Название условия.
     *
     * @var string
     */
    private string $name = '';

    /**
     * Описание условия.
     *
     * @var string
     */
    private string $description = '';

    /**
     * Список полей для работы с базой данных.
     *
     * @var array
     */
    public array $fields = [
        "id", "name", "description"
    ];

    /**
     * Конструктор класса Condition.
     *
     * Инициализирует экземпляры базы данных и конфигурации.
     * Загружает данные условия, если переданы параметры.
     *
     * @param array $data Параметры для поиска условия в базе данных.
     */
    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $condition = $this->database->first('conditions', $data);

            if ($condition) {
                foreach ($condition as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Редактирует данные условия.
     *
     * @param array $values Ассоциативный массив значений для обновления.
     */
    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Возвращает данные условия.
     *
     * Если указаны условия, возвращает только выбранные поля.
     *
     * @param array $conditions Список полей для выборки.
     * @return array Данные условия.
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
     * Проверяет существование условия в базе данных.
     *
     * @return bool true, если условие существует, иначе false.
     */
    public function exists(): bool
    {
        if (!$this->id) {
            return false;
        }

        if (!$this->database->first('conditions', ['id' => $this->id])) {
            return false;
        }

        return true;
    }

    /**
     * Сохраняет данные условия в базе данных.
     *
     * Если ID не задан, создаёт новую запись, иначе обновляет существующую.
     *
     * @return bool Результат выполнения операции.
     */
    public function save(): bool
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->$field;
        }

        if ($this->id > 0) {
            $stmt = $this->database->update(
                'conditions',
                $data,
                ['id' => $this->id],
            );
        } else {
            $stmt = $this->database->insert(
                'conditions',
                $data
            );
            if ($stmt) {
                $this->id = $stmt;
            }
        }

        return $stmt;
    }
}
