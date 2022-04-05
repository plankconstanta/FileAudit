<?php
require_once __DIR__ . '/vendor/autoload.php';

use FileAudit\FileLineRecord;
use FileAudit\DirectoryAudit;
use FileAudit\FileAudit;



$directory = new DirectoryAudit('test', 'test%.txt');
$record1 = new FileLineRecord('test', date('Y-m-d H:i:s'));
$record2 = new FileAnotherLineRecord(1, 'test', date('Y-m-d H:i:s'));
$audit = new FileAudit(3, $directory);
$audit->addRecord($record1->createRecord());
$audit->addRecord($record2->createRecord());