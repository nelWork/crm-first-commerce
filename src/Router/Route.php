<?php

namespace App\Router;

/**
 * Класс, представляющий маршрут в роутере.
 *
 * Этот класс используется для хранения информации о маршруте, включая URI, HTTP метод (GET/POST),
 * действие, которое должно быть выполнено, и промежуточные слои (middlewares), если они предусмотрены.
 */
class Route
{
    /**
     * Конструктор класса Route.
     *
     * Инициализирует объект маршрута с URI, HTTP методом, действием и опциональными промежуточными слоями.
     *
     * @param string $uri URI маршрута.
     * @param string $method HTTP метод для маршрута (например, 'GET', 'POST').
     * @param mixed $action Действие, которое должно быть выполнено для этого маршрута (например, функция или контроллер).
     * @param array $middlewares Промежуточные слои, которые будут применяться к маршруту.
     */
    public function __construct(
        private string $uri,
        private string $method,
        private $action,
        private array $middlewares = []
    ) {

    }

    /**
     * Статический метод для создания маршрута с методом GET.
     *
     * @param string $uri URI маршрута.
     * @param mixed $action Действие, которое должно быть выполнено для маршрута.
     * @param array $middlewares Промежуточные слои (опционально).
     * @return static Новый объект маршрута с методом GET.
     */
    public static function get(string $uri, $action, array $middlewares = []): static
    {
        return new static($uri, 'GET', $action, $middlewares);
    }

    /**
     * Статический метод для создания маршрута с методом POST.
     *
     * @param string $uri URI маршрута.
     * @param mixed $action Действие, которое должно быть выполнено для маршрута.
     * @param array $middlewares Промежуточные слои (опционально).
     * @return static Новый объект маршрута с методом POST.
     */
    public static function post(string $uri, $action, array $middlewares = []): static
    {
        return new static($uri, 'POST', $action, $middlewares);
    }

    /**
     * Возвращает URI маршрута.
     *
     * @return string URI маршрута.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Возвращает действие, которое должно быть выполнено для маршрута.
     *
     * @return mixed Действие, ассоциированное с маршрутом (например, функция или контроллер).
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Возвращает HTTP метод, ассоциированный с маршрутом (например, 'GET' или 'POST').
     *
     * @return string HTTP метод маршрута.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Проверяет, есть ли у маршрута промежуточные слои.
     *
     * @return bool Возвращает true, если у маршрута есть промежуточные слои, иначе false.
     */
    public function hasMiddlewares(): bool
    {
        return !empty($this->middlewares);
    }

    /**
     * Возвращает массив промежуточных слоев, ассоциированных с маршрутом.
     *
     * @return array Массив промежуточных слоев.
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
