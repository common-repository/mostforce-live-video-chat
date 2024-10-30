<?php

class MostForce
{
	// singleton pattern
	protected static $instance;

	/**
	 * Absolute path to plugin files
	 */
	protected $plugin_url = null;

	/**
	 * MostForce license parameters
	 */
	protected $login = null;
	protected $license_number = null;
	protected $skill = null;

	/**
	 * Remembers if MostForce license number is set
	 */
	protected static $license_installed = false;

	/**
	 * Starts the plugin
	 */
	protected function __construct()
	{
		add_action ('wp_head', array($this, 'tracking_code'));
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
	 * Returns plugin files absolute path
	 *
	 * @return string
	 */
	public function get_plugin_url()
	{
		if (is_null($this->plugin_url))
		{
			$this->plugin_url = WP_PLUGIN_URL.'/mostforce-live-video-chat/plugin_files';
		}

		return $this->plugin_url;
	}

	/**
	 * Returns true if mostforce license is set properly,
	 * false otherwise
	 *
	 * @return bool
	 */
	public function is_installed()
	{
		//echo "get_license_number::::".$this->get_license_number();
		return ($this->get_license_number() > 0);
	}

	/**
	 * Returns mostfoce license number
	 *
	 * @return int
	 */
	public function get_license_number()
	{
		if (is_null($this->license_number))
		{
			$this->license_number = get_option('mostforce_license_number');
		}

		// license_number must be >= 0
		// also, this prevents from NaN values
		$this->license_number = max(0, $this->license_number);

		return $this->license_number;
	}

	/**
	 * Returns MostForce login
	 */
	public function get_login()
	{
		if (is_null($this->login))
		{
			$this->login = get_option('login');
		}

		return $this->login;
	}

	/**
	 * Returns MostForce skill number
	 *
	 * @return int
	 */
	public function get_skill()
	{
		if (is_null($this->skill))
		{
			$this->skill = (int)get_option('mostforce_groups');
		}

		// skill must be >= 0
		$this->skill = max(0, $this->skill);

		return $this->skill;
	}

	/**
	 * Injects tracking code
	 */
	public function tracking_code()
	{
		$this->get_helper('TrackingCode');
	}

	/**
	 * Echoes given helper
	 */
	public static function get_helper($class, $echo=true)
	{
		$class .= 'Helper';
	
		if (class_exists($class) == false)
		{
			$path = dirname(__FILE__).'/helpers/'.$class.'.class.php';
			if (file_exists($path) !== true)
			{
				return false;
			}

			require_once($path);
		}

		$c = new $class;

		if ($echo)
		{
			echo $c->render();
			return true;
		}
		else
		{
			return $c->render();
		}
	}
}