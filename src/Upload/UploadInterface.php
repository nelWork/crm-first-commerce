<?php

namespace App\Upload;

interface UploadInterface
{
    public function upload(string $newPath): string|false;

    public function getExtension(): string;
}