<?php
namespace FileAudit;

interface FileAuditable {
    public function addRecord(string $record);
}