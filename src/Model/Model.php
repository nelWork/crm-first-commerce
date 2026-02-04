<?php

namespace App\Model;
use DateTime;

/**
 * Описание класса App\Model
 *
 * Класс шаблон для моделей
 */
class Model
{

    private string $upperLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private string $lowerLetters = 'abcdefghijklmnopqrstuvwxyz';

    /** Функция нужна для преобразования названий из формата sql в php (application_number в applicationNumber)
     * @param string $name
     * @return string
     */
    public function sqlToPhpNameConvert(string $name): string
    {
        $result = $name;

        if(str_contains($name, '_')){
            $result = "";

            $split_name = explode('_', $name);

            foreach ($split_name as $part) {
                $result .= ucfirst($part);
            }

            $result = lcfirst($result);

        }

        if(str_contains($name, '-')){
            $result = "";

            $split_name = explode('-', $name);

            foreach ($split_name as $part) {
                $result .= ucfirst($part);
            }

            $result = lcfirst($result);

        }

        return $result;
    }

    /** Функция нужна для преобразования названий из формата php в sql (applicationNumber в application_number)
     * @param string $name
     * @return string
     */
    public function phpToSqlNameConvert(string $name): string
    {
        $result = '';

        $chars = mb_str_split($name);
        foreach ($chars as $char) {
            if ($this->isUpperLetter($char)){
                $result .= '_'.mb_strtolower($char);
            }
            else
                $result .= $char;
        }

        return $result;
    }

    private function isUpperLetter(string $letter): bool
    {
        return str_contains($this->upperLetters, $letter);
    }

    private function isLowerLetter(string $letter): bool
    {
        return str_contains($this->lowerLetters, $letter);
    }

    private array $holidays = [
        '2025-01-01','2025-01-02','2025-01-03','2025-01-06','2025-01-07','2025-01-08',
        '2025-05-01','2025-05-02','2025-05-08','2025-05-09',
        '2025-06-12','2025-06-13',
        '2025-11-03','2025-11-04',
        '2025-12-31',
        '2026-01-01','2026-01-02','2026-01-03','2026-01-04','2026-01-05',
        '2026-01-06','2026-01-07','2026-01-08',
        '2026-01-09','2026-01-10','2026-01-11',
    ];

    public function addBankDays(DateTime $startDate, int $daysToAdd): string {
        $date = clone $startDate;
        $addedDays = 0;



        while ($addedDays < $daysToAdd) {
            $date->modify('+1 day');
            $weekday = $date->format('N'); // 1 (пн) - 7 (вс)
            $isWeekend = $weekday >= 6;
            $isHoliday = in_array($date->format('Y-m-d'), $this->holidays);

            if (!$isWeekend && !$isHoliday) {
                $addedDays++;
            }
        }

        return $date->format('Y-m-d');
    }


    public function calculateBankDays(string $startDate = '', string $textToParse = ''): string
    {
        preg_match_all(
            '/\b(\d+)(?:\s*[-–—]\s*(\d+))?(?:\s|<[^>]+>)*?(?:б[.\s]*д[.]?|банковских\s+дней)/ui',
            $textToParse,
            $matches
        );

        $days = [];

        // dump($matches);

        foreach ($matches[1] as $i => $first) {
            $firstInt = (int)$first;
            $secondInt = isset($matches[2][$i]) && $matches[2][$i] !== '' ? (int)$matches[2][$i] : null;

            if ($secondInt !== null) {
                $days[] = max($firstInt, $secondInt);
            } else {
                $days[] = $firstInt;
            }
        }
        // Если ничего не найдено, по умолчанию 11
        $maxDay = !empty($days) ? max($days) : 11;

        $datePayment =
            $this->addBankDays(
                new DateTime($startDate),
                $maxDay - 1
            );

        return $datePayment;
    }
}