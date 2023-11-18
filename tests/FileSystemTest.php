<?php

namespace Siestacat\Phpfilemanager\Tests\File;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Siestacat\PhpFilesystemHash\FileSystem;

class FileSystemTest extends TestCase
{

    private ?FileSystem $fileSystem = null;

    private string $data_dir = __DIR__.'/.data/';

    /**
     * uploadFile() and exists() methods tested here
     */
    public function test_upload()
    {
        $hash = $this->doUpload();

        $this->assertEquals(true, $this->getFileSystemInstance()->exists($hash));

        $this->assertEquals(true, $this->getFileSystemInstance()->delete($hash));
    }

    public function test_get_contents()
    {
        $hash = $this->doUpload();

        $contents = $this->getFileSystemInstance()->getContents($hash);

        $this->assertEquals
        (
            $hash,
            hash_file(FileSystem::UPLOAD_HASH_ALGO, $this->createTmpFile($contents))
        );

        $this->assertEquals(true, $this->getFileSystemInstance()->delete($hash));
    }

    public function test_get_stream()
    {
        $hash = $this->doUpload();

        $stream = $this->getFileSystemInstance()->getStream($hash);

        $this->assertEquals
        (
            $hash,
            hash_file(FileSystem::UPLOAD_HASH_ALGO, $this->createTmpFile(stream_get_contents($stream)))
        );

        $this->assertEquals(true, $this->getFileSystemInstance()->delete($hash));
    }

    private function getFileSystemInstance():FileSystem
    {
        if($this->fileSystem === null) $this->fileSystem = new FileSystem(new \League\Flysystem\Filesystem(new LocalFilesystemAdapter($this->data_dir)));

        return $this->fileSystem;
    }

    private function doUpload():string //return file hash
    {
        return
        $this->getFileSystemInstance()->uploadFile
        (
            $this->createTmpFile(random_bytes(rand(1000000,10000000))) //Put random bytes to tmp file
        );
    }

    private function createTmpFile(string $contents):string
    {
        $tmp_path = tempnam(sys_get_temp_dir(), self::genRandomHash());

        file_put_contents($tmp_path, $contents);

        return $tmp_path;
    }

    private static function genRandomHash():string
    {
        return hash('md5', random_bytes(32));
    }
}