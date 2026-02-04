<?php

namespace App\Model\Marshrut;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\Model;

/**
 * Класс Marshrut
 *
 * Представляет модель маршрута для работы с базой данных.
 */
class Marshrut extends Model
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
     * Идентификатор маршрута.
     *
     * @var int
     */
    private int $id = 0;

    /**
     * Тип маршрута (например, для клиента или перевозчика).
     *
     * @var int
     */
    private int $typeFor = 0;

    /**
     * Направление маршрута (погрузка/разгрузка).
     *
     * @var int
     */
    private int $direction = 0;

    /**
     * Город, связанный с маршрутом.
     *
     * @var string
     */
    private string $city = '';

    /**
     * Адрес маршрута.
     *
     * @var string
     */
    private string $address = '';

    /**
     * Дата маршрута.
     *
     * @var string|null
     */
    private $date = NULL;

    /**
     * Время маршрута.
     *
     * @var string
     */
    private $time = 'Время согласовать с менеджером';

    /**
     * Контактное лицо.
     *
     * @var string
     */
    private string $contact = '';

    /**
     * Телефонное число контактного лица.
     *
     * @var string
     */
    private string $phone = '';

    /**
     * Метод погрузки/разгрузки.
     *
     * @var string
     */
    private string $loadingMethod = '';

    /**
     * Идентификатор связанной заявки.
     *
     * @var int
     */
    private int $idApplication = 0;

    /**
     * Сортировка маршрута (позиция).
     *
     * @var int
     */
    private int $sort = 0;

    /**
     * Поля, используемые для работы с базой данных.
     *
     * @var array
     */
    public array $fields = [
        "id", "direction", "typeFor", "city", "address", "date",
        "time", "contact", "phone", "loadingMethod", "idApplication", "sort"
    ];

    private bool $isTSApplication = false;

    /**
     * Конструктор класса Marshrut.
     *
     * Инициализирует экземпляры базы данных и конфигурации.
     * Загружает данные маршрута, если переданы параметры.
     *
     * @param array $data Параметры для поиска маршрута в базе данных.
     */
    public function __construct(array $data = [], $isTSApplication = false)
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        $this->isTSApplication = $isTSApplication;

        if (count($data) > 0) {

            if($this->isTSApplication){
                $marshrut = $this->database->first('routes_ts', $data);
            }
            else{
                $marshrut = $this->database->first('routes', $data);
            }

            if ($marshrut) {
                foreach ($marshrut as $key => $value) {
                    $newKey = $this->sqlToPhpNameConvert($key);
                    $this->$newKey = $value;
                }
            }
        }
    }

    /**
     * Редактирует данные маршрута.
     *
     * @param array $values Ассоциативный массив значений для обновления.
     */
    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $newKey = $this->sqlToPhpNameConvert($key);
            $this->$newKey = $value;
        }
    }

    /**
     * Возвращает данные маршрута.
     *
     * Если указаны условия, возвращает только выбранные поля.
     *
     * @param array $conditions Список полей для выборки.
     * @return array Данные маршрута.
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
     * Сохраняет данные маршрута в базе данных.
     *
     * Если ID не задан, создаёт новую запись, иначе обновляет существующую.
     *
     * @return bool|int Результат выполнения операции.
     */
    public function save()
    {
        $newData = $this->get();

        if ($this->id > 0) {
            if($this->isTSApplication) {
                $stmt = $this->database->update(
                    'routes_ts',
                    $newData,
                    ['id' => $this->id],
                );
            }
            else{
                $stmt = $this->database->update(
                    'routes',
                    $newData,
                    ['id' => $this->id],
                );
            }
        } else {
            if($this->isTSApplication) {
                $stmt = $this->database->insert(
                    'routes_ts',
                    $newData
                );
            }
            else{
                $stmt = $this->database->insert(
                    'routes',
                    $newData
                );
            }
        }

        return $stmt;
    }
}
