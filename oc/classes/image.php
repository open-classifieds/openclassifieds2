<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Image manipulation support.
 *
 * @package    Kohana/Image
 * @category   Base
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */
abstract class Image extends Kohana_Image {

    /**
    * Correct image orientation according to Exif data
    *
    * @return  $this
    * @uses    Image::flip, Image::rotate
    */
    public function orientate()
    {
        if (function_exists('exif_read_data') AND in_array(exif_imagetype($this->file), array(IMAGETYPE_JPEG,IMAGETYPE_TIFF_II,IMAGETYPE_TIFF_MM)))
        {
            $exif = @exif_read_data($this->file);

            $exif_orientation = isset($exif['Orientation'])?$exif['Orientation']:0;

            $rotate = 0;
            $flip = FALSE;

            switch($exif_orientation) {
                case 1:
                    $rotate = 0;
                    $flip = FALSE;
                break;

                case 2:
                    $rotate = 0;
                    $flip = TRUE;
                break;

                case 3:
                    $rotate = 0;

                    // Fix rotation for Safari iOS <= 12
                    if (Request::user_agent('browser') === 'Safari')
                    {
                        $version = preg_replace("/(.*) OS ([0-9]*)_(.*)/","$2", Request::$user_agent);

                        if ((int) $version < 13)
                        {
                            $rotate = 180;
                        }
                    }

                    $flip = FALSE;
                break;

                case 4:
                    $rotate = 180;
                    $flip = TRUE;
                break;

                case 5:
                    $rotate = 270;
                    $flip = TRUE;
                break;

                case 6:
                    $rotate = 180;

                    // Fix rotation for Safari iOS <= 12
                    if (Request::user_agent('browser') === 'Safari')
                    {
                        $version = preg_replace("/(.*) OS ([0-9]*)_(.*)/","$2", Request::$user_agent);

                        if ((int) $version < 13)
                        {
                            $rotate = 270;
                        }
                    }

                    $flip = FALSE;
                break;

                case 7:
                    $rotate = 90;
                    $flip = TRUE;
                break;

                case 8:
                    $rotate = 180;

                    // Fix rotation for Safari iOS <= 12
                    if (Request::user_agent('browser') === 'Safari')
                    {
                        $version = preg_replace("/(.*) OS ([0-9]*)_(.*)/","$2", Request::$user_agent);

                        if ((int) $version < 13)
                        {
                            $rotate = 90;
                        }
                    }

                    $flip = FALSE;
                break;
            }

            if ($flip)
                $this->flip(Image::HORIZONTAL);

            if ($rotate > 0)
                $this->rotate($rotate);
        }

        //default return the object so we can concatenate
        return $this;

    }

    /**
    * Image nudity detector based on flesh color quantity.
    * Source: http://www.naun.org/multimedia/NAUN/computers/20-462.pdf
    *
    * @param string $threshold Threshold of flesh color in image to consider in pornographic. See page 302
    * @return boolean
    */

    public function is_nude_image($threshold = .5) {

        // Get the width, height and type from the uploaded image
        list($width, $height, $type) = getimagesize($this->file);

        // Cannot get image size, cannot validate
        if (empty($width) OR empty($height))
            return FALSE;

        switch($type) {
            case IMAGETYPE_JPEG:
                $resource = imagecreatefromjpeg($this->file);
                break;
            case IMAGETYPE_GIF:
                $resource = imagecreatefromgif($this->file);
                break;
            case IMAGETYPE_PNG:
                $resource = imagecreatefrompng($this->file);
                break;
            default:
                throw new Exception(__('Image type is not supported'));
                break;
        }

        // Init vars
        $inc = 1; // Pixel count to iterate over. To increase speed, set it higher and it will skip some pixels.
        list($Cb1, $Cb2, $Cr1, $Cr2) = array(80, 120, 133, 173); // Cb and Cr value bounds. See page 300
        $white = 255; // Exclude white colors above this RGB color intensity
        $black = 5; // Exclude dark and black colors below this value

        $total = 0;
        $count = 0;

        for($x = 0; $x < $width; $x += $inc)
        for($y = 0; $y < $height; $y += $inc) {

        // Get color of a pixel
        $color = imagecolorat($resource, $x, $y);
        // RGB array of pixel's color
        $color = array(($color >> 16) & 0xFF, ($color >> 8) & 0xFF, $color & 0xFF);

        list($r, $g, $b) = $color;

        // Exclude white/black colors from calculation, presumably background
        if((($r > $white) && ($g > $white) && ($b > $white)) ||
        (($r < $black) && ($g < $black) && ($b < $black))) continue;

        // Converg pixel RGB color to YCbCr, coefficients already divided by 255
        $Cb = 128 + (-0.1482 * $r) + (-0.291 * $g) + (0.4392 * $b);
        $Cr = 128 + (0.4392 * $r) + (-0.3678 * $g) + (-0.0714 * $b);

        // Increase counter, if necessary
        if(($Cb >= $Cb1) && ($Cb <= $Cb2) && ($Cr >= $Cr1) && ($Cr <= $Cr2))
            $count++;
            $total++;
        }

        if ($total > 0)
            return ($count / $total) >= $threshold;

        return FALSE;

    }

} // End Image
