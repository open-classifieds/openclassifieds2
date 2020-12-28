<?php defined('SYSPATH') or die('No direct script access.');
/**
 * mysqli class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, xavi <xavi@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */
class Database_MySQLi extends Kohana_Database_MySQLi {

    public function multi_query($sql, $as_object = FALSE, array $params = NULL)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if ( ! empty($this->_config['profiling']))
        {
            // Benchmark this query for the current instance
            $benchmark = Profiler::start("Database ({$this->_instance})", $sql);
        }

        if ( ! empty($this->_config['connection']['persistent']) AND $this->_config['connection']['database'] !== Database_MySQLi::$_current_databases[$this->_connection_id])
        {
            // Select database on persistent connections
            $this->_select_db($this->_config['connection']['database']);
        }

        // Execute the query
        if (($result = $this->_connection->multi_query($sql)) === FALSE)
        {
            if (isset($benchmark))
            {
                // This benchmark is worthless
                Profiler::delete($benchmark);
            }

            throw new Database_Exception('[:code] :error ( :query )', array(
                ':code' => $this->_connection->errno,
                ':error' => $this->_connection->error,
                ':query' => $sql,
            ), $this->_connection->errno);
        }
        while($this->_connection->more_results() && $this->_connection->next_result()) {
            $extraResult = $this->_connection->use_result();
            if($extraResult instanceof mysqli_result){
                $extraResult->free();
            }
        }


        if (isset($benchmark))
        {
            Profiler::stop($benchmark);
        }

        // Set the last query
        $this->last_query = $sql;


        // Return the number of rows affected
        return $this->_connection->affected_rows;

    }

    public function datatype($type)
	{
		static $types = [
			'blob'                      => ['type' => 'string', 'binary' => TRUE, 'character_maximum_length' => '65535'],
			'bool'                      => ['type' => 'bool'],
			'bigint unsigned'           => ['type' => 'int', 'min' => '0', 'max' => '18446744073709551615'],
			'datetime'                  => ['type' => 'string'],
			'decimal unsigned'          => ['type' => 'float', 'exact' => TRUE, 'min' => '0'],
			'double'                    => ['type' => 'float'],
			'double precision unsigned' => ['type' => 'float', 'min' => '0'],
			'double unsigned'           => ['type' => 'float', 'min' => '0'],
			'enum'                      => ['type' => 'string'],
			'fixed'                     => ['type' => 'float', 'exact' => TRUE],
			'fixed unsigned'            => ['type' => 'float', 'exact' => TRUE, 'min' => '0'],
			'float unsigned'            => ['type' => 'float', 'min' => '0'],
			'geometry'                  => ['type' => 'string', 'binary' => TRUE],
			'int unsigned'              => ['type' => 'int', 'min' => '0', 'max' => '4294967295'],
			'integer unsigned'          => ['type' => 'int', 'min' => '0', 'max' => '4294967295'],
			'longblob'                  => ['type' => 'string', 'binary' => TRUE, 'character_maximum_length' => '4294967295'],
			'longtext'                  => ['type' => 'string', 'character_maximum_length' => '4294967295'],
			'mediumblob'                => ['type' => 'string', 'binary' => TRUE, 'character_maximum_length' => '16777215'],
			'mediumint'                 => ['type' => 'int', 'min' => '-8388608', 'max' => '8388607'],
			'mediumint unsigned'        => ['type' => 'int', 'min' => '0', 'max' => '16777215'],
			'mediumtext'                => ['type' => 'string', 'character_maximum_length' => '16777215'],
			'national varchar'          => ['type' => 'string'],
			'numeric unsigned'          => ['type' => 'float', 'exact' => TRUE, 'min' => '0'],
			'nvarchar'                  => ['type' => 'string'],
			'point'                     => ['type' => 'string', 'binary' => TRUE],
			'real unsigned'             => ['type' => 'float', 'min' => '0'],
			'set'                       => ['type' => 'string'],
			'smallint unsigned'         => ['type' => 'int', 'min' => '0', 'max' => '65535'],
			'text'                      => ['type' => 'string', 'character_maximum_length' => '65535'],
			'tinyblob'                  => ['type' => 'string', 'binary' => TRUE, 'character_maximum_length' => '255'],
			'tinyint'                   => ['type' => 'int', 'min' => '-128', 'max' => '127'],
			'tinyint unsigned'          => ['type' => 'int', 'min' => '0', 'max' => '255'],
			'tinytext'                  => ['type' => 'string', 'character_maximum_length' => '255'],
            'year'                      => ['type' => 'string'],
			'json'                      => ['type' => 'string'],
		];

		$type = str_replace(' zerofill', '', $type);

		if (isset($types[$type]))
			return $types[$type];

		return parent::datatype($type);
	}

}
