<?php

namespace Md\Db;

use PDO;
use PdoStatement;
use Exception;

/** Class Db
 *
 * @author   MDevoldere 
 * @version  1.0.1
 * @access   public
 */
class DbContext implements IDbContext 
{

    /** @var IDbContext[] $context DbContext storage */
    static protected array $context = [];

    static public function getContext(string $_context = 'default') : ?IDbContext
    {
        return self::$context[$_context] ?? null;
    }

    static public function setContext(string $_context, array $c): void
    {
        try {
            
            if (empty($c['db_type']) || empty($c['db_dsn'])) {
                return;
            }

            switch ($c['db_type']) {
                case 'mysql':
                    self::$context[$_context] = new DbContext(new PDO(
                        $c['db_dsn'],
                        ($c['db_user'] ?? 'root'),
                        ($c['db_pass'] ?? ''),
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false
                        ]
                    ));
                    break;
                case 'sqlite':
                    $pdo = new PDO($c['db_dsn'], 'charset=utf8');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $pdo->exec('pragma synchronous = off;');
                    self::$context[$_context] = new DbContext($pdo);
                    break;
                default:
                    return;
                    break;
            }

        } catch (Exception $e) {
            exit('DbContext Error');
        }
    }


    /**
     * Get fetch result from executed prepared statement (SELECT queries only)
     * @param PDOStatement $stmt the prepared statement already executed
     * @param bool $_all true = return all lines. false = return first line
     * @return array result set or empty array
     */
    static protected function fetchStmt(PDOStatement $stmt, bool $_all = false): array
    {
        $r = (($_all === false) ? $stmt->fetch() : $stmt->fetchAll());
        $stmt->closeCursor();
        return (!empty($r) ? $r : []);
    }


    /** @var PDO $db PDO Connection */
    protected ?PDO $pdo = null;


    /**
     * DbContext Constructor
     */
    public function __construct(PDO $_pdo) 
    {
        $this->pdo = $_pdo;
    }  

    /** Performs a simple read request 
     * @param string $_query SQL query to execute 
     * @param bool $_all true = return all rows. false = return first row
     * @return mixed result set or empty array 
     */
    public function query(string $_query, bool $_all = false): array
    {
        try {
            return self::fetchStmt($this->pdo->query($_query), $_all);
        } catch (Exception $e) {
            exit('DbQuery Error');
        }
    }

    /** Executes a parameterized read request
     * @param string $_query SQL query to execute
     * @param array $_values the values associated with the query parameters
     * @param bool $_all true = return all rows. false = return first row
     * @return mixed result set or empty array 
     */
    public function fetch(string $_query, array $_values = [], bool $_all = false): array
    {
        try {
            $stmt = $this->pdo->prepare($_query);
            return ($stmt->execute($_values) ? static::fetchStmt($stmt, $_all) : []);
        } catch (Exception $e) {
            exit('DbFetch Error' . $e->getMessage());
        }
    }

    /** Execute a parameterized read request and return all rows  
     * @param string $_query SQL query to execute
     * @param array $_values the values associated with the query parameters
     * @return mixed result set or empty array 
     */
    public function fetchAll(string $_query, array $_values = []): array
    {
        return $this->fetch($_query, $_values, true);
    }


    /** Executes a parameterized write request and returns the number of rows affected
     * @param string $_query SQL query to execute
     * @param array $_values the values associated with the query parameters
     * @return int number of rows affected by the query
     */
    public function exec(string $_query, array $_values = []): int
    {
        try {
            $stmt = $this->pdo->prepare($_query);

            if ($stmt->execute($_values)) {
                $r = $stmt->rowCount();
                $stmt->closeCursor();
                return $r;
            }
            return 0;
        } catch (Exception $e) {
            exit('Db Error 101');
        }
    }


    /** Add data to specific table
     * @param string $_table the table
     * @param array $_values data to insert (must match to table structure)
     * @return int number of rows affected
     */
    public function insert(string $_table, array $_values): int
    {
        $cols = \array_keys($_values);
        $vals = (':' . \implode(', :', $cols));
        $cols = \implode(',', $cols);

        return $this->exec("INSERT INTO " . $_table . " (" . $cols . ") VALUES (" . $vals . ");", $_values);
    }

    /** Update a row in specific table
     * @param string $_table the table
     * @param string $_pk the primary key name
     * @param array $_values The array of values corresponding to the current table. Must contain the identifier of the row to update.
     * @return int number of rows affected
     */
    public function update(string $_table, string $_pk, array $_values): int
    {
        $id = null;
        $cols = [];

        foreach ($_values as $k => $v) {
            if($k !== $_pk) {
                $cols[$k] = ($k . '=:' . $k);
            }
            else {
                $id = $v;
            }            
        }

        if($id !== null) {
            return $this->exec("UPDATE " . $_table  . " SET " . \implode(', ', $cols) . " WHERE " . $_pk  . "=:" . $id  . ";", $_values);
        }
    }

    /** Delete a row in specific table
     * @param string $_table the table
     * @param string $_pk the primary key name
     * @param string $_id row identifier
     * @return int number of rows affected
     */
    public function delete(string $_table, string $_pk, string $_id): int
    {
        return $this->exec("DELETE FROM " . $_table  . " WHERE " . $_pk  . "=:id;", [':id' => $_id]);
    }
}
