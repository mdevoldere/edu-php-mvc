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
    /** Performs a simple read request 
     * @param string $_query SQL query to execute 
     * @param bool $_all true = return all rows. false = return first row
     * @return mixed result set or empty array 
     */
    public function query(string $_query, bool $_all = false): array;

    /** Executes a parameterized read request
     * @param string $_query SQL query to execute
     * @param array $_values the values associated with the query parameters
     * @param bool $_all true = return all rows. false = return first row
     * @return mixed result set or empty array 
     */
    public function fetch(string $_query, array $_values = [], bool $_all = false): array;

    /** Execute a parameterized read request and return all rows  
     * @param string $_query SQL query to execute
     * @param array $_values the values associated with the query parameters
     * @return mixed result set or empty array 
     */
    public function fetchAll(string $_query, array $_values = []): array;

    /** Executes a parameterized write request and returns the number of rows affected
     * @param string $_query SQL query to execute
     * @param array $_values the values associated with the query parameters
     * @return int number of rows affected by the query
     */
    public function exec(string $_query, array $_values = []): int;
}