<?php 

namespace Md\Db;

/** Interface IDbContext
 *
 * @author   MDevoldere 
 * @version  1.0.0
 * @access   public
 */
interface IDbContext 
{
    /** Exécute une requête de lecture simple 
     * @param string $_query La requête SQL à exécuter 
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1ère ligne trouvée 
     * @return array Le jeu de résultat ou empty si aucun résultat 
     */
    public function query(string $_query, bool $_all = false): array;

    /** Exécute une requête de lecture paramétrée
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @param bool $_all true = retourne toutes les lignes trouvées. false = retourne la 1er ligne trouvée
     * @return array Le jeu de résultat ou empty si aucun résultat
     */
    public function fetch(string $_query, array $_values = [], bool $_all = false): array;

    /** Exécute une requête de lecture paramétrée et retourne toutes les lignes trouvées
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @return array Le jeu de résultat ou empty si aucun résultat
     */
    public function fetchAll(string $_query, array $_values = []): array;

    /** Exécute une requête d'écriture paramétrée
     * @param string $_query La requête à exécuter
     * @param array $_values Les paramètres de la requête
     * @return int Le nombre de lignes affectées
     */
    public function exec(string $_query, array $_values = []): int;
}