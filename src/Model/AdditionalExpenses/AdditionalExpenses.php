<?php

namespace App\Model\AdditionalExpenses;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

/**
 * Класс AdditionalExpenses
 *
 * Класс для управления дополнительными расходами, включая их создание, редактирование, сохранение и извлечение данных.
 */
class AdditionalExpenses extends Model
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
     * Тип расхода.
     *
     * @var string
     */
    private string $typeExpenses = '';

    /**
     * Сумма расхода.
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
     * Комментарий к расходу.
     *
     * @var string
     */
    private string $comment = '';

    /**
     * Тип назначения расхода (например, для проекта или компании).
     *
     * @var int
     */
    private int $typeFor = 0;

    /**
     * Идентификатор заявки, связанной с расходом.
     *
     * @var int
     */
    private int $idApplication = 0;

    /**
     * Оплачено ли
     *
     * @var int
     */
    private int $isPaid = 0;

    /**
     * Список полей, используемых в модели.
     *
     * @var array
     */
    public array $fields = [
        "id", "typeExpenses", "sum", "typePayment",
        "comment", "typeFor", "idApplication", "isPaid"
    ];

    /**
     * Конструктор класса.
     *
     * Инициализирует конфигурацию и базу данных, а также заполняет данные, если они переданы.
     *
     * @param array $data Ассоциативный массив с данными для инициализации объекта.
     */

    public string $nameTable = 'additional_expenses';

    public function __construct(array $data = [], string $nameTable = 'additional_expenses')
    {
        $this->nameTable = $nameTable;
        $this->config = new Config();
        $this->database = new Database($this->config);

        if (count($data) > 0) {
            $expenses = $this->database->first($this->nameTable, $data);

            if ($expenses) {
                foreach ($expenses as $key => $value) {
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

        if($this->nameTable == 'additional_expenses_prr'){
            unset($newData['type_for']);
        }

        if ($this->id > 0) {
            $stmt = $this->database->update(
                $this->nameTable,
                $newData,
                ['id' => $this->id],
            );

        } else {

            $stmt = $this->database->insert(
                $this->nameTable,
                $newData
            );
        }

        return $stmt;
    }
}
