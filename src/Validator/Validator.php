<?php

namespace App\Validator;

use App\Database\DatabaseInterface;
use App\Validator\ValidatorInterface;

/**
 * Класс Validator
 *
 * Реализует проверку данных на соответствие заданным правилам.
 */
class Validator implements ValidatorInterface
{
    /**
     * @var array Массив ошибок, возникших при проверке.
     */
    private array $errors = [];

    /**
     * @var DatabaseInterface Интерфейс для взаимодействия с базой данных.
     */
    private DatabaseInterface $database;

    /**
     * @var array Данные, которые проверяются.
     */
    private array $data;

    /**
     * Устанавливает объект базы данных.
     *
     * @param DatabaseInterface $database Объект для взаимодействия с базой данных.
     * @return void
     */
    public function setDatabase(DatabaseInterface $database): void
    {
        $this->database = $database;
    }

    /**
     * Выполняет валидацию данных на основе заданных правил.
     *
     * @param array $data Данные для проверки.
     * @param array $rules Правила проверки. Ключи массива - названия полей, значения - массив правил.
     * @return bool Возвращает true, если ошибок нет; иначе false.
     */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];
        $this->data = $data;

        foreach ($rules as $key => $ruleSet) {
            foreach ($ruleSet as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;

                $error = $this->validateRule($key, $ruleName, $ruleValue);

                if ($error) {
                    $this->errors[$key][] = $error;
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Возвращает массив ошибок, возникших при проверке.
     *
     * @return array Массив ошибок.
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Выполняет проверку отдельного правила.
     *
     * @param string $key Название поля.
     * @param string $ruleName Имя правила.
     * @param string|null $ruleValue Значение правила (если есть).
     * @return string|null Возвращает текст ошибки, если правило нарушено; иначе null.
     */
    private function validateRule(string $key, string $ruleName, ?string $ruleValue = null): ?string
    {
        $value = $this->data[$key] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    return 'Поле обязательно для заполнения';
                }
                break;

            case 'min':
                if (mb_strlen($value) < $ruleValue) {
                    return "Поле должно содержать как минимум $ruleValue символов";
                }
                break;

            case 'max':
                if (mb_strlen($value) > $ruleValue) {
                    return "Поле не должно превышать $ruleValue символов";
                }
                break;

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return 'В данном поле должен быть Email';
                }
                break;

            case 'unique':
                if (!$this->validateUnique($value, $ruleValue)) {
                    return 'Пользователь с такими данными уже существует';
                }
                break;

            case 'not-zero':
                if ($value == 0) {
                    return 'Выберите какой-нибудь вариант';
                }
                break;
        }

        return null;
    }

    /**
     * Проверяет уникальность значения в заданной таблице и поле.
     *
     * @param mixed $value Значение для проверки.
     * @param string $tableField Таблица и поле в формате "table.field".
     * @return bool Возвращает true, если значение уникально; иначе false.
     */
    private function validateUnique($value, string $tableField): bool
    {
        [$table, $field] = explode('.', $tableField);

        return !$this->database->first($table, [$field => $value]);
    }
}
