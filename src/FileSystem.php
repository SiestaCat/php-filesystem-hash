<?php

namespace Siestacat\PhpFilesystemHash;
use League\Flysystem\FilesystemOperator;
use Siestacat\PhpHashPath\GenHashPath;

/**
 * @package Siestacat\PhpFilesystemHash
 */
class FileSystem
{
    const DIR_SUFIX = 'files';

    const UPLOAD_HASH_ALGO = 'sha512';

    private ?GenHashPath $genHashPath = null;

    public function __construct(private FilesystemOperator $defaultStorage)
    {
        $this->genHashPath = new GenHashPath;
    }

    public function exists(string $hash, ?string $dir_sufix = self::DIR_SUFIX):bool
    {
        return $this->defaultStorage->fileExists($this->getRelPathFromHash($hash, $dir_sufix));
    }

    public function getContents(string $hash, ?string $dir_sufix = self::DIR_SUFIX):string
    {
        return $this->defaultStorage->read($this->getRelPathFromHash($hash, $dir_sufix));
    }

    /**
     * @return resource
     */
    public function getStream(string $hash, ?string $dir_sufix = self::DIR_SUFIX)
    {
        return $this->defaultStorage->readStream($this->getRelPathFromHash($hash, $dir_sufix));
    }

    public function uploadFile(string $local_file_path, ?string $dir_sufix = self::DIR_SUFIX, string $hash_algo = self::UPLOAD_HASH_ALGO):string
    {

        $hash = hash_file($hash_algo, $local_file_path);

        $this->defaultStorage->writeStream($this->getRelPathFromHash($hash, $dir_sufix), fopen($local_file_path, 'r'));

        return $hash;
    }

    public function delete(string $hash, ?string $dir_sufix = self::DIR_SUFIX):bool
    {
        $this->defaultStorage->delete($this->getRelPathFromHash($hash, $dir_sufix));

        return !$this->exists($hash, $dir_sufix);
    }  

    private function getRelPathFromHash(string $hash, ?string $dir_sufix = self::DIR_SUFIX):string
    {
        return ($dir_sufix === null ? null : $dir_sufix . DIRECTORY_SEPARATOR) . $this->genHashPath->getPathFileSystem($hash);
    }
}