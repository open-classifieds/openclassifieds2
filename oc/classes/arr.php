<?php defined('SYSPATH') or die('No direct script access.');
/**
 * arr
 *
 * @package    OC
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */


class Arr extends Kohana_Arr {

    /**
     * Converts an array to XML
     *
     * @param array $array
     * @param SimpleXMLElement $xml
     * @param string $child_name
     *
     * @return SimpleXMLElement $xml
     */
    public static function to_xml($array, SimpleXMLElement $xml, $child_name)
    {
        foreach ($array as $k => $v) {
            if(is_array($v)) {
                (is_int($k)) ? self::to_xml($v, $xml->addChild($child_name), $v) : self::to_xml($v, $xml->addChild(strtolower($k)), $child_name);
            } else {
                (is_int($k)) ? $xml->addChild($child_name, $v) : $xml->addChild(strtolower($k), $v);
            }
        }

        return $xml->asXML();
    }

    /**
     * Re array the odd indexing of multiple file uploads from the format:
     *
     * $_FILES['field']['key']['index']
     *
     * to
     *
     * $_FILES['field']['index']['key']
     *
     * @param array $files_post
     *
     * @return array $files
     */
    public static function re_array_multiple_file_uploads($files_post) {
        $files = [];
        $files_count = count($files_post['name']);
        $files_keys = array_keys($files_post);

        for ($i = 0; $i < $files_count; $i++)
        {
            foreach ($files_keys as $key) {
                $files[$i][$key] = $files_post[$key][$i];
            }
        }

        return $files;
    }


	/**
	 * Gets a value from an array using a dot separated path.
	 *
	 *     // Get the value of $array['foo']['bar']
	 *     $value = Arr::path($array, 'foo.bar');
	 *
	 * Using a wildcard "*" will search intermediate arrays and return an array.
	 *
	 *     // Get the values of "color" in theme
	 *     $colors = Arr::path($array, 'theme.*.color');
	 *
	 *     // Using an array of keys
	 *     $colors = Arr::path($array, array('theme', '*', 'color'));
	 *
	 * @param   array   $array      array to search
	 * @param   mixed   $path       key path string (delimiter separated) or array of keys
	 * @param   mixed   $default    default value if the path is not set
	 * @param   string  $delimiter  key path delimiter
	 * @return  mixed
	 */
	public static function path($array, $path, $default = NULL, $delimiter = NULL)
	{
		if ( ! Arr::is_array($array))
		{
			// This is not an array!
			return $default;
		}

		if (is_array($path))
		{
			// The path has already been separated into keys
			$keys = $path;
		}
		else
		{
			if (is_array($array) AND array_key_exists($path, $array))
			{
				// No need to do extra processing
				return $array[$path];
			}

			if (is_object($array) AND property_exists($array, $path))
			{
				// No need to do extra processing
				return $array[$path];
			}

			if ($delimiter === NULL)
			{
				// Use the default delimiter
				$delimiter = Arr::$delimiter;
			}

			// Remove starting delimiters and spaces
			$path = ltrim($path, "{$delimiter} ");

			// Remove ending delimiters, spaces, and wildcards
			$path = rtrim($path, "{$delimiter} *");

			// Split the keys by delimiter
			$keys = explode($delimiter, $path);
		}

		do
		{
			$key = array_shift($keys);

			if (ctype_digit($key))
			{
				// Make the key an integer
				$key = (int) $key;
			}

			if (isset($array[$key]))
			{
				if ($keys)
				{
					if (Arr::is_array($array[$key]))
					{
						// Dig down into the next part of the path
						$array = $array[$key];
					}
					else
					{
						// Unable to dig deeper
						break;
					}
				}
				else
				{
					// Found the path requested
					return $array[$key];
				}
			}
			elseif ($key === '*')
			{
				// Handle wildcards

				$values = [];
				foreach ($array as $arr)
				{
					if ($value = Arr::path($arr, implode('.', $keys)))
					{
						$values[] = $value;
					}
				}

				if ($values)
				{
					// Found the values requested
					return $values;
				}
				else
				{
					// Unable to dig deeper
					break;
				}
			}
			else
			{
				// Unable to dig deeper
				break;
			}
		}
		while ($keys);

		// Unable to find the value requested
		return $default;
	}
}
