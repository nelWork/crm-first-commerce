<?php

namespace App\User\Contoller;

abstract class Controller extends \App\Controller\Controller
{
    private string $directory = "/user/View/";
    public function view(string $pageName, string $viewDirectory = ''){
        $this->view->page($pageName, $this->directory);
    }


    public function isActive($paths, bool $strict = false): string
    {
        $current = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        foreach ((array)$paths as $path) {
            $path = rtrim($path, '/');

            if (
                ($strict && $current === $path) ||
                (!$strict && str_starts_with($current, $path))
            ) {
                return 'active';
            }
        }

        return '';
    }




}