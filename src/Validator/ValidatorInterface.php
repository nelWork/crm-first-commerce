<?php

namespace App\Validator;

/** Описание класса Validator
 *
 * Класс нужен для проверки полей подходят ли они под заданные правила (валидация полей)
 */
interface ValidatorInterface
{
    /** Функция проверки полей по правилам
     * @param array $data массив полей
     * @param array $rules
     * @return bool
     */
    public function validate(array $data, array $rules): bool;

    /** Возвращает ошибки, которые возникли при проверке на валидацию
     * @return array
     */
    public function errors(): array;

}