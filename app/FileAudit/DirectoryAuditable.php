<?php
namespace FileAudit;

interface DirectoryAuditable {
    public function getListFileNames();
    public function getLastFileIndex():int;
    public function getFullFileNameByIndex(int $index):string;
}