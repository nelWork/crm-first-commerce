<?php

namespace App\Controller;

use App\Auth\AuthInterface;
use App\Database\DatabaseInterface;
use App\Http\RedirectInterface;
use App\Http\Request;
use App\Mailer\MailerInterface;
use App\Session\SessionInterface;
use App\Storage\StorageInterface;
use App\Validator\ValidatorInterface;
use App\View\View;

abstract class Controller
{
    public View $view;

    public Request $request;

    public AuthInterface $auth;

    public StorageInterface $storage;

    public SessionInterface $session;

    public RedirectInterface $redirect;

    public DatabaseInterface $database;

    public MailerInterface $mailer;

    public ValidatorInterface $validator;


    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    public function setMailer(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function setDatabase(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function setRedirect(RedirectInterface $redirect)
    {
        $this->redirect = $redirect;
    }

    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setAuth(AuthInterface $auth)
    {
        $this->auth = $auth;
    }
    public function view(string $pageName, string $viewDirectory=''){
        $this->view->page($pageName, $viewDirectory);
    }

    public function setView(View $view){
        $this->view = $view;
    }

    public function setRequest(Request $request){
        $this->request = $request;
    }

    public function setStorage(StorageInterface $storage){
        $this->storage = $storage;
    }

    public function extract(array $extractedData)
    {
        $this->view->addExtractList($extractedData);
    }

    public function format_date($date, $format)
    {
        $intDate = strtotime($date);
        return date($format, $intDate);
    }
}