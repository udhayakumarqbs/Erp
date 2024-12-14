<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Database\MySQLi;

use CodeIgniter\Database\BaseUtils;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Utils for MySQLi
 */
class Utils extends BaseUtils
{
    /**
     * List databases statement
     *
     * @var string
     */
    protected $listDatabases = 'SHOW DATABASES';

    /**
     * OPTIMIZE TABLE statement
     *
     * @var string
     */
    protected $optimizeTable = 'OPTIMIZE TABLE %s';

    /**
     * Platform dependent version of the backup function.
     *
     * //@return never
     */

    // public function _backup(?array $params = null)
    // {
    //     // throw new DatabaseException('Unsupported feature of the database platform you are using.');

    //     if (count($params) === 0)
    // 	{
    // 		return FALSE;
    // 	}

    // 	// Extract the prefs for simplicity
    // 	extract($params);

    // 	// Build the output
    // 	$output = '';

    // 	// Do we need to include a statement to disable foreign key checks?
    // 	if ($foreign_key_checks === FALSE)
    // 	{
    // 		$output .= 'SET foreign_key_checks = 0;'.$newline;
    // 	}

    // 	foreach ( (array) $tables as $table)
    // 	{
    // 		// Is the table in the "ignore" list?
    // 		if (in_array($table, (array) $ignore, TRUE))
    // 		{
    // 			continue;
    // 		}

    // 		// Get the table schema
    // 		$query = $this->db->query('SHOW CREATE TABLE '.$this->db->escapeIdentifiers($this->db->database.'.'.$table));

    // 		// No result means the table name was invalid
    // 		if ($query === FALSE)
    // 		{
    // 			continue;
    // 		}

    // 		// Write out the table schema
    // 		$output .= '#'.$newline.'# TABLE STRUCTURE FOR: '.$table.$newline.'#'.$newline.$newline;

    // 		if ($add_drop === TRUE)
    // 		{
    // 			$output .= 'DROP TABLE IF EXISTS '.$this->db->protectIdentifiers($table).';'.$newline.$newline;
    // 		}

    // 		$i = 0;
    // 		$result = $query->getResultArray();
    // 		foreach ($result[0] as $val)
    // 		{
    // 			if ($i++ % 2)
    // 			{
    // 				$output .= $val.';'.$newline.$newline;
    // 			}
    // 		}

    // 		// If inserts are not needed we're done...
    // 		if ($add_insert === FALSE)
    // 		{
    // 			continue;
    // 		}

    // 		// Grab all the data from the current table
    // 		$query = $this->db->query('SELECT * FROM '.$this->db->protectIdentifiers($table));

    // 		if ($query->getNumRows() === 0)
    // 		{
    // 			continue;
    // 		}

    // 		// Fetch the field names and determine if the field is an
    // 		// integer type. We use this info to decide whether to
    // 		// surround the data with quotes or not

    // 		$i = 0;
    // 		$field_str = '';
    // 		$is_int = array();
    // 		while ($field = mysqli_fetch_field($query->getResultId()))
    // 		{
    // 			// Most versions of MySQL store timestamp as a string
    // 			$is_int[$i] = in_array(strtolower(mysqli_field_type($query->result_id, $i)),
    // 						array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'), //, 'timestamp'),
    // 						TRUE);

    // 			// Create a string of field names
    // 			$field_str .= $this->db->escape_identifiers($field->name).', ';
    // 			$i++;
    // 		}

    // 		// Trim off the end comma
    // 		$field_str = preg_replace('/, $/' , '', $field_str);

    // 		// Build the insert string
    // 		foreach ($query->result_array() as $row)
    // 		{
    // 			$val_str = '';

    // 			$i = 0;
    // 			foreach ($row as $v)
    // 			{
    // 				// Is the value NULL?
    // 				if ($v === NULL)
    // 				{
    // 					$val_str .= 'NULL';
    // 				}
    // 				else
    // 				{
    // 					// Escape the data if it's not an integer
    // 					$val_str .= ($is_int[$i] === FALSE) ? $this->db->escape($v) : $v;
    // 				}

    // 				// Append a comma
    // 				$val_str .= ', ';
    // 				$i++;
    // 			}

    // 			// Remove the comma at the end of the string
    // 			$val_str = preg_replace('/, $/' , '', $val_str);

    // 			// Build the INSERT string
    // 			$output .= 'INSERT INTO '.$this->db->protect_identifiers($table).' ('.$field_str.') VALUES ('.$val_str.');'.$newline;
    // 		}

    // 		$output .= $newline.$newline;
    // 	}

    // 	// Do we need to include a statement to re-enable foreign key checks?
    // 	if ($foreign_key_checks === FALSE)
    // 	{
    // 		$output .= 'SET foreign_key_checks = 1;'.$newline;
    // 	}

    // 	return $output;
    // }


    public function _backup(?array $params = null)
    {
        // Commenting out this line as throwing an exception might not be desired behavior
        // throw new DatabaseException('Unsupported feature of the database platform you are using.');

        // Check if $params is empty
        if (empty($params)) {
            return FALSE;
        }

        // Extract the parameters for simplicity
        $foreign_key_checks = $params['foreign_key_checks'] ?? TRUE;
        $add_drop = $params['add_drop'] ?? TRUE;
        $add_insert = $params['add_insert'] ?? TRUE;
        $tables = $params['tables'] ?? [];
        $ignore = $params['ignore'] ?? [];
        $newline = "\n"; // Assuming newline character

        // Initialize output
        $output = '';

        // Do we need to include a statement to disable foreign key checks?
        if ($foreign_key_checks === FALSE) {
            $output .= 'SET foreign_key_checks = 0;' . $newline;
        }

        foreach ($tables as $table) {
            // Is the table in the "ignore" list?
            if (in_array($table, $ignore, TRUE)) {
                continue;
            }

            // Get the table schema
            $query = $this->db->query('SHOW CREATE TABLE ' . $this->db->escapeIdentifiers($table));

            // No result means the table name was invalid
            if ($query === FALSE) {
                continue;
            }

            // Write out the table schema
            $output .= '#' . $newline . '# TABLE STRUCTURE FOR: ' . $table . $newline . '#' . $newline . $newline;

            if ($add_drop === TRUE) {
                $output .= 'DROP TABLE IF EXISTS ' . $this->db->protectIdentifiers($table) . ';' . $newline . $newline;
            }

            $schema = $query->getRow()->{'Create Table'};
            $output .= $schema . ';' . $newline . $newline;

            // If inserts are not needed we're done...
            if ($add_insert === FALSE) {
                continue;
            }

            // Grab all the data from the current table
            $dataQuery = $this->db->table($table)->get();

            if ($dataQuery->getNumRows() === 0) {
                continue;
            }

            // Fetch the field names and determine if the field is an
            // integer type. We use this info to decide whether to
            // surround the data with quotes or not

            // $fields = $dataQuery->getFieldNames();
            // $is_int = [];

            // foreach ($fields as $field)
            // {
            //     // Most versions of MySQL store timestamp as a string
            //     $is_int[] = in_array(strtolower($field->getType()),
            //                 ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'], //, 'timestamp'),
            //                 TRUE);
            // }

            // Fetch the field names and determine if the field is an integer type. We use this info to decide whether to surround the data with quotes or not
            // $fields = $dataQuery->getFieldData();
            $fields = $dataQuery->getFieldNames();
            $fieldMetadata = $this->db->getFieldData($table);
            $is_int = [];

            foreach ($fieldMetadata as $field) {
                // Most versions of MySQL store timestamp as a string
                $is_int[] = in_array(
                    strtolower($field->type),
                    ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'], //, 'timestamp'),
                    TRUE
                );
            }



            // Build the insert string
            // foreach ($dataQuery->getResultArray() as $row) {
            //     $val_str = '';

            //     foreach ($row as $i => $v) {
            //         // Is the value NULL?
            //         if ($v === NULL) {
            //             $val_str .= 'NULL';
            //         } else {
            //             // Escape the data if it's not an integer
            //             $val_str .= ($is_int[$i] === FALSE) ? $this->db->escape($v) : $v;
            //         }

            //         // Append a comma
            //         $val_str .= ', ';
            //     }

            //     // Remove the comma at the end of the string
            //     $val_str = rtrim($val_str, ', ');

            //     // Build the INSERT string
            //     $output .= 'INSERT INTO ' . $this->db->protectIdentifiers($table) . ' (' . implode(', ', $fields) . ') VALUES (' . $val_str . ');' . $newline;
            // }

            // Build the insert string
            foreach ($dataQuery->getResultArray() as $row) {
                $val_str = '';

                foreach ($row as $i => $v) {
                    // Check if the index exists in the $is_int array
                    if (isset($is_int[$i]) && $is_int[$i] === FALSE) {
                        $val_str .= $this->db->escape($v);
                    } else {
                        $val_str .= $v;
                    }

                    // Append a comma
                    $val_str .= ', ';
                }

                // Remove the comma at the end of the string
                $val_str = rtrim($val_str, ', ');

                // Build the INSERT string
                $output .= 'INSERT INTO ' . $this->db->protectIdentifiers($table) . ' (' . implode(', ', $fields) . ') VALUES (' . $val_str . ');' . $newline;
            }


            $output .= $newline . $newline;
        }

        // Do we need to include a statement to re-enable foreign key checks?
        if ($foreign_key_checks === FALSE) {
            $output .= 'SET foreign_key_checks = 1;' . $newline;
        }

        return $output;
    }
}
