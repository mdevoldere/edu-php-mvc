<?php 

namespace MD\Db;


interface IRepository 
{
    public function count(): int;

    public function exists($_id): bool;

    public function getAll(): array;

    public function getBy(string $_col, string $_value, bool $_all = true): array;

    public function getById($_id): array;

    public function validate(array &$_input): bool;

    public function add(array $_input) : bool;

    public function update($id, array $_input): bool;

    public function delete($_id): bool;
}