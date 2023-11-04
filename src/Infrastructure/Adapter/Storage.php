<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Application\StorageInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Storage implements StorageInterface
{
    public function __construct(
        private readonly FilesystemOperator $storage,
    ) {
    }

    public function write(string $identifier, UploadedFile $file): string
    {
        $path = sprintf('%s.%s', $identifier, $file->getClientOriginalExtension());
        $this->storage->write($path, $file->getContent(), ['visibility' => 'public']);

        return $path;
    }

    public function delete(string $identifier): void
    {
        if (!$this->storage->has($identifier)) {
            return;
        }

        $this->storage->delete($identifier);
    }
}
