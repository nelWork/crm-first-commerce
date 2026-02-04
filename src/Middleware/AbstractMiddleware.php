<?php

namespace App\Middleware;

use App\Auth\AuthInterface;
use App\Http\RedirectInterface;
use App\Http\RequestInterface;

abstract class AbstractMiddleware
{
    public function __construct(
        protected RequestInterface $request,
        protected AuthInterface $auth,
        protected RedirectInterface $redirect
    ) {

    }

    abstract public function handle(): void;
}