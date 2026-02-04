<?php

namespace App\Model\Addition;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;

/**
 * Класс Addition
 *
 * Представляет модель "Приложения" с возможностью работы с базой данных, сохранения и обновления данных.
 */
class Addition
{
    /**
     * Экземпляр для работы с базой данных.
     *
     * @var DatabaseInterface
     */
    private DatabaseInterface $database;

    /**
     * Экземпляр для работы с конфигурацией.
     *
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Уникальный идентификатор приложения.
     *
     * @var int
     */
    private int $id = 0;

    /**
     * Название приложения.
     *
     * @var string
     */
    private string $name = '';

    /**
     * Описание приложения.
     *
     * @var string
     */
    private string $description = '';

    /**
     * Список пользователей, имеющих доступ к приложению.
     *
     * @var array
     */
    private array $users_access = [];

    /**
     * Список полей, доступных в модели.
     *
     * @var array
     */
    public array $fields = [
        "id", "name", "description", "users_access"
    ];

    /**
     * Конструктор класса Addition.
     *
     * @param array $data Условия для выборки данных из базы. Если массив пуст, данные не загружаются.
     */
    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $addition = $this->database->first('addition', $data);

            if ($addition) {
                foreach ($addition as $key => $value) {
                    if ($key == 'users_access') {
                        $this->users_access = str_replace(
                            '|',
                            '',
                            explode(",", trim($value, ','))
                        );
                    } else {
                        $this->$key = $value;
                    }
                }
            }
        }
    }

    /**
     * Редактирует данные модели.
     *
     * @param array $values Массив данных для обновления.
     */
    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Возвращает данные модели.
     *
     * @param array $conditions Условия выборки. Если массив пуст, возвращаются все данные модели.
     * @return array Массив данных.
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
     * Проверяет существование записи в базе данных.
     *
     * @return bool Возвращает true, если запись существует, иначе false.
     */
    public function exists(): bool
    {
        if (!$this->id) {
            return false;
        }

        if (!$this->database->first('addition', ['id' => $this->id])) {
            return false;
        }

        return true;
    }

    /**
     * Сохраняет или обновляет данные модели в базе данных.
     *
     * @return bool Успешность операции.
     */
    public function save(): bool
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->$field;
        }

        $users_access = '';
        foreach ($this->users_access as $user) {
            $users_access .= '|' . $user . '|,';
        }
        $data['users_access'] = $users_access;

        if ($this->id > 0) {
            $stmt = $this->database->update(
                'addition',
                $data,
                ['id' => $this->id],
            );
        } else {
            $stmt = $this->database->insert(
                'addition',
                $data
            );
            if ($stmt) {
                $this->id = $stmt;
            }
        }

        return $stmt;
    }
}
