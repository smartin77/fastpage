<?php

if (function_exists('mb_internal_encoding'))
{	mb_internal_encoding('UTF-8');	}

//		SESSION INITIALIZE			//
session_start();

//		PARAMS & VARIABLES		//

// [system settings] //
$_CFG['sys']['stf_ext'] = '.htt';               // content files extension
$_CFG['sys']['css_src'] = 'css/';               // css style folder
$_CFG['sys']['js_src'] = 'js/';                	// javascript folder
$_CFG['sys']['lib_src'] = './fastpage/lib/';    // library folder
$_CFG['sys']['fn_src'] = './functions/';        // functions folder
$_CFG['sys']['tpl_src'] = './tpl/';                // template folder
$_CFG['sys']['tpl_img_src'] = $_CFG['sys']['tpl_src'] . 'gfx/';        // layout images folder
$_CFG['sys']['content_src'] = 'content/';          // content folder with subfolders for entries
$_CFG['sys']['static_src'] = 'static/';            // folder for static content - text files
// can be added also DB support for dynamic content such as article
$_CFG['sys']['img_src'] = 'img/';               // image folder - content_src/obj_name/img_src
$_CFG['sys']['file_src'] = 'file/';             // file folder - content_src/obj_name/file_src
$_CFG['sys']['media_src'] = 'media/';           // media folde - content_src/obj_name/media_src

// using common time format for all localizations yet
$_CFG['sys']['timeformat1'] = 'j. n. Y';
$_CFG['sys']['timeformat2'] = 'j. n. Y - H:i';

$_CFG['sys']['br'] = "<br />\r\n";            	// BR string - you can use custom BR string
$_CFG['sys']['history'] = 5;                  	// history of this site - last $_CFG['sys']['history'] urls

$_CFG['sys']['default_lang_id'] = 3;			// default language id (from locale array below)

$_CFG['sys']['emlweb'] = 'this@web.com';        // email address of this site (sender)
$_CFG['sys']['emladmin'] = '';                	// admin email - for debugging purpose
$_CFG['sys']['emlrecipients'][] = '';          	// list of recipients if required

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
	//HTTPS
	$_CFG['sys']['protocol'] = "https";
} else {
	$_CFG['sys']['protocol'] = "http";
}

if ($_SERVER['SERVER_NAME'] != 'localhost')
{
	$_CFG['base'] = '';
	$_CFG['basehref'] = $_CFG['sys']['protocol'] .'://'. $_SERVER['HTTP_HOST'] .'/';
}
else
{
	$_CFG['base'] = '/'. array_pop((explode("\\", dirname(__FILE__))));
	$_CFG['basehref'] = $_CFG['sys']['protocol'] .'://localhost'. $_CFG['base'] .'/';
}


// [static] //
$_CFG['static']['homepage'] = '_homepage';    	// home page - default filename


// localization settings
$locale[1]['code'] = "en";
$locale[1]['locale'] = "en_US";
$locale[1]['name'] = "English";
$locale[2]['code'] = "de";
$locale[2]['locale'] = "de_DE";
$locale[2]['name'] = "Deutsh";
$locale[3]['code'] = "sk";
$locale[3]['locale'] = "sk_SK";
$locale[3]['name'] = "Slovenský";			// localized language name

require_once('_config_messages.inc.php');	// status messages translation

//		LOAD COMMON LIBRARIES
require_once($_CFG['sys']['lib_src'] . 'class_fasttemplate.php');

// set language
if (isset($_GET['lang'])) {
	$_CFG['sys']['lang'] = $_GET['lang'];
} else {
	if (isset($_SESSION['language']))
	{
		$_CFG['sys']['lang'] = $_SESSION['language'];
	} else {
		$_CFG['sys']['lang'] = $locale[$_CFG['sys']['default_lang_id']]['code'];
	}
}

$_SESSION['language'] = $_CFG['sys']['lang'];			// add to session or refresh

foreach ($locale as $key=>$val) {
	//print_r($val);
	if ($val['code'] == $_CFG['sys']['lang']) {
		$_CFG['sys']['lang_id'] = $key;
	}
}

// get localization code
if (!isset($_CFG['sys']['lang_id'])){
	$_CFG['sys']['lang_id'] = $_CFG['sys']['default_lang_id'];
}

?>
