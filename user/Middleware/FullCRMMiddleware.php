<?php

namespace App\User\Middleware;

use App\Middleware\AbstractMiddleware;

class FullCRMMiddleware extends AbstractMiddleware
{

    public function handle(): void
    {
        if(! $this->auth->check())
            $this->redirect->to('/login');


        $user = $this->auth->user();

        if(! $user->fullCRM())
            $this->redirect->to('/applications-list');
    }
}