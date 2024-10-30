<?php
/*
Plugin Name: MostForce Live Video Chat
Plugin URI: http://www.mostforce.com/
Description: MostForce Live Video Chat for WordPress plugin that allow visitors establish live video chat in browser without download and plugin installation.
Author: MostForce
Author URI: http://www.mostforce.com
Version: 1.0.0
*/

if (is_admin())
{
	require_once(dirname(__FILE__).'/plugin_files/MostForceAdmin.class.php');
	MostForceAdmin::get_instance();
}
else
{
	require_once(dirname(__FILE__).'/plugin_files/MostForce.class.php');
	MostForce::get_instance();
}

?>