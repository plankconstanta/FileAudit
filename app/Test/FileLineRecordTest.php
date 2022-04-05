<?php

use PHPUnit\Framework\TestCase;
use FileAudit\FileLineRecord;

class FileLineRecordTest extends TestCase
{
    public function testCreateRecord()
    {
        $data = 'data';
        $date = date('Y-m-d');
        $sut = new FileLineRecord($data, $date);
        $this->assertSame($data . ';' . $date, $sut->createRecord());
    }
}
