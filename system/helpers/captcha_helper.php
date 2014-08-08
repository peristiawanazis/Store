<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter CAPTCHA Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Create CAPTCHA
 *
 * @access	public
 * @param	array	array of data for the CAPTCHA
 * @param	string	path to create the image in
 * @param	string	URL to the CAPTCHA image folder
 * @param	string	server path to font
 * @return	string
 */
function create_customcaptcha() {
    @session_start();
//Let's generate a totally random string using md5
    $md5_hash = md5(rand(0,999));
//We don't need a 32 character long string so we trim it down to 5
    $security_code = substr($md5_hash, 15, 5);
    $security_code = '11';
//Set the session to store the security code
    $_SESSION["security_code"] = $security_code;
//Set the image width and height
    $width = 100;
    $height = 20;

//Create the image resource
    $image = ImageCreate($width, $height);

//We are making three colors, white, black and gray
    $white = ImageColorAllocate($image, 255, 255, 255);
    $black = ImageColorAllocate($image, 0, 0, 0);
    $grey = ImageColorAllocate($image, 204, 204, 204);

//Make the background black
    ImageFill($image, 0, 0, $black);

//Add randomly generated string in white to the image
    ImageString($image, 3, 30, 3, $security_code, $white);

//Throw in some lines to make it a little bit harder for any bots to break
    ImageRectangle($image,0,0,$width-1,$height-1,$grey);
    imageline($image, 0, $height/2, $width, $height/2, $grey);
    imageline($image, $width/2, 0, $width/2, $height, $grey);
//Tell the browser what kind of file is come in
    header("Content-Type: image/jpeg");
//Output the newly created image in jpeg format
    ImageJpeg($image);
//Free up resources
    ImageDestroy($image);
}
if ( ! function_exists('create_captcha')) {
    function create_captcha($data = '', $img_path = '', $img_url = '', $font_path = '') {
        $defaults = array('word' => '', 'img_path' => '', 'img_url' => '', 'img_width' => '150', 'img_height' => '30', 'font_path' => '', 'expiration' => 7200);

        foreach ($defaults as $key => $val) {
            if ( ! is_array($data)) {
                if ( ! isset($$key) OR $$key == '') {
                    $$key = $val;
                }
            }
            else {
                $$key = ( ! isset($data[$key])) ? $val : $data[$key];
            }
        }
        if ($img_path == '' OR $img_url == '') {
            return FALSE;
        }

        if ( ! @is_dir($img_path)) {
            return FALSE;
        }
        if ( ! is_writable($img_path)) {
            return FALSE;
        }
        if ( ! extension_loaded('gd')) {
            return FALSE;
        }
        // -----------------------------------
        // Remove old images
        // -----------------------------------

        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec);

        $current_dir = @opendir($img_path);

        while($filename = @readdir($current_dir)) {
            if ($filename != "." and $filename != ".." and $filename != "index.html") {
                $name = str_replace(".jpg", "", $filename);

                if (($name + $expiration) < $now) {
                    @unlink($img_path.$filename);
                }
            }
        }

        @closedir($current_dir);

        // -----------------------------------
        // Do we have a "word" yet?
        // -----------------------------------

        if ($word == '') {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $str = '';
            for ($i = 0; $i < 8; $i++) {
                $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
            }

            $word = $str;
        }

        // -----------------------------------
        // Determine angle and position
        // -----------------------------------

        $length	= strlen($word);
        $angle	= ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
        $x_axis	= rand(6, (360/$length)-16);
        $y_axis = ($angle >= 0 ) ? rand($img_height, $img_width) : rand(6, $img_height);

        // -----------------------------------
        // Create image
        // -----------------------------------

        // PHP.net recommends imagecreatetruecolor(), but it isn't always available
        if (function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($img_width, $img_height);
        }
        else {
            $im = imagecreate($img_width, $img_height);
        }

        // -----------------------------------
        //  Assign colors
        // -----------------------------------
        $bg_color		= imagecolorallocate ($im, 255, 255, 255);
        $border_color	= imagecolorallocate ($im, 153, 102, 102);
        $text_color		= imagecolorallocate ($im, 204, 153, 153);
        $grid_color		= imagecolorallocate($im, 255, 182, 182);
        $shadow_color	= imagecolorallocate($im, 255, 240, 240);

        // -----------------------------------
        //  Create the rectangle
        // -----------------------------------
        ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);

        // -----------------------------------
        //  Create the spiral pattern
        // -----------------------------------

        $theta		= 1;
        $thetac		= 7;
        $radius		= 16;
        $circles	= 20;
        $points		= 32;

        for ($i = 0; $i < ($circles * $points) - 1; $i++) {
            $theta = $theta + $thetac;
            $rad = $radius * ($i / $points );
            $x = ($rad * cos($theta)) + $x_axis;
            $y = ($rad * sin($theta)) + $y_axis;
            $theta = $theta + $thetac;
            $rad1 = $radius * (($i + 1) / $points);
            $x1 = ($rad1 * cos($theta)) + $x_axis;
            $y1 = ($rad1 * sin($theta )) + $y_axis;
            imageline($im, $x, $y, $x1, $y1, $grid_color);
            $theta = $theta - $thetac;
        }
        // -----------------------------------
        //  Write the text
        // -----------------------------------

        $use_font = ($font_path != '' AND file_exists($font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;

        if ($use_font == FALSE) {
            $font_size = 5;
            $x = rand(0, $img_width/($length/3));
            $y = 0;
        } else {
            $font_size	= 16;
            $x = rand(0, $img_width/($length/1.5));
            $y = $font_size+2;
        }
        for ($i = 0; $i < strlen($word); $i++) {
            if ($use_font == FALSE) {
                $y = rand(0 , $img_height/2);
                imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
                $x += ($font_size*2);
            }
            else {
                $y = rand($img_height/2, $img_height-3);
                imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
                $x += $font_size;
            }
        }


        // -----------------------------------
        //  Create the border
        // -----------------------------------

        imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color);

        // -----------------------------------
        //  Generate the image
        // -----------------------------------

        $img_name = $now.'.jpg';

        ImageJPEG($im, $img_path.$img_name);

        $img = "<img src=\"$img_url$img_name\" width=\"$img_width\" height=\"$img_height\" style=\"border:0;\" alt=\" \" />";

        ImageDestroy($im);

        return array('word' => $word, 'time' => $now, 'image' => $img);
    }
}

// ------------------------------------------------------------------------

/* End of file captcha_helper.php */
/* Location: ./system/heleprs/captcha_helper.php */