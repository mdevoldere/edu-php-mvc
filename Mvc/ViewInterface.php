<?php 

namespace Md\Mvc;

interface ViewInterface 
{
    public function setFile(string $_filename): ViewInterface;

    public function fetch(array $_data): string;

    public function setChild(string $key, string $_filename): ViewInterface;
}