<?php 

namespace Md\Db;

/**
 * Represents a Table with basics operations (CRUD)
 */
interface IRepository 
{
    /**
     * Get total rows number
     * @return int total rows number
     */
    public function count(): int;

    /**
     * Check if identifier exists in table
     * @param string $_id identifier to search
     * @return bool true if exists, false if not found 
     */
    public function exists( string $_id): bool;

    /**
     * Get all table rows
     * @return array all table rows
     */
    public function getAll(): array;

    /**
     * Get by specific column value
     * @param string $_col the column in table
     * @param string $_value the value to search in column
     * @param bool $_all true to get all result set, false to get the first row found
     */
    public function getBy(string $_col, string $_value, bool $_all = true): array;

    /**
     * Get row by identifier
     * @param string $_id identifier to search
     * @return array row found or empty array
     */
    public function getById(string $_id): array;

    /**
     * Clean & Validate array structure matching current table and clean data if necessary
     * @param array $_input the array to validate
     * @return bool true if array is valid, false if invalid 
     */
    public function validate(array &$_input): bool;

    /**
     * Add row to current table
     * @param array data corresponding to current table
     * @return bool true if row added, false otherwise
     */
    public function add(array $_input) : bool;

    /**
     * Update a row in current table
     * @param string $_id row identifier
     * @param array $_input data to update
     */
    public function update(string $_id, array $_input): bool;

    /**
     * Delete a row in table
     * @param string $_id row identifier
     * @return bool true if deleted, false otherwise
     */
    public function delete(string $_id): bool;
}