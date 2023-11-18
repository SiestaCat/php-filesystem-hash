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

        $this->clearDir();

        $hash = $this->doUpload();

        $this->assertEquals(true, $this->getFileSystemInstance()->exists($hash));

        $this->assertEquals(true, $this->getFileSystemInstance()->delete($hash));

        $this->clearDir();
    }

    public function test_get_contents()
    {

        $this->clearDir();

        $hash = $this->doUpload();

        $contents = $this->getFileSystemInstance()->getContents($hash);

        $this->assertEquals(true, $this->getFileSystemInstance()->delete($hash));

        $this->clearDir();
    }

    private function getFileSystemInstance():FileSystem
    {
        if($this->fileSystem === null) $this->fileSystem = new FileSystem(new \League\Flysystem\Filesystem(new LocalFilesystemAdapter($this->data_dir)));

        return $this->fileSystem;
    }

    private function doUpload():string
    {
        $tmp_path = tempnam(sys_get_temp_dir(), self::genRandomHash());

        file_put_contents($tmp_path, random_bytes(rand(1000000,10000000))); //Put random bytes to file

        return $this->getFileSystemInstance()->uploadFile($tmp_path); //return file hash
    }

    private static function genRandomHash():string
    {
        return hash('md5', random_bytes(32));
    }

    private function clearDir():void
    {

    }
}