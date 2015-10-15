<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * MySQL database result.   See [Results](/database/results) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query/Result
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Database_MySQL_Result extends Database_Result {

	protected $_internal_row = 0;

	public function __construct($result, $sql, $as_object = FALSE, array $params = NULL)
	{
		parent::__construct($result, $sql, $as_object, $params);

		// Find the number of rows in the result
		$this->_total_rows = mysql_num_rows($result);
	}

	public function __destruct()
	{
		if (is_resource($this->_result))
		{
			mysql_free_result($this->_result);
		}
	}

	public function seek($offset)
	{
		if ($this->offsetExists($offset) AND mysql_data_seek($this->_result, $offset))
		{
			// Set the current row to the offset
			$this->_current_row = $this->_internal_row = $offset;

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function current()
	{
		if ($this->_current_row !== $this->_internal_row AND ! $this->seek($this->_current_row))
			return NULL;

		// Increment internal row for optimization assuming rows are fetched in order
		$this->_internal_row++;

		// FIXME mysql_fetch_object has been deprecated as of php 5.5!
		// Please use mysqli_fetch_object or PDOStatement::fetch(PDO::FETCH_OBJ) instead.

		if ($this->_as_object === TRUE)
		{
			// Return an stdClass
			return mysql_fetch_object($this->_result);
		}
		elseif (is_string($this->_as_object))
		{
			/* The second and third argument for mysql_fetch_object are optional, but do 
			 * not have default values defined.  Passing _object_params with a non-array value results 
			 * in undefined behavior that varies by PHP version.  For example, if NULL is supplied on 
			 * PHP 5.3, the resulting behavior is identical to calling with array(), which results in the 
			 * classes __construct function being called with no arguments. This is only an issue when 
			 * the _as_object class does not have an explicit __construct method resulting in the 
			 * cryptic error "Class %s does not have a constructor hence you cannot use ctor_params."
			 * In contrast, the same function call on PHP 5.5 will 'functionally' interpret 
			 * _object_params == NULL as an omission of the third argument, resulting in the original
			 * intended functionally.
			 * 
			 * Because the backing code for the mysql_fetch_object has not changed between 5.3 and 5.5,
			 * I suspect this discrepancy is due to the way the classes are instantiated on a boarder 
			 * level. Additionally, mysql_fetch_object has been deprecated in 5.5 and should probably be 
			 * replaced by mysqli_fetch_object or PDOStatement::fetch(PDO::FETCH_OBJ) in Kohana 3.4.
			 */
			if ($this->_object_params !== NULL)
			{
				// Return an object of given class name with constructor params
				return mysql_fetch_object($this->_result, $this->_as_object, $this->_object_params);
			}
			else
			{
				// Return an object of given class name without constructor params
				return mysql_fetch_object($this->_result, $this->_as_object);
			}
		}
		else
		{
			// Return an array of the row
			return mysql_fetch_assoc($this->_result);
		}
	}

} // End Database_MySQL_Result_Select
