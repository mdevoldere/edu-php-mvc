<?php 

namespace Md\Db;

use function array_key_exists;

abstract class Model
{
    private string $pk;

    private array $struct;

    private string $select;


    public function __construct(string $_pk, array $_struct)
    {
        $this->pk = $_pk;
        $this->struct = $_struct;
    }

    abstract public function getId(): int;


    public function validate(array &$_input, $_id = null): bool
    {
        if($_id === null) {
            foreach($this->struct as $k => $v) {
                if(!array_key_exists($k, $_input)) {
                    return false;
                }
            }
        }

        foreach($_input as $k => $v) {
            if(!array_key_exists($k, $this->struct)) {
                return false;
            }

            /*switch($k) {
            
            }*/

        }
        return true;
    }
}