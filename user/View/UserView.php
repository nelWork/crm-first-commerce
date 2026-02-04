<?php

namespace App\User\View;

use App\View\View;
class UserView extends View
{
    private string $directory = "/user/View/";

    public function page(string $pageName){
        $this->view($pageName,$this->directory);
    }
}