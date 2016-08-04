<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date helper class
 *
* @package    OC
* @category   Date
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2009-2013 Open Classifieds Team
* @license    GPL v3
*/
class Date extends Kohana_Date {
    
    /**
     * Formats the given date into another format
     * 
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function format($date, $format='d/m/Y')
    {
       return date($format, strtotime($date));
    }
    
    /**
     * 
     * Converts a Unix time stamp to a MySQL date
     * @param integer $date
     * @return string
     */
    public static function unix2mysql($date = NULL)
    {
        if($date === NULL)
        {
            $date = time();
        }
        return date(Date::$timestamp_format,$date);
    }
    
    /**
     * 
     * Converts a MySQL date to a Unix date
     * @param unknown_type $date
     * @return number
     */
    public static function mysql2unix($date)
    {
        return strtotime($date);
    }

    /**
     * shortcut fot DateTime::createFromFormat
     * @param  string $date          
     * @param  string $input_format  
     * @param  string $output_format 
     * @return mixed                
     */
    public static function from_format($date, $input_format = 'd/m/yy', $output_format = 'm-d-Y')
    {
        if($date === NULL)
            $date = time();

        $datetime = DateTime::createFromFormat($input_format, $date);

        switch ($output_format) 
        {
            case 'unix':
                return date::unix2mysql($datetime->getTimestamp());
                break;
            
            default:
                return $datetime->format($output_format);
                break;
        }

    }

    /**
     * get an array range with dates in a specific format
     * @param  string $start      from
     * @param  string $end        to
     * @param  string $step       strtotime style
     * @param  string $format     date format
     * @param  array $array_fill default fill for the array to return
     * @param  string $d_field      the field we use to put the date into
     * @return array             
     */
    public static function range($start, $end, $step = '+1 day', $format = 'Y-m-d', $array_fill = NULL, $d_field = 'date') 
    {
        //return array
        $range = array();

        if (is_string($start) === TRUE) 
            $start = strtotime($start);
        if (is_string($end) === TRUE) 
            $end   = strtotime($end);

        do 
        {
            $date = date($format, $start);

            if (is_array($array_fill))
            {
                $array_fill[$d_field]  = $date;
                $range[] = $array_fill;
            }   
            else
                $range[] = $date;
            
            $start  = strtotime($step, $start);//increase

        } while($start <= $end);

        return $range;
    }
    

    /**
     * seconds to readable time format h:i:s
     * @param  integer $secs 
     * @return string      
     */
    public static function secs_to_time($secs) 
    {
        $times = array(3600, 60, 1);
        $time = '';
        $tmp  = '';

        for($i = 0; $i < 3; $i++) 
        {
            $tmp = floor($secs / $times[$i]);
            if($tmp < 1) 
            {
                $tmp = '00';
            }
            elseif($tmp < 10) 
            {
                $tmp = '0' . $tmp;
            }
            $time .= $tmp;
            if($i < 2) 
            {
                $time .= ':';
            }
            $secs = $secs % $times[$i];
        }
        return $time;
    }
    
   
    
} // End Date