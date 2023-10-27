<?php

declare(strict_types=1);

namespace App\Application;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface StorageInterface
{
    public function write(string $identifier, UploadedFile $file): string;

    public function delete(string $identifier): void;
}
