<?php
namespace FileAudit;

class FileAudit implements FileAuditable {
    const TEMPLATE_SIGN = '%';

    //protected string $fileNameTemplate;
    //protected int $maxRecordInFile;
    //protected DirectoryAuditable $directoryManager;
    //protected FileRecordable $contentMaker;

    public function __construct(protected string $fileNameTemplate, protected int $maxRecordInFile, protected DirectoryAuditable $directoryManager, protected FileRecordable $contentMaker)
    {
        //$this->fileNameTemplate = $fileNameTemplate;
        //$this->maxRecordInFile = $maxRecordInFile;
        //$this->directoryManager = $directoryManager;
        //$this->contentMaker = $contentMaker;
    }

    public function addRecord():void
    {
        $filename = $this->getCurrentFileOrCreate();

        $content = $this->directoryManager->getFileContent($filename);
        $content = $this->contentMaker->addRecord($content);

        $this->directoryManager->saveFileContent($filename, $content);
    }

    public function getCurrentFileOrCreate():string {
        $index = 0;
        $list = $this->directoryManager->getListFileNames($this->directoryManager->getDirectoryName(), $this->fileNameTemplate);

        if (empty($list)) {
            $filename = $this->getFullFileName($this->directoryManager->getDirectoryName(), $index, $this->fileNameTemplate);
            $this->directoryManager->createFile($filename);
        } else {
            $index = $this->getLastFileIndex($list, $this->fileNameTemplate);
            $filename = $this->getFullFileName($this->directoryManager->getDirectoryName(), $index, $this->fileNameTemplate);
            $content = $this->directoryManager->getFileContent($filename);
            if ($this->contentMaker->getCountRecord($content) >= $this->maxRecordInFile) {
                $index++;
                $filename = $this->getFullFileName($this->directoryManager->getDirectoryName(), $index, $this->fileNameTemplate);
                $this->directoryManager->createFile($filename);
            }
        }

        return $filename;
    }

    public function getLastFileIndex($list, string $fileNameTemplate):int {
        $parts = explode(self::TEMPLATE_SIGN, $fileNameTemplate);
        $max = 0;
        foreach($list as $item) {
            $index = intval(str_replace($parts, '', $item));
            $max = $index > $max ? $index : $max;
        }

        return $max;
    }

    public function getFullFileName(string $directoryName, int $index, string $fileNameTemplate): string {
        return  $directoryName . DIRECTORY_SEPARATOR. str_replace(self::TEMPLATE_SIGN, $index, $fileNameTemplate);
    }
}
?>