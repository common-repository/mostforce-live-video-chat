<?php

require_once('MostForceHelper.class.php');

class ChangesSavedHelper extends MostForceHelper
{
	public function render()
	{
		if (MostForce::get_instance()->changes_saved())
		{
			return '<div id="changes_saved_info" class="updated installed_ok"><p>Advanced settings saved successfully.</p></div>';
		}

		return '';
	}
}