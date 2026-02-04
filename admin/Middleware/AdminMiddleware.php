<?php

namespace App\Admin\Middleware;

use App\Middleware\AbstractMiddleware;

class AdminMiddleware extends AbstractMiddleware
{

    public function handle(): void
    {
        // TODO: Implement handle() method.
        if(! $this->auth->check())
            $this->redirect->to('/login');


        $user = $this->auth->user();

        if(! $user->admin())
            $this->redirect->to('/applications-list');
    }
}