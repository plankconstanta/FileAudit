<?php
namespace FileAudit;

class FileLineRecord implements FileRecordable {
    const RECORD_SEPARATOR = "\r\n";

    //protected string $data;
    //protected string $date;

    public function __construct(protected string $data, protected string $date)
    {
        //$this->data = $data;
        //$this->date = $date;
    }

    public static function getCountRecord(string $content): int {
        if (empty($content)) {
            return 0;
        }
        $tmp = explode(self::RECORD_SEPARATOR, $content);
        $cnt = count($tmp);
        return $cnt;
    }

    public function createRecord(): string {
        return $this->data . ';' . $this->date;
    }

    public function addRecord(string $content): string {
        $record = $this->createRecord();
        $content = $content ? $content . self::RECORD_SEPARATOR . $record : $record;
        return $content;
    }
}