<?php

namespace App\Admin\View;

use App\View\View;
class AdminView extends View
{
    private string $directory = "/admin/View/";

    public function page(string $pageName){
        $this->view($pageName,$this->directory);
    }
}