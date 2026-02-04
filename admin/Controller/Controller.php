<?php

namespace App\Admin\Controller;

abstract class Controller extends \App\Controller\Controller
{
    private string $directory = "/admin/View/";
    public function view(string $pageName, string $viewDirectory = ''){
        $this->view->page($pageName, $this->directory);
    }
}