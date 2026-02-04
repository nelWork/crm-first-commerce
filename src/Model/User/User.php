<?php

namespace App\Model\User;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Mailer\Mailer;
use App\Mailer\MailerInterface;
use App\Storage\Storage;

/** Класс User
 *
 * Класс для работы с пользователем
 *
 */
class User
{
    private DatabaseInterface $database;

    private ConfigInterface $config;

    private MailerInterface $mailer;

    private Storage $storage;

    private int $id = 0;

    private string $login = '';
    private string $email = '';

    private int $role = 0;
    private string $password = '';

    private string $name = '';
    private string $surname = '';
    private string $lastname = '';

    private string $phone = '';

    private float $salary = 0;
    private float $procent = 0;

    private string $subordinates = '';

    private string $img_avatar = '2024/06/empty_avatar.png';

    private $registr_date = NULL;
    private $last_action = NULL;

    private string $reset_link = '';

    public array $fields = [
        "id","login", "email", "role", "password",
        "name", "surname", "lastname", "phone",
        "salary", "procent", "img_avatar",
        "registr_date", "last_action", 'reset_link',
        'subordinates'
    ];

    public function __construct(array $data = []){
        $this->config = new Config();
        $this->database = new Database($this->config);
        $this->mailer = new Mailer($this->config);
        $this->storage = new Storage($this->config);

        if(count($data) > 0){
            $user = $this->database->first('users',$data);

            if($user){
                foreach ($user as $key => $value) {
                    $this->$key = $value;
                }
                $this->updateLastAction();
            }
        }
    }

    /** Функция для редактирования данных пользователя
     * @param array $values
     * @return void
     */

    public function getUserSetting(): array
    {
        $settings = $this->database->first('user_settings');

        if(! $settings){
            $this->database->insert('user_settings',['id_user' => $this->id]);
            $settings = $this->database->first('user_settings');
        }

        return $settings;

    }

    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /** Функция для получения id пользователя
     * @return int
     */

    public function id(): int
    {
        return $this->id;
    }

    /** Функция для получения имени пользователя в формате Имя Ф.
     * @return string
     */
    public function fullName(): string
    {
        return $this->name .' ' . mb_substr($this->surname, 0, 1) .'.';
    }

    /** Функция для получения ФИО
     * @return string
     */

    public function FIO(): string
    {
        return $this->surname .' ' . $this->name .' ' .$this->lastname;
    }

    /** Функция для получения роли пользователя
     * @return string
     */
    public function getRole(): string
    {
        return $this->database->first('roles', ['id' => $this->role])['name'];
    }

    /** Функция для проверки есть ли у пользователя активные Уведомления
     * @return bool
     */

    public function hasActiveNotifications(): bool
    {
        return $this->database->first('notifications', ['id_to_user' => $this->id, 'status' => 0])['id'] ?? false;
    }

    /** Функция для получения списка активных уведомлений
     * @return array
     */

    public function getListNotificationsTo(): array
    {
        return $this->database->select('notifications', ['id_to_user' => $this->id, 'status' => 0]) ?? [];
    }

    public function getListNotificationsFrom(): array
    {
        return $this->database->select('notifications', ['id_from_user' => $this->id]) ?? [];
    }


    /** Функция для получения подопечных
     * @return array
     */
    public function getSubordinatesList(): array
    {
        if($this->subordinates == '')
            return [];

        return str_replace(
            '|','',
            explode(
                ",",
                trim($this->subordinates,',')
            )
        );
    }

    /** Функция для получения расположения автарки пользователя
     * @return string
     */
    public function avatar(): string
    {
        if(file_exists(APP_PATH .'/public/storage/' .$this->img_avatar)){
            return $this->storage->url($this->img_avatar);
        }
        else{
            return $this->storage->url('2024/06/empty_avatar.png');
        }
    }

    /** Функция для получения зарплаты
     * @return float|int
     */
    public function salary()
    {
        return $this->salary;
    }

    /** Функция для определения есть ли у пользователя админ права
     * @return bool
     */
    public function admin(): bool
    {
        $roleAccess = $this->database->first('roles', ['id' => $this->role]);

        if($roleAccess['admin_access'])
            return true;

        return false;
    }

    public function financeAdmin(): bool
    {
        $roleAccess = $this->database->first('roles', ['id' => $this->role]);

        if($roleAccess['finance_admin_access'])
            return true;

        return false;
    }

    /** Функция для определения есть ли у пользователя права просматривать все данные на основном сайте
     * @return bool
     */

    public function fullCRM(): bool
    {
        $roleAccess = $this->database->first('roles', ['id' => $this->role]);

        if($roleAccess['crm_data'])
            return true;

        return false;
    }

    /** Функция для определения логист ли пользователь или нет
     * @return bool
     */
    public function manager(): bool
    {
        $roleAccess = $this->database->first('roles', ['id' => $this->role]);

        if($roleAccess['name'] == 'Менеджер')
            return true;

        return false;
    }

    public function procent(): float
    {
        return $this->procent;
    }

    public function countApplication(array $condition = [])
    {
        $condition['id_user'] = $this->id;
        $count = $this->database->first('applications',$condition,['COUNT(*)'])['COUNT(*)'];

        return $count;
    }

    /** Функция для получения данных пользователя
     * @param array $conditions
     * @return array
     */
    public function get(array $conditions = []): array
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $data[$field] = $this->$field;
                if($field == 'img_avatar')
                    $data[$field] = $this->avatar();
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $returnedArray[$value] = $this->$value;
                if($value == 'img_avatar')
                    $returnedArray[$value] = $this->avatar();
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    /** Функция для обновления даты последней активности пользователя
     * @return void
     */
    public function updateLastAction()
    {
        if($this->exists()){
            $this->database->update('users',
                ['last_action'=>date('Y-m-d H:i:s')],
                ['id'=>$this->id]
            );
        }
    }


    public function resetPassword(): bool
    {
        // todo доделать отправку сообщения
        if(! $this->exists()){
            return false;
        }

        $resetLink = $this->createResetLink();

        $html = "<a href='http://pegas-new-crm/reset?c={$resetLink}'>Сбросить пароль</a>";

        if(! $this->mailer->sendMail([$html],$this->email)){
            return false;
        }

        $this->database->update('users',['reset_link' => $resetLink],['id' => $this->id]);

        return true;

    }

    private function createResetLink()
    {
        return sha1(uniqid(mt_rand(), true)) .sha1(uniqid(mt_rand(), true));
    }

    public function exists(): bool
    {
        if(! $this->id){
            return false;
        }

        if(! $this->database->first('users',['login' => $this->login])){
            return false;
        }

        return true;

    }

    public function search(array $data): User|false
    {
//        $this->
        return false;
    }

    /** Функция для добавления пользователя либо сохранение данных у уже существующего
     * @return bool
     */
    public function save(): bool
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->$field;
        }
        if($this->id > 0){
            $stmt = $this->database->update(
                'users',
                $data,
                ['id' => $this->id],
            );
        }
        else{
            $this->registr_date = date("Y-m-d");
            $this->last_action = date("Y-m-d H:i:s");

            $data['registr_date'] = $this->registr_date;
            $data['last_action'] = $this->last_action;
            $stmt = $this->database->insert(
                'users',
                $data
            );
        }


        return $stmt;
    }

}