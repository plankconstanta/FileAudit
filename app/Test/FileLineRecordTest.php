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

    public function testAddRecord()
    {
        $data = 'data';
        $date = date('Y-m-d');
        $sut = new FileLineRecord($data, $date);

        $rec1 = new FileLineRecord('add 1', $date);
        $rec2 = new FileLineRecord('add 2', $date);

        $content = $sut->createRecord();
        $content = $rec1->addRecord($content);
        $content = $rec2->addRecord($content);

        $this->assertSame($content, 'data;'.$date . FileLineRecord::RECORD_SEPARATOR . 'add 1;'.$date . FileLineRecord::RECORD_SEPARATOR . 'add 2;'.$date);
    }

    public function testCountRecord()
    {
        $data = 'data';
        $date = date('Y-m-d');

        $sut = new FileLineRecord($data, $date);
        $rec1 = new FileLineRecord($data, $date);
        $rec2 = new FileLineRecord($data, $date);

        $content = $sut->addRecord('');
        $content = $rec1->addRecord($content);
        $content = $rec2->addRecord($content);

        $this->assertSame(3, $sut->getCountRecord($content));
    }
}
