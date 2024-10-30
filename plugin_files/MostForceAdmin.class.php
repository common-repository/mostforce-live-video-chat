<?php

require_once('MostForce.class.php');

final class MostForceAdmin extends MostForce
{
	/**
	 * Plugin's version
	 */
	protected $plugin_version = null;

	/**
	 * Returns true if "Advanced settings" form has just been submitted,
	 * false otherwise
	 *
	 * @return bool
	 */
	protected $changes_saved = false;

	/**
	 * Starts the plugin
	 */
	protected function __construct()
	{
		parent::__construct();

		add_action('init', array($this, 'load_scripts'));
		add_action('admin_menu', array($this, 'admin_menu'));

		// tricky error reporting
		if (defined('WP_DEBUG') && WP_DEBUG == true)
		{
			add_action('init', array($this, 'error_reporting'));
		}

		if (isset($_GET['reset']) && $_GET['reset'] == '1')
		{
			$this->reset_options();
		}
		elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->update_options($_POST);
		}
	}

	public static function get_instance()
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	/**
	 * Set error reporting for debugging purposes
	 */
	public function error_reporting()
	{
		error_reporting(E_ALL & ~E_USER_NOTICE);
	}

	/**
	 * Returns this plugin's version
	 *
	 * @return string
	 */
	public function get_plugin_version()
	{
		if (is_null($this->plugin_version))
		{
			if (!function_exists('get_plugins'))
			{
				require_once(ABSPATH.'wp-admin/includes/plugin.php');
			}

			$plugin_folder = get_plugins('/'.plugin_basename(dirname(__FILE__).'/..'));
			
			$this->plugin_version = $plugin_folder['mostforce.php']['Version'];
			//print_r($plugin_folder);
			//echo "111:::".$plugin_folder['mostforce.php']['Version'];
		}
		//echo "plugin_version".$this->plugin_version;
		return $this->plugin_version;
	}

	public function load_scripts()
	{
		wp_enqueue_script('mostforce', $this->get_plugin_url().'/js/mostforce.js?v=21', 'jquery', $this->get_plugin_version(), true);
		wp_enqueue_style('mostforce', $this->get_plugin_url().'/css/mostforce.css', false, $this->get_plugin_version());
	}

	public function admin_menu()
	{
		add_menu_page(
			'MostForce',
			'MostForce',
			'administrator',
			'mostforce',
			array($this, 'mostforce_settings_page'),
			$this->get_plugin_url().'/images/favicon.ico'
		);

		add_submenu_page(
			'mostforce',
			'Settings',
			'Settings',
			'administrator',
			'mostforce_settings',
			array($this, 'mostforce_settings_page')
		);

		// remove the submenu that is automatically added
		if (function_exists('remove_submenu_page'))
		{
			remove_submenu_page('mostforce', 'mostforce');
		}

		// Settings link
		add_filter('plugin_action_links', array($this, 'mostforce_settings_link'), 10, 2);
	}

	/**
	 * Displays settings page
	 */
	public function mostforce_settings_page()
	{
		$this->get_helper('Settings');
	}

	public function changes_saved()
	{
		
		return $this->changes_saved;
	}

	public function mostforce_settings_link($links, $file)
	{
		if (basename($file) !== 'mostforce.php')
		{
			return $links;
		}

		$settings_link = sprintf('<a href="admin.php?page=mostforce_settings">%s</a>', __('Settings'));
		array_unshift ($links, $settings_link); 
		return $links;
	}

	protected function reset_options()
	{
		delete_option('mostforce_license_number');
		delete_option('mostforce_groups');
	}

	protected function update_options($data)
	{
		// check if we are handling MostForce settings form
		
		if (isset($data['settings_form']) == false && isset($data['new_license_form']) == false)
		{
			return false;
		}

		$license_number = isset($data['license_number']) ? $data['license_number'] : 0;
		//echo $license_number;
		$skill = isset($data['skill']) ? (int)$data['skill'] : 0;

		// skill must be >= 0
		$skill = max(0, $skill);

		echo $license_number;
		update_option('mostforce_license_number', $license_number);
		update_option('mostforce_groups', $skill);

		if (isset($data['changes_saved']) && $data['changes_saved'] == '1')
		{
			$this->changes_saved = true;
		
		}
	}
}