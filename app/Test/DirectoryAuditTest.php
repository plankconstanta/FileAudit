<?php

use PHPUnit\Framework\TestCase;
use FileAudit\DirectoryAudit;
use FileAudit\DirectoryAuditException;

class DirectoryAuditTest extends TestCase
{
    public function test_List_directory()
    {
        $dirname = 'test/test';
        $list = scandir($dirname);

        $sut = new DirectoryAudit($dirname, 'test%.txt');
        $this->assertSame(count($list) - 2, count($sut->getListFileNames()));
    }

    public function test_Get_last_file_index()
    {
        $templ = 'test%.txt';
        $sut = new DirectoryAudit('test/test_index', $templ);
        $this->assertSame(21, $sut->getLastFileIndex());
    }

    public function test_Get_0index_file_in_empty_dir()
    {
        $directoryName = 'test_empty';
        $fileNameTemplate = 'test%.txt';
        $sut = new DirectoryAudit($directoryName, $fileNameTemplate);
        $this->assertSame(0, $sut->getLastFileIndex());
    }
}