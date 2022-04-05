<?php
namespace FileAudit;

class FileLineRecord implements FileRecordable {

    public function __construct(protected string $data, protected string $date)
    {}

    public function createRecord(): string {
        return $this->data . ';' . $this->date;
    }
}