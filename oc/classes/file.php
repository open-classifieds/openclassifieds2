<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for Kohana File
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class File extends Kohana_File{
    
    /**
     * copies files/directories recursively
     * @param  string  $source    from
     * @param  string  $dest      to
     * @param  integer $overwrite 0=do not overwrite 1=force overwrite 2=overwrite only is size is different
     * @return void             
     */
    public static function copy($source, $dest, $overwrite = 0)
    { 
        //be sure source exists..
        if (!is_readable($source))
            throw HTTP_Exception::factory(500,'File ('.$source.') could not be readed, likely a permissions problem.');

        //just a file to copy, so do it!
        if(is_file($source))
        {
            $copy_file = FALSE;

            //if doesnt exists OR we want to overwrite always OR different size copy the file.
            if( !is_file( $dest ) OR $overwrite == 1 OR ( $overwrite == 2 AND filesize($source)!==filesize($dest) ) ) 
                $copy_file = TRUE;

            if ($copy_file === TRUE)
            {
                try {
                    copy($source, $dest);
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,'File ('.$source.') could not be copied, likely a permissions problem.');
                }     
            }
            
            //always return if its a file, so we dont move forward
            return;
        }
        
        //was not a file, so folder...lets check exists, if not create it
        if(!is_dir($dest))
            mkdir($dest);     

        //read folder contents
        $objects = scandir($source);
        foreach ($objects as $object) 
        {
            if($object != '.' && $object != '..')
            { 
                $from = $source . '/' . $object; 
                $to   = $dest   . '/' . $object;
                File::copy($from, $to, $overwrite);                  
            } 
        } 
        
    }

    /**
     * deletes file or directory recursevely
     * @param  string $file 
     * @return void       
     */
    public static function delete($file)
    {
        if (is_dir($file)) 
        {
            $objects = scandir($file);
            foreach ($objects as $object) 
            {
                if ($object != '.' && $object != '..') 
                {
                    if (is_dir($file.'/'.$object)) 
                        File::delete($file.'/'.$object); 
                    else 
                        unlink($file.'/'.$object);
                }
            }
            reset($objects);
            @rmdir($file);
        }
        elseif(is_file($file))
            unlink($file);
    }


    /**
     * deletes file or directory recursevely
     * @param  string $file 
     * @return void       
     */
    public static function clean($file)
    {
        if (is_dir($file)) 
        {
            $objects = scandir($file);
            foreach ($objects as $object) 
            {
                if ($object != '.' && $object != '..') 
                {
                    if (is_dir($file.'/'.$object)) 
                        File::clean($file.'/'.$object); 
                    else 
                        File::write($file.'/'.$object,'');
                }
            }
            reset($objects);
        }
        elseif(is_file($file))
            File::write($file.'/'.$object,'');
    }


    /**
     * write to file
     * @param $filename fullpath file name
     * @param $content
     * @return boolean
     */
    public static function write($filename,$content)
    {
        $file = fopen($filename, 'w');
        if ($file)
        {//able to create the file
            fwrite($file, $content);
            fclose($file);
            return TRUE;
        }
        return FALSE;   
    }
    
    /**
     * read file content
     * @param $filename fullpath file name
     * @return $string or false if not found
     */
    public static function read($filename)
    {
        if (is_readable($filename))
        {
            $file = fopen($filename, 'r');
            if ($file)
            {//able to read the file
                try {
                    $data = fread($file, filesize($filename));
                    fclose($file);
                    return $data;
                }
                catch (Exception $e) {
                    Kohana::$log->add(Log::ERROR,$e->getMessage());
                    return FALSE;
                }
            }
        }
        return FALSE;   
    }

    /**
     * replaces in a file 
     * @param  string $orig_file 
     * @param  array $search   
     * @param  array $replace  
     * @return bool           
     */
    public static function replace_file($orig_file,$search, $replace,$to_file = NULL)
    {
        if ($to_file === NULL)
            $to_file = $orig_file;

        //check file is writable
        // if (is_writable($to_file))
        // {
            //read file content
            $content = file_get_contents($orig_file);
            //replace fields
            $content = str_replace($search, $replace, $content);
            //save file
            return file::write($to_file,$content);
        // }
        // return FALSE;
    }

}
