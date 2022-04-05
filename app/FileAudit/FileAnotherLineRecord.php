<?php
namespace FileAudit;

class FileAnotherLineRecord implements FileRecordable {

    public function __construct(protected int $cnt, protected string $data, protected string $date)
    {
    }

    public function createRecord(): string {
        return $this->cnt . ';' . $this->data . ';' . $this->date;
    }
}