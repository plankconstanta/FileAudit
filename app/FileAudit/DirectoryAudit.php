<?php
namespace FileAudit;

class DirectoryAudit implements DirectoryAuditable {

    //protected string $directoryName;

    public function __construct(protected string $directoryName)
    {
        //$this->directoryName = $directoryName;
    }

    public function getDirectoryName(): string
    {
        return $this->directoryName;
    }

    public function getListFileNames()
    {
        if (!is_dir($this->directoryName)) {
            mkdir($this->directoryName);
            //throw new \Exception('Directory ' . $directoryName . ' does not exist');
        }
        $list = scandir($this->directoryName, SCANDIR_SORT_ASCENDING);
        $except = ['.', '..'];
        foreach($list as $key=>$name) {
            if (in_array($name, $except)) {
                unset($list[$key]);
            }
        }
        return $list;
    }

    public function createFile(string $fileName): void {
        file_put_contents($fileName, '');
    }

    public function getFileContent(string $fileName):string {
        return file_get_contents($fileName);
    }

    public function saveFileContent(string $fileName, string $content): void {
        file_put_contents($fileName, $content);
    }
}