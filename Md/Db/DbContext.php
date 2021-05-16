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

    /** @var IDbContext[] $context */
    static protected array $context = [];

    static public function getContext(string $_context = 'default') : ?IDbContext
    {
        return self::$context[$_context] ?? null;
    }

    static public function setContext(string $_context, array $c): ?IDbContext
    {
        try {
            
            if (empty($c['db_type']) || empty($c['db_dsn'])) {
                exit('Db Error 0');
                return null;
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
                    exit('Db Error 1');
                    break;
            }

            return self::getContext($_context);

        } catch (Exception $e) {
            exit('Db Error 10');
        }
    }


    /**
     * Retourne le jeu de résultat d'une requête SELECT exécutée
     * @param PDOStatement $stmt Le jeu de résultat de la requête exécutée
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1ère ligne trouvée
     * @return array le jeu de résultat ou un tableau vide
     */
    static protected function fetchStmt(PDOStatement $stmt, bool $_all = false): array
    {
        $r = (($_all === false) ? $stmt->fetch() : $stmt->fetchAll());
        $stmt->closeCursor();
        return (!empty($r) ? $r : []);
    }


    /** @var PDO $db Représente une connexion vers une base de données */
    protected ?PDO $pdo = null;


    public function __construct(PDO $_pdo) 
    {
        $this->pdo = $_pdo;
    }  

    /** Exécute une requête de lecture simple 
     * @param string $_query La requête SQL à exécuter 
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1ère ligne trouvée 
     * @return mixed Le jeu de résultat ou empty si aucun résultat 
     */
    public function query(string $_query, bool $_all = false): array
    {
        try {
            return self::fetchStmt($this->pdo->query($_query), $_all);
        } catch (Exception $e) {
            exit('Db Error 11');
        }
    }

    /** Exécute une requête de lecture paramétrée
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1er ligne trouvée
     * @return mixed Le jeu de résultat ou empty si aucun résultat
     */
    public function fetch(string $_query, array $_values = [], bool $_all = false): array
    {
        try {
            $stmt = $this->pdo->prepare($_query);
            return ($stmt->execute($_values) ? static::fetchStmt($stmt, $_all) : []);
        } catch (Exception $e) {
            exit('Db Error 100' . $e->getMessage());
        }
    }

    /** Exécute une requête de lecture paramétrée et retourne toutes les lignes trouvées 
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @return mixed Le jeu de résultat ou empty si aucun résultat
     */
    public function fetchAll(string $_query, array $_values = []): array
    {
        return $this->fetch($_query, $_values, true);
    }


    /** Exécute une requête d'écriture paramétrée et retourne le nombre de lignes affectées
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @return int Le nombre de lignes affectées
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


    /** Insère un nouvel élément
     * @param array|Db $_values Le tableau de valeurs correspondant à la table courante
     * @return int Le nombre de lignes affectées
     */
    public function insert(string $_table, array $_values): int
    {
        $cols = \array_keys($_values);
        $vals = (':' . \implode(', :', $cols));
        $cols = \implode(',', $cols);

        return $this->exec("INSERT INTO " . $_table . " (" . $cols . ") VALUES (" . $vals . ");", $_values);
    }

    /** Met à jour un élément
     * @param array\Db $_values Le tableau de valeurs correspondant à la table courante. Doit contenir l'identifiant de la ligne à mettre à jour.
     * @return int Le nombre de lignes affectées
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

    /** Supprime un élément
     * @param int $_id L'identifiant de la ligne à supprimer
     * @return int Le nombre de lignes affectées
     */
    /*static public function delete($_id): int
    {
        return static::exec("DELETE FROM " . static::$tableName  . " WHERE " . static::$pkName  . "=:id;", [':id' => $_id]);
    }*/
}
