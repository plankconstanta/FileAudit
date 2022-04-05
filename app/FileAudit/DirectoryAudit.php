<?php
namespace FileAudit;

class DirectoryAudit implements DirectoryAuditable {

    const TEMPLATE_SIGN = '%';

    protected $listFileNames = [];

    public function __construct(protected string $directoryName, protected string $fileNameTemplate)
    {
        $this->listFileNames = $this->updateListFileNames();
    }

    public function getDirectoryName(): string
    {
        return $this->directoryName;
    }

    public function getListFileNames()
    {
        return $this->listFileNames;
    }

    protected function updateListFileNames()
    {
        if (!file_exists($this->directoryName) || !is_dir($this->directoryName)) {
            throw new DirectoryAuditException('Directory ' . $this->directoryName . ' does not exist');
        }
        $list = [];
        $unused = [$this->directoryName, DIRECTORY_SEPARATOR];
        foreach(glob(str_replace(self::TEMPLATE_SIGN, '*', $this->directoryName .DIRECTORY_SEPARATOR. $this->fileNameTemplate)) as $filename) {
            $list[] = trim(str_replace($unused,'', $filename));
        }
        $this->listFileNames = $list;
        return $this->listFileNames;
    }

    public function getFullFileNameByIndex(int $index): string
    {
        $filename = $this->directoryName . DIRECTORY_SEPARATOR. str_replace(self::TEMPLATE_SIGN, $index, $this->fileNameTemplate);
        return $filename;
    }

    public function getLastFileIndex():int
    {
        $parts = explode(self::TEMPLATE_SIGN, $this->fileNameTemplate);
        $max = 0;
        foreach($this->listFileNames as $item) {
            $index = intval(str_replace($parts, '', $item));
            $max = $index > $max ? $index : $max;
        }
        return $max;
    }
}