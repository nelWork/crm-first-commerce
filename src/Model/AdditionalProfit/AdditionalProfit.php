<?php

namespace App\Model\AdditionalProfit;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

/**
 * Класс AdditionalProfit
 *
 * Класс для управления дополнительной прибылью, включая создание, редактирование, сохранение и получение данных.
 */
class AdditionalProfit extends Model
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
     * Уникальный идентификатор записи.
     *
     * @var int
     */
    private int $id = 0;

    /**
     * Тип прибыли.
     *
     * @var string
     */
    private string $type = '';

    /**
     * Сумма прибыли.
     *
     * @var string
     */
    private string $sum = '';

    /**
     * Тип оплаты.
     *
     * @var string
     */
    private string $typePayment = '';

    /**
     * Комментарий к прибыли.
     *
     * @var string
     */
    private string $comment = '';

    /**
     * Идентификатор связанной заявки.
     *
     * @var int
     */
    private int $idApplication = 0;

    /**
     * Список полей, используемых в модели.
     *
     * @var array
     */
    public array $fields = [
        "id", "type", "sum", "typePayment",
        "comment", "idApplication"
    ];

    /**
     * Конструктор класса.
     *
     * Инициализирует конфигурацию и базу данных, а также заполняет данные, если они переданы.
     *
     * @param array $data Ассоциативный массив с данными для инициализации объекта.
     */
    public function __construct(array $data = [])
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $profit = $this->database->first('additional_profit', $data);

            if ($profit) {
                foreach ($profit as $key => $value) {
                    $newKey = $this->sqlToPhpNameConvert($key);
                    $this->$newKey = $value;
                }
            }
        }
    }

    /**
     * Редактирует данные объекта.
     *
     * @param array $values Ассоциативный массив с новыми значениями.
     */
    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $newKey = $this->sqlToPhpNameConvert($key);
            $this->$newKey = $value;
        }
    }

    /**
     * Извлекает данные объекта.
     *
     * @param array $conditions Условия для фильтрации данных (опционально).
     * @return array Ассоциативный массив с данными объекта.
     */
    public function get(array $conditions = []): array
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $newField = $this->phpToSqlNameConvert($field);
                $data[$newField] = $this->$field;
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as $value) {
                $newValue = $this->phpToSqlNameConvert($value);
                $returnedArray[$newValue] = $this->$value;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    /**
     * Сохраняет данные объекта в базе данных.
     *
     * @return bool Успешность операции.
     */
    public function save(): bool
    {
        $newData = $this->get();

        if ($this->id > 0) {
            $stmt = $this->database->update(
                'additional_profit',
                $newData,
                ['id' => $this->id],
            );
        } else {
            $stmt = $this->database->insert(
                'additional_profit',
                $newData
            );
        }

        return $stmt;
    }
}
