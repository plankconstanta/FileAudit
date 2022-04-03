<?php
namespace FileAudit;

class FileTwoLineRecord implements FileRecordable {
    const RECORD_SEPARATOR = "\r\n\r\n";

    //protected string $data;
    //protected string $date;
    //protected int $cnt;

    public function __construct(protected int $cnt, protected string $data, protected string $date)
    {
        //$this->cnt = $cnt;
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
        return $this->cnt . ';' . $this->data . ';' . $this->date;
    }

    public function addRecord(string $content): string {
        $record = $this->createRecord();
        $content = $content ? $content . self::RECORD_SEPARATOR . $record : $record;
        return $content;
    }
}