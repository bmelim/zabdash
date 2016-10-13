<?php
 
require_once('config.php');
 
$locale = LANG;
$textdomain = "zabdash";
$locales_dir = dirname(__FILE__) . '/locales';
//$locales_dir = '/locales';
 
if (isset($_GET['locale']) && !empty($_GET['locale'])) {
  $locale = $_GET['locale']; }
 
putenv('LANGUAGE=' . $locale);
putenv('LANG=' . $locale);
putenv('LC_ALL=' . $locale);
putenv('LC_MESSAGES=' . $locale);
 
require_once('lib/gettext.inc');
 
_setlocale(LC_ALL, $locale);
_setlocale(LC_CTYPE, $locale);
 
_bindtextdomain($textdomain, $locales_dir);
_bind_textdomain_codeset($textdomain, 'UTF-8');
_textdomain($textdomain);
 
function _e($string) {
  echo __($string);
}
?>