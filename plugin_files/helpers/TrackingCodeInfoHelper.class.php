<?php

require_once('MostForceHelper.class.php');

class TrackingCodeInfoHelper extends MostForceHelper
{
	public function render()
	{
		if (MostForce::get_instance()->is_installed())
		{
			return '<div class="updated installed_ok"><p>MostForce live chat is installed properly. Please recive letter from your email,and active your account.!</p></div>';
		}

		return '';
	}
}