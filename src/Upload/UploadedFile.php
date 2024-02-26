<?php

declare(strict_types=1);


namespace Plutuss\CodeMaster\Upload;

class UploadedFile implements UploadedFileInterface
{

    public function __construct(
        public string $name,
        public string $type,
        public string $tmpName,
        public int    $error,
        public int    $size,
    )
    {
    }

    /**
     * @param string $path
     * @param string|null $fileName
     * @return string|false
     */
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

    /**
     * @return string
     */
    private function randomFileName(): string
    {
        return md5(uniqid((string)rand(), true)) . ".{$this->getExtension()}";
    }


    /**
     * @return string
     */
    public function getExtension(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error !== UPLOAD_ERR_OK;
    }
}