<?php
namespace FileAudit;

class FileAudit implements FileAuditable {

    const RECORD_SEPARATOR = PHP_EOL;
    const LOCK_DIR = 'directoryaudit';

    protected $recordCounter;
    protected $fileIndex = 0;

    public function __construct(protected int $maxRecordInFile, protected DirectoryAuditable $directoryManager)
    {
        $this->fileIndex = $this->directoryManager->getLastFileIndex();
        $filename = $this->directoryManager->getFullFileNameByIndex($this->fileIndex);
        $this->recordCounter = $this->getCountRecordFromFile($filename);
    }

    public function getFilename():string
    {
        $filename = $this->directoryManager->getFullFileNameByIndex($this->fileIndex);
        return $filename;
    }

    public function addRecord(string $record):void
    {
        try {
            $locked = 0;
            while (!$locked) {
                if (@mkdir(self::LOCK_DIR, 0777)) {
                    $locked = 1;
                } else {
                    sleep(1);
                }
            }

            if ($this->recordCounter >= $this->maxRecordInFile) {
                $this->fileIndex++;
                $this->recordCounter = 0;
            }
            $filename = $this->directoryManager->getFullFileNameByIndex($this->fileIndex);
            if ($this->recordCounter) {
                $record = self::RECORD_SEPARATOR . $record;
            }
            $this->recordCounter++;
            $this->addRecordToEndFile($filename, $record);
            rmdir(self::LOCK_DIR);

        } catch (\Exception $e) {
            @rmdir(self::LOCK_DIR);
            throw new \FileAuditException($e->getMessage());
        }
    }

    public static function getCountRecordFromContent(string $content):int
    {
        if (empty($content)) {
            return 0;
        }
        $tmp = explode(self::RECORD_SEPARATOR, $content);
        $cnt = count($tmp);
        return $cnt;
    }

    public static function getCountRecordFromFile(string $filename):int
    {
        // TODO
        return self::getCountRecordFromContent(file_get_contents($filename));
    }

    public function addRecordToEndFile(string $fileName, string $content):void
    {
        if (!($f = fopen($fileName, 'a'))) {
            throw new \FileAuditException('File ' . $fileName . ' could not be opened');
        }

        if (flock($f, LOCK_EX)) {
            fwrite($f, $content);
            fflush($f);
            flock($f, LOCK_UN);
        } else {
            throw new \FileAuditException('File ' . $fileName . ' could not be written');
        }

        if (!fclose($f)) {
            throw new \FileAuditException('File ' . $fileName . ' could not be closed');
        }
    }

}
?>