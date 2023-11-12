<?php

declare(strict_types=1);


namespace  Plutuss\SauceCore\Upload;

readonly class UploadedFile implements UploadedFileInterface
{

    public function __construct(
        public string $name,
        public string $type,
        public string $tmpName,
        public int    $error,
        public int    $size,
    ) {
    }

    public function move(string $path, string $fileName = null): string|false
    {
        $storagePath = APP_DIR . "/storage/$path";

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $fileName = $fileName ?? $this->randomFileName();

        $filePath = "$storagePath/$fileName";

        if (move_uploaded_file($this->tmpName, $filePath)) {
            return "$path/$fileName";
        }

        return false;
    }

    private function randomFileName(): string
    {
        return md5(uniqid((string)rand(), true)) . ".{$this->getExtension()}";
    }


    public function getExtension(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function hasError(): bool
    {
        return $this->error !== UPLOAD_ERR_OK;
    }
}