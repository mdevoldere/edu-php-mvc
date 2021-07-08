<?php 

namespace Md\Db;


interface IRepositoryReader
{    
    public function maxId(): mixed;

    public function getFirst(): array;

    public function getLatest(): array;

    public function getRandom(): array;
}