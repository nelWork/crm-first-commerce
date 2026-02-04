<?php

namespace App\Database;
/**
 * Описание класса Database
 *
 *  Класс для работы с базой данных
 *
 * */
interface DatabaseInterface
{
    /** Функция выборки из базы данных по заданным параметрам
     *
     * @param string $table название таблицы
     * @param array $conditions массив условий (например ['id' => 2] - выбирает запись где id = 2)
     * @param array $order фильтр по записям от большого к меньшему (['id' => 'DESC']), от меньшего к большему (['id' => 'ASC'])
     * @param int $limit количество записей которые будут браться (-1 безлимит)
     * @param array $select массив полей которые будут браться
     * @param int $offset количество пропущенных записей отступ
     * @param string $selectionOperator оператор группировки условий ('OR', 'AND')
     * @param string $comparisonOperator оператор сравнения условий ('=','!=','>','<')
     * @return array|false возвращает массив данных либо false если не нашел такие записи
     */
    public function select(string $table, array $conditions = [], array $order = [], int $limit = -1, array $select = ['*'],
                           int $offset = 0, string $selectionOperator = 'AND',$comparisonOperator = '='): array|false;

    public function superSelect(string $table, array $conditions = [], array $order = [], int $limit = -1, array $select = ['*'],
                                int $offset = 0, string $selectionOperator = 'AND', $comparisonOperator = '='): array|false;

    /** Функция для вставки новых записей в БД
     * @param string $table название таблицы
     * @param array $data массив данных
     * @return mixed возвращает либо сообщение об ошибки либо id новой записи
     *
     */
    public function insert(string $table, array $data);

    /** Функция для обновления данных в БД
     *
     * @param string $table название таблицы
     * @param array $data массив данных
     * @param array $conditions массив условий для выборки записей для изменения
     * @return bool
     */
    public function update(string $table, array $data, array $conditions): bool;

    /** Функция для удаления данных из БД
     * @param string $table название таблицы
     * @param array $conditions массив условий для выборки записей
     * @return bool
     */
    public function delete(string $table,array $conditions): bool;

    /** Функция для выбора первой записи из БД подходящей под условия
     * @param string $table название таблицы
     * @param array $conditions массив условий
     * @return array|false возвращает массив данных либо false если не нашел такие записи
     */
    public function first(string $table, array $conditions = [],array $select = ['*']): array|false;

    /** Функция для проверки существования в БД подходящей под условия записи
     * @param string $table название таблицы
     * @param array $conditions массив условий
     * @return array|false возвращает массив данных либо false если не нашел такие записи
     */
    public function exists(string $table, array $conditions = []): array|bool;
}