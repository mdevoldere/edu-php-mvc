<?php

namespace MD\Db;

use PDO;
use PdoStatement;
use Exception;

/** Classe Db
 *
 * @author   MDevoldere 
 * @version  1.0
 * @access   public
 */
abstract class DbContext
{
    /** @var PDO $db Représente une connexion vers une base de données */
    static protected ?PDO $pdo = null;

    /**
     * PDO Factory
     */
    static public function getPdo(array $c = []): ?PDO
    {
        try {
            if (static::$pdo === null) {
                if (empty($c['db_type']) || empty($c['db_dsn'])) {
                    exit('Db Error 0');
                    return null;
                }

                switch ($c['db_type']) {
                    case 'mysql':
                        static::$pdo = new PDO(
                            $c['db_dsn'],
                            ($c['db_user'] ?? 'root'),
                            ($c['db_pass'] ?? ''),
                            [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::ATTR_EMULATE_PREPARES => false
                            ]
                        );
                        break;
                    case 'sqlite':
                        static::$pdo = new PDO($c['db_dsn'], 'charset=utf8');
                        static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        static::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                        static::$pdo->exec('pragma synchronous = off;');
                        break;
                    default:
                        exit('Db Error 1');
                        break;
                }
            }

            return static::$pdo;
        } catch (Exception $e) {
            exit('Db Error 10');
        }
    }


    /***** 
     * 
     * OPERATIONS GENERIQUES 
     * 
     * *****/


    /**
     * Retourne le jeu de résultat d'une requête SELECT exécutée
     * @param PDOStatement $stmt Le jeu de résultat de la requête exécutée
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1ère ligne trouvée
     */
    static protected function fetchStmt(PDOStatement $stmt, bool $_all = false)
    {
        $r = (($_all === false) ? $stmt->fetch() : $stmt->fetchAll());
        $stmt->closeCursor();
        return (!empty($r) ? $r : []);
    }

    /** Exécute une requête de lecture simple 
     * @param string $_query La requête SQL à exécuter 
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1ère ligne trouvée 
     * @return mixed Le jeu de résultat ou empty si aucun résultat 
     */
    static public function query(string $_query, bool $_all = false)
    {
        try {
            return static::fetchStmt(static::$pdo->query($_query), $_all);
        } catch (Exception $e) {
            exit('Db Error 11');
        }
    }

    /** Exécute une requête de lecture paramétrée
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1er ligne trouvée
     * @param null|string $_model = Le nom de la classe Modèle à utiliser dans le jeu de résultat ou le mode défini à la connexion si null
     * @return mixed Le jeu de résultat ou empty si aucun résultat
     */
    static public function fetch(string $_query, array $_values = [], bool $_all = false)
    {
        try {
            $stmt = static::$pdo->prepare($_query);
            return ($stmt->execute($_values) ? static::fetchStmt($stmt, $_all) : null);
        } catch (Exception $e) {
            exit('Db Error 100' . $e->getMessage());
        }
    }

    static public function fetchAll(string $_query, array $_values = [])
    {
        return static::fetch($_query, $_values, true);
    }


    /** Exécute une requête d'écriture paramétrée
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @return int Le nombre de lignes affectées
     */
    static public function exec(string $_query, array $_values = []): int
    {
        try {
            $stmt = static::$pdo->prepare($_query);

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
    /*static public function insert(array $_values): int
    {
        $cols = \array_keys($_values);
        $vals = (':' . \implode(', :', $cols));
        $cols = \implode(',', $cols);

        return static::exec("INSERT INTO " . static::$tableName . " (" . $cols . ") VALUES (" . $vals . ");", $_values);
    }*/

    /** Met à jour un élément
     * @param array\Db $_values Le tableau de valeurs correspondant à la table courante. Doit contenir l'identifiant de la ligne à mettre à jour.
     * @return int Le nombre de lignes affectées
     */
    /*static public function update(array $_values): int
    {
        $cols = [];

        foreach ($_values as $k => $v) {
            $cols[$k] = ($k . '=:' . $k);
        }

        return static::exec("UPDATE " . static::$tableName  . " SET " . \implode(', ', $cols) . " WHERE " . static::$pkName  . "=:" . static::$pkName  . ";", $_values);
    }*/

    /** Supprime un élément
     * @param int $_id L'identifiant de la ligne à supprimer
     * @return int Le nombre de lignes affectées
     */
    /*static public function delete($_id): int
    {
        return static::exec("DELETE FROM " . static::$tableName  . " WHERE " . static::$pkName  . "=:id;", [':id' => $_id]);
    }*/
}
