<?php

namespace App\User\Contoller\Common;

use App\Model\Client\Client;
use App\User\Contoller\Controller;
use App\User\View\UserView;
use App\User\Model\User\UserList;

class HomeController extends Controller
{

    public function __construct()
    {
    }

    public function map(){
        $this->extract([
            'controller' => $this
        ]);

        $this->view('Map/index');
    }

    public function apiMainSiteUploadForm()
    {
        $file = $this->request->file('json');
        $fileContent = file_get_contents($file->tmpName);

        $content = json_decode($fileContent, true);
        $form = $content['fields'];

        if($this->database->insert('main_site_form_feedback', [
            'name' => $form['NAME'],
            'phone' => $form['PHONE'],
            'email' => $form['EMAIL'],
            'service' => $form['SERVICE'],
            'message' => $form['MESSAGE'],
            'datetime' => $content['datetime'],
        ])){
            http_response_code(200);
        }
//
//        dd($content);
//
//        dd($this->request->file('json'));


    }

    public function apiMainSite(){
        $this->extract([
            'controller' => $this
        ]);

        $this->view('Common/api-test');
    }

    public function index()
    {
        $this->extract([
            'controller' => $this
        ]);
        $this->view("Common/home");

        $client = new Client(2);

        dump($client->get(['name', 'inn']));

        $client->save();
    }

    public function home()
    {
        $this->extract([
            'controller' => $this
        ]);

        $this->view("Common/testhome");

    }
  
    public function login(){
        $this->view('Common/login');
    }

    public function ajaxChangeCountElementPage()
    {
        $count = $this->request->input('count') ?? 25;

        $this->session->set('countElementsPage', $count);
    }



}