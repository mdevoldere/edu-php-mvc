<?php 

namespace MD\Db;


class RepositoryReader extends Repository implements IrepositoryReader
{
    public function maxId()
    {
        return $this->dbContext::query(("SELECT MAX(" .$this->pk . ") as nb FROM " . $this->table . ";"), false)['nb'];
    }
    
    public function getFirst(): array
    {
        return $this->dbContext::query("SELECT * FROM " . $this->table . " ORDER BY " . $this->pk . " ASC LIMIT 1;");
    }

    public function getLatest(): array
    {
        return $this->dbContext::query("SELECT * FROM " . $this->table . " ORDER BY " . $this->pk . " DESC LIMIT 1;");
    }

    public function getRandom(): array
    {
        return $this->dbContext::query("SELECT * FROM " . $this->table . " ORDER BY RAND() ASC LIMIT 1;");
    }
}