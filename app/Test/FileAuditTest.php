<?php

use PHPUnit\Framework\TestCase;
use FileAudit\FileAudit;
use FileAudit\DirectoryAudit;
use FileAudit\FileLineRecord;

class FileAuditTest extends TestCase
{
    public function test_Add_record()
    {
        $date = date('Y-m-d');
        $directoryName = 'test/test_add';
        $fileNameTemplate = 'testmy%my.txt';
        $maxRecordInFile = 2;
        $directoryManager = new DirectoryAudit($directoryName, $fileNameTemplate);


        $content0 = new FileLineRecord('data1', $date);
        $record0 = $content0->createRecord();
        $content1 = new FileLineRecord('data1', $date);
        $record1 = $content1->createRecord();

        file_put_contents($directoryName.'/testmy0my.txt', $record0);

        $sut = new FileAudit($maxRecordInFile,  $directoryManager);
        $sut->addRecord($record1);

        $this->assertSame(file_get_contents($directoryName.'/testmy0my.txt'), $record0.FileAudit::RECORD_SEPARATOR.$record1);

        file_put_contents($directoryName.'/testmy1my.txt', '');
        $sut->addRecord($record1);
        $this->assertSame(file_get_contents($directoryName.'/testmy1my.txt'), $record1);
        @unlink($directoryName.'/testmy1my.txt');
    }
}