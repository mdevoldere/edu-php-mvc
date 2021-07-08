<?php 

namespace Md\Db;

use function basename;

class Repository implements IRepository
{

    public string $table;

    public string $pk;

    protected IDbContext $db;

    public function __construct(string $_table, string $_pk, string $_dbContext = 'default')
    {
        $this->table = $_table;
        $this->pk = $_pk;
        $this->db = DbContext::getContext($_dbContext);

        if(empty($this->db)) {
            exit('Repository Error 1 ('.$_dbContext.')');
        }
    }

    public function exists($_id): bool
    {
        return $this->db->fetch("SELECT COUNT(*) as nb FROM " . $this->table . " WHERE " . $this->pk . "=:cond;", [':cond' => $_id], false)['nb'] > 0;
    }

    public function count(): int
    {
        return $this->db->query(("SELECT COUNT(*) as nb FROM " . $this->table . ";"), false)['nb'];
    }

    public function getAll(): array
    {
        return $this->db->query(("SELECT * FROM " . $this->table . ";"), true);
    }

    public function getBy(string $_col, string $_value, bool $_all = false) : array
    {
        return $this->db->fetch("SELECT * FROM " . $this->table . " WHERE " . basename($_col) . "=:cond;", [':cond' => $_value], $_all);
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