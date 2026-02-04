<?php

namespace App\Router;

use App\Database\DatabaseInterface;
use App\Http\RedirectInterface;
use App\Mailer\MailerInterface;
use App\Session\SessionInterface;
use App\Storage\StorageInterface;
use App\Validator\ValidatorInterface;
use App\View\View;
use App\Http\RequestInterface;
use App\Auth\AuthInterface;

/**
 * Описание класса Router
 * Класс нужен для маршрутизации сайта (по какому адресу какой контроллер и его функцию применять)
 */
class Router
{

    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /** Функция для поиска в файле routes.php в папке configs адреса страницы метода запроса и обработчика
     * @param string $url адрес страницы
     * @param string $method метода запрос (GET либо POST)
     * @return void
     */
    public function route(string $url, string $method){
        $route = $this->findRoute($url, $method);

        if (! $route) {
            $this->notFound();
        }

        if ($route->hasMiddlewares()) {
            foreach ($route->getMiddlewares() as $middleware) {
                /** @var AbstractMiddleware $middleware */
                $middleware = new $middleware($this->request, $this->auth, $this->redirect);

                $middleware->handle();
            }
        }

        if (is_array($route->getAction())){

            [$controller, $action] = $route->getAction();

            $controller = new $controller();

            call_user_func([$controller, 'setView'],$this->view);

            call_user_func([$controller, 'setRequest'],$this->request);

            call_user_func([$controller, 'setStorage'],$this->storage);

            call_user_func([$controller, 'setAuth'], $this->auth);

            call_user_func([$controller, 'setSession'], $this->session);

            call_user_func([$controller, 'setRedirect'], $this->redirect);

            call_user_func([$controller, 'setDatabase'], $this->database);

            call_user_func([$controller, 'setMailer'], $this->mailer);

            call_user_func([$controller, 'setValidator'], $this->validator);

            call_user_func([$controller, $action]);
        }
        else{
            call_user_func($route->getAction());
        }
//        return $route;
    }

    /**
     * @param string $uri
     * @param string $method
     * @return Route|false
     */
    private function findRoute(string $uri, string $method): Route|false
    {
        if (! isset($this->routes[$method][$uri])) {
            return false;
        }

        return $this->routes[$method][$uri];
    }

    public function __construct(
        private View $view,
        private RequestInterface $request,
        private StorageInterface $storage,
        private RedirectInterface $redirect,
        private SessionInterface $session,
        private DatabaseInterface $database,
        private AuthInterface $auth,
        private ValidatorInterface $validator,
//        private CaptchaInterface $captcha,
        private MailerInterface $mailer

    ) {
        $this->initRoutes();
    }

    /** Функция инициализации путей
     * @return void
     */
    private function initRoutes()
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route) {

            $this->routes[$route->getMethod()][$route->getUri()] = $route;
        }
    }

    /** Функция для получения всех путей в файле routes.php в папке configs
     * @return mixed
     */
    private function getRoutes()
    {
        return  require APP_PATH. "/configs/routes.php";
    }

    /** Функция если пользователь перешел по адресу которого нет
     * @return void
     */
    private function notFound()
    {
//        echo "404";
//        exit();
        $this->redirect->to('/');
    }
}