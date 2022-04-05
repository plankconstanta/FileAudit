<?php
namespace FileAudit;

interface FileRecordable {
    public function createRecord(): string;
}