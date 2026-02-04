<?php

namespace App\Http;

use App\Upload\Upload;
use App\Upload\UploadInterface;
use App\Validator\ValidatorInterface;

class Request implements \App\Http\RequestInterface
{
    private ValidatorInterface $validator;

    public function __construct(
        public readonly array $get,
        public readonly array $post,
        public readonly array $server,
        public readonly array $files,
        public readonly array $cookie
    )
    {
    }

    public static function createFromGlobals(): static{
        return new static($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE);
    }

    public function uri(): string{
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function method(): string{
        return $this->server['REQUEST_METHOD'];
    }

    public function file(string $name):?UploadInterface{
        if (!isset($this->files[$name])) {
            return null;
        }

        return new Upload(
            $this->files[$name]['name'],
            $this->files[$name]['type'],
            $this->files[$name]['tmp_name'],
            $this->files[$name]['size'],
            $this->files[$name]['error'],
        );
    }

    public function requestUri(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function isGET(): bool
    {
        return count($this->get) > 0;
    }

    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function validate(array $rules): bool
    {
        $data = [];

        foreach ($rules as $field => $rule) {
            $data[$field] = $this->input($field);
        }

        return $this->validator->validate($data, $rules);
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }
}