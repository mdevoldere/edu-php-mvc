<?php 

namespace MD\Db;


class Repository implements Irepository
{

    public string $table;

    public string $pk;

    protected string $dbContext;

    public function __construct(string $_table, string $_pk, string $_dbContext = '\\MD\\Db\\DbContext')
    {
        $this->table = $_table;
        $this->pk = $_pk;
        $this->dbContext = $_dbContext;
    }

    public function exists($_id): bool
    {
        return $this->dbContext::fetch("SELECT COUNT(*) as nb FROM " . $this->table . " WHERE " . $this->pk . "=:cond;", [':cond' => $_id], false)['nb'] > 0;
    }

    public function count(): int
    {
        return $this->dbContext::query(("SELECT COUNT(*) as nb FROM " . $this->table . ";"), false)['nb'];
    }

    public function getAll(): array
    {
        return $this->dbContext::query(("SELECT * FROM " . $this->table . ";"), true);
    }

    public function getBy(string $_col, string $_value, bool $_all = false) : array
    {
        return $this->dbContext::fetch("SELECT * FROM " . $this->table . " WHERE " . \basename($_col) . "=:cond;", [':cond' => $_value], $_all);
    }

    public function getById($_id): array
    {
        return $this->getBy($this->pk, $_id, false);
    }


    public function validate(array &$_input): bool
    {
        return true;
        /*$m = $this->getFirst();
        return empty(array_diff_key($m, $_input));*/
    }

    public function add(array $_input) : bool
    {
        return true;
    }

    public function update($_id, array $_input): bool
    {
        return $this->exists($_id);
    }

    public function delete($_id): bool
    {
        return $this->exists($_id);
    }
}