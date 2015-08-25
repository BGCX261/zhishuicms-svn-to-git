<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
include "../inc/config.inc.php";
function smarty_modifier_cut($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
	global $cfg;
    if ($length == 0)
        return '';
    $string=preg_replace('/<[^>]*?>/', '', $string);
    $string=preg_replace('/&nbsp;/', '', $string);
    $string=preg_replace('/&quot;/', '"', $string);
    $string=preg_replace('/&rdquo;/', '"', $string);
    $string=preg_replace('/&ldquo;/', '"', $string);
    $string=preg_replace('/&[a-z]*;/', ' ', $string);
    $string=preg_replace('/\s+/', '', $string);
    if (strlen($string) > $length) {
        $length -= strlen($etc);
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length+1,$cfg['mysql_charset']));
        }
        if(!$middle) {
            return mb_substr($string, 0, $length,$cfg['mysql_charset']).$etc;
        } else {
            return mb_substr($string, 0, $length/2,$cfg['mysql_charset']) . $etc . mb_substr($string, -$length/2,$cfg['mysql_charset']);
        }
    	return mb_substr($string, 0, $length,$cfg['mysql_charset']).$etc;
     } else {
        return $string;
    }
}

/* vim: set expandtab: */

?>
