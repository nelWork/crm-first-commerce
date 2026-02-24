-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 24 2026 г., 18:39
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pegas_new_clear`
--

-- --------------------------------------------------------

--
-- Структура таблицы `company_calculator_settings`
--

CREATE TABLE `company_calculator_settings` (
  `id` int NOT NULL,
  `coefficient_income_vat` decimal(10,3) NOT NULL,
  `coefficient_consumption_vat` decimal(10,3) NOT NULL,
  `coefficient_consumption_not_vat` decimal(10,3) NOT NULL,
  `coefficient_consumption_cash` decimal(10,3) NOT NULL,
  `coefficient_salary` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `company_calculator_settings`
--

INSERT INTO `company_calculator_settings` (`id`, `coefficient_income_vat`, `coefficient_consumption_vat`, `coefficient_consumption_not_vat`, `coefficient_consumption_cash`, `coefficient_salary`) VALUES
(1, '0.900', '0.800', '0.700', '0.600', '0.500');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `company_calculator_settings`
--
ALTER TABLE `company_calculator_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `company_calculator_settings`
--
ALTER TABLE `company_calculator_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
