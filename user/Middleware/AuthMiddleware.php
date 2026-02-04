<?php

namespace App\User\Middleware;

use App\Middleware\AbstractMiddleware;

class AuthMiddleware extends AbstractMiddleware
{

    public function handle(): void
    {
        // TODO: Implement handle() method.
        if(! $this->auth->check()){
            $this->redirect->to('/login');

        }
    }
}