<?php

namespace App\Container;

use App\Auth\Auth;
use App\Auth\AuthInterface;
use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Http\Redirect;
use App\Http\RedirectInterface;
use App\Http\Request;
use App\Mailer\Mailer;
use App\Mailer\MailerInterface;
use App\Router\Router;
use App\Storage\Storage;
use App\Storage\StorageInterface;
use App\Session\Session;
use App\Session\SessionInterface;
use App\Validator\Validator;
use App\Validator\ValidatorInterface;
use App\View\View;



class Container
{
    public readonly Request $request;

    public readonly Router $router;

    public readonly RedirectInterface $redirect;

    public readonly View $view;

    public readonly ConfigInterface $config;

    public readonly DatabaseInterface $database;

    public readonly StorageInterface $storage;

    public readonly AuthInterface $auth;

    public readonly SessionInterface $session;

    public readonly MailerInterface $mailer;

    public readonly ValidatorInterface $validator;

    public function __construct()
    {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->view = new View();

        $this->redirect = new Redirect();

        $this->request = Request::createFromGlobals();

        $this->config =  new Config();

        $this->session = new Session();

        $this->database = new Database($this->config);

        $this->validator = new Validator();

        $this->validator->setDatabase($this->database);

        $this->request->setValidator($this->validator);

        $this->storage = new Storage($this->config);

        $this->auth = new Auth($this->database, $this->session ,$this->config);

        $this->mailer = new Mailer($this->config);



        $this->router = new Router(
            $this->view,
            $this->request,
            $this->storage,
            $this->redirect,
            $this->session,
            $this->database,
            $this->auth,
            $this->validator,
            $this->mailer
        );
    }
}