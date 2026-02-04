<?php

namespace App\User\Model\Journal;

use App\User\Model\Model;

class ParseTXT extends Model
{
    private string $pathDocTemplate = APP_PATH .'/public/doc/journal/';

    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function parseDocuments() {
        // Читаем содержимое файла
        $content = file_get_contents($this->filePath);


        if ($content === false) {
            die("Ошибка при чтении файла");
        }

        // Устанавливаем кодировку в UTF-8, если необходимо
        $content = mb_convert_encoding($content, 'UTF-8', 'Windows-1251');

        // Разбиваем файл на секции по шаблону
        preg_match_all('/СекцияДокумент=Платежное поручение(.*?)КонецДокумента/su', $content, $matches);

        return array_map('trim', $matches[1]); // Возвращаем массив документов
    }

    public function parseKeyValue($document) {
        $lines = explode("\n", $document);
        $assocArray = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $assocArray[trim($key)] = trim($value);
            }
        }

        // Проверяем ключ "НазначениеПлатежа" и ищем текст по формату
        if (isset($assocArray['НазначениеПлатежа'])) {

            if($assocArray['ПлательщикИНН'] == '6685191957' OR
                $assocArray['ПлательщикИНН'] == '661221287051' OR
                $assocArray['ПлательщикИНН'] == '661221186335'
            )
                $assocArray['Заявка'] = $this->extractRequestNumber($assocArray['НазначениеПлатежа']);

            // Если заявка не найдена, ищем счета
            if (!isset($assocArray['Заявка'])) {
                $assocArray['Счета'] = $this->extractAccounts($assocArray['НазначениеПлатежа']);
            }
        }

//        var_dump($assocArray);

        return $assocArray;
    }

    private function extractRequestNumber($text) {
        // Обновленное регулярное выражение для учёта всех форматов
        if (preg_match('/Заявк[а,и]?\s*№?\s*"?\s*(\d+-?П?)\s*"*/ui', $text, $matches)) {
            return isset($matches[1]) ? $matches[1] : null;
        }
        return null; // Если не найдено, возвращаем null
    }

    private function extractAccounts($text) {
        if (stripos($text, 'Аванс') !== false || stripos($text, 'Возврат подотчетных средств') !== false) {
            return null;
        }

        // Предварительная очистка текста
        $text = preg_replace('/\b(2023|2024|2025)\b/u', '', $text);
        $text = preg_replace('/НДС\s*\(.*?\)/ui', '', $text);
        $text = preg_replace('/\b(\d{1,2}\.\d{1,2}\.\d{4})г\b/u', '$1', $text);
        $text = preg_replace('/ДОГОВОР\s*№?\s*\d+\/\d+/ui', '', $text);
        $text = preg_replace('/##.*?##/u', '', $text);
        $text = str_replace('\\', '/', $text);

        // Паттерны по приоритету
        $patterns = [
            '/(?:№\s*)?(\d+\/\d+)/u',              // 1. "№ 2/7837" или "2/7837"
            '/№\s*(\d+)/u',                         // 2. "№ 7837"
            '/№\s*"*(\d+)"*/u',                     // 3. '№"7837"' или '№ "7837"'
            '/(\d+)\s+[Оо][Тт]/u',                  // 4. "7837 от"
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                // Удаляем повторы, если есть
                $unique = array_unique(array_map('trim', $matches[1]));
                if (!empty($unique)) {
                    return array_values($unique); // Возвращаем только первый успешный паттерн
                }
            }
        }

        return null;
    }





    public function getParsedDocuments() {
        $documents = $this->parseDocuments();
//        dd($documents);
        return array_map([$this, 'parseKeyValue'], $documents);
    }



}