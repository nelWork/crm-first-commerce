<?php

namespace App\Model\Client;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Model\ContactFace\ContactFace;
use App\Model\Model;

/**
 * Класс для работы с клиентами.
 *
 * Этот класс предоставляет методы для создания, редактирования, получения и сохранения данных о клиентах.
 * Также включает функциональность для работы с доступами менеджеров, связанных с клиентами.
 */
class Client extends Model
{
    /**
     * Интерфейс для работы с базой данных.
     *
     * @var DatabaseInterface
     */
    private DatabaseInterface $database;

    /**
     * Интерфейс для конфигурации.
     *
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Идентификатор клиента.
     *
     * @var int
     */
    private int $id = 0;

    /**
     * Идентификатор типа налогообложения.
     *
     * @var string
     */
    private string $typeTaxationId  = '';

    /**
     * ИНН клиента.
     *
     * @var string
     */
    private string $inn = '';

    /**
     * Юридический адрес клиента.
     *
     * @var string
     */
    private string $legalAddress = '';

    /**
     * Название клиента.
     *
     * @var string
     */
    private string $name = '';

    /**
     * Дополнительная информация о клиенте.
     *
     * @var string
     */
    private string $info = '';

    /**
     * Полное имя клиента.
     *
     * @var string
     */
    private string $fullName = '';

    /**
     * Должность клиента.
     *
     * @var string
     */
    private string $jobTitle = '';

    /**
     * Телефон клиента.
     *
     * @var string
     */
    private string $phone = '';

    /**
     * Электронная почта клиента.
     *
     * @var string
     */
    private string $email = '';

    /**
     * Доступ пользователей к клиенту.
     *
     * @var string
     */
    private string $usersAccess =  '';

    /**
     * Флаг видимости клиента.
     *
     * @var int
     */
    private int $visible = 0;

    /**
     * Формат заявки клиента.
     *
     * @var int
     */
    private int $applicationFormat = 0;

    /**
     * Формат работы с клиентом.
     *
     * @var string
     */
    private string $formatWork = '';

    /**
     * Отсрочка платежа у клиента.
     *
     * @var string
     */
    private string $paymentDeferment = '';

    /**
     * Список полей клиента для сохранения в базе данных.
     *
     * @var array
     */

    private int $percent = 0;

    private int $quantityApplications = 0;

    private int $inWork = 1;

    public array $fields = [
        'id', 'typeTaxationId', 'inn', 'legalAddress', 'name',
        'info', 'fullName', 'jobTitle', 'phone', 'email',
        'usersAccess', 'visible', 'applicationFormat',
        'formatWork','percent', 'quantityApplications',
        'paymentDeferment', 'inWork'
    ];

    public string $nameTable = 'clients';

    /**
     * Конструктор класса.
     *
     * Инициализирует объект клиента с переданными данными или создает пустой объект, если данные не переданы.
     * Если данные переданы, происходит извлечение информации о клиенте из базы данных.
     *
     * @param array $data Данные клиента для инициализации.
     */
    public function __construct(array $data = [], string $nameTable = 'clients')
    {
        $this->config = new Config();
        $this->database = new Database($this->config);

        $this->nameTable = $nameTable;

        if (count($data) > 0) {
            $client = $this->database->first($this->nameTable , $data);

            if (! $client)
                return false;

            foreach ($client as $key => $value) {
                $newKey = $this->sqlToPhpNameConvert($key);
                $this->$newKey = $value;
            }
        }
    }

    /**
     * Редактирует данные клиента.
     *
     * Метод позволяет обновить данные клиента, передав их в виде ассоциативного массива.
     *
     * @param array $values Ассоциативный массив с новыми значениями для обновления.
     */
    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $newKey = $this->sqlToPhpNameConvert($key);
            $this->$newKey = $value;
        }
    }

    /**
     * Получает список менеджеров, имеющих доступ к этому клиенту.
     *
     * Метод извлекает пользователей по их идентификаторам, указанным в поле `usersAccess`.
     *
     * @return array Список менеджеров с их идентификаторами, именами и фамилиями.
     */
    public function getManagersAccess(): array
    {
        $arrayUsersAccess = str_replace('|','', explode(",", trim($this->usersAccess,',')));

        return $this->database->superSelect(
            'users',
            ['id' => $arrayUsersAccess],
            [],
            -1,
            ['name','surname','id'],
            0,
            'OR'
        );
    }

    public function countApplication(){
        return $this->database->select('applications',['client_id_Client' => $this->get()['id']],[],-1,['COUNT(id)'])[0]['COUNT(id)'];
    }

    public function getLastComment(): string
    {
        $comment = $this->database->select('clients_comments',['id_client' => $this->id,'visible' => 1],['id' => 'DESC'],1);

        $textComment = 'Нет комментариев';

        if($comment){
            $textComment = $comment[0]['comment'];
        }   

        return $textComment;
    }

    /**
     * Получает данные клиента.
     *
     * Возвращает все данные клиента или только те поля, которые указаны в условиях.
     *
     * @param array $conditions Условия для выборки полей клиента.
     * @return array|string Массив с данными клиента или сообщение об ошибке.
     */
    public function get(array $conditions = []): array|string
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $data[$this->phpToSqlNameConvert($field)] = $this->$field;
            }
            $data['managersAccess'] = $this->getManagersAccess();
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as $value) {
                $returnedArray[$this->phpToSqlNameConvert($value)] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    /**
     * Сохраняет данные клиента в базе данных.
     *
     * Если у клиента уже есть идентификатор, происходит обновление данных клиента в базе данных.
     * Если идентификатор отсутствует, создается новый клиент.
     *
     * @return bool Возвращает true, если операция сохранения прошла успешно, и false в противном случае.
     */
    public function save(): bool
    {
        $newData = $this->get();
        unset($newData['managersAccess']);

        if ($this->id > 0) {
            $stmt = $this->database->update($this->nameTable , $newData, ["id" => $this->id]);
            return $stmt;
        }
        else {
            $stmt = $this->database->insert($this->nameTable , $newData);

            if ($stmt) {
                $this->id = $stmt;
                return true;
            }

            return $stmt;
        }
    }
}
