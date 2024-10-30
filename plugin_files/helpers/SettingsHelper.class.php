<?php

require_once('MostForceHelper.class.php');

class SettingsHelper extends MostForceHelper
{
	public function render()
	{
?>
		<div id="mostforce">
		<div class="wrap">

		<div id="lc_logo">
			<img src="<?php echo MostForce::get_instance()->get_plugin_url(); ?>/images/logo.png" width="200px" height="70px" />
			<span>for Wordpress</span>
		</div>
		<div class="clear"></div> 

<?php
MostForce::get_instance()->get_helper('ChangesSaved');
MostForce::get_instance()->get_helper('TrackingCodeInfo');
?>	
		<?php if (MostForce::get_instance()->is_installed() == false) { ?>
		<div class="metabox-holder">
			<div class="postbox">
				<h3>Do you already have a MostForce account?</h3>
				<div class="postbox_content">
				<ul id="choice_account">
				<li><input type="radio" name="choice_account" id="choice_account_1" checked="checked"> <label for="choice_account_1">Yes, I already have a MostForce account</label></li>
				<li><input type="radio" name="choice_account" id="choice_account_0"> <label for="choice_account_0">No, I want to create one</label></li>
				</ul>
				</div>
			</div>
		</div>
		<?php } ?>

		<!-- Already have an account -->
		<div class="metabox-holder" id="mostforce_already_have" style="display:none">

			<?php if (MostForce::get_instance()->is_installed()): ?>
			<div class="postbox">
			<h3><?php echo _e('Sign in to MostForce'); ?></h3>
			<div class="postbox_content">
			<p><?php echo _e('Sign in to MostForce and start chatting with your customers!'); ?></p>
			<p><span class="btn"><a href="https://www.mostforce.com/dashboard/index.html" target="_blank"><?php _e('Sign in to web application'); ?></a></span></p>
			</div>
			</div>
			<?php endif; ?>
			<?php if (MostForce::get_instance()->is_installed() == false) { ?>
			<div class="postbox">
			<form method="post" action="?page=mostforce_settings">
				<h3>MostForce account</h3>
				<div class="postbox_content">
				<table class="form-table">
				<tr>
				<th scope="row"><label for="mostforce_login">My MostForce login is:</label></th>
				<td><input type="text" name="login" id="mostforce_login" value="<?php echo MostForce::get_instance()->get_login(); ?>" size="40" /></td>
				</tr>
				</table>

				<p class="ajax_message"></p>
				<p class="submit">
					<input type="hidden" name="license_number" value="<?php echo MostForce::get_instance()->get_license_number(); ?>" id="license_number">
					<input type="hidden" name="settings_form" value="1">
					<input type="submit" class="button-primary" value="<?php _e('Save changes') ?>" />
				</p>
				</div>
			</form>
			</div>
			<?php } else { ?>
			<!--div id="advanced" class="postbox" style="display:none">
			<form method="post" action="?page=mostforce_settings">
				<h3>Advanced settings</h3>
				<div class="postbox_content">
				<table class="form-table">
				<tr>
				<th scope="row"><label for="skill">Group:</label></th>
				<td><input type="text" name="skill" id="skill" value="<?php echo MostForce::get_instance()->get_skill(); ?>" /> <span class="explanation">Used for dividing chat agents into groups (<a href="http://www.mostforce.com/kb/dividing-live-chat-by-group/" target="_blank">read more</a>). Enter <strong>0</strong> for default group (recommended).</span></td>
				</tr>
				</table>
				<p class="submit">
				<input type="hidden" name="license_number" value="<?php echo MostForce::get_instance()->get_license_number(); ?>" id="license_number">
				<input type="hidden" name="changes_saved" value="1">
				<input type="hidden" name="settings_form" value="1">
				<input type="submit" class="button-primary" value="<?php _e('Save changes') ?>" />
				</p>
				</div>
			</form>
			</div>
			<p id="advanced-link"><a href="">Show advanced settings&hellip;</a></p-->
				<?php } ?>

			<?php if (MostForce::get_instance()->is_installed()) { ?>
			<p id="reset_settings_mostforce">Something went wrong? <a href="?page=mostforce_settings&amp;reset=1">Reset your settings</a>.</p>
			<?php } ?>
		</div>

		<!-- New account form -->
		<div class="metabox-holder" id="mostforce_new_account" style="display:none">
			<div class="postbox">
			<form method="post" action="?page=mostforce_settings">
				<h3>Create new MostForce account</h3>
				<div class="postbox_content">

				<?php
				global $current_user;
				get_currentuserinfo();

				$fullname = $current_user->user_firstname.' '.$current_user->user_lastname;
				$fullname = trim($fullname);
				?>
				<table class="form-table">
				<tr>
				<th scope="row"><label for="name">Full name:</label></th>
				<td><input type="text" name="MostForce_name" id="MostForce_name" maxlength="60" value="<?php echo $fullname; ?>" size="40" /></td> 
				</tr>
				<tr>
				<th scope="row"><label for="email">E-mail:</label></th>
				<td><input type="text" name="MostForce_email" id="MostForce_email" maxlength="100" value="<?php echo $current_user->user_email; ?>" size="40" /></td>
				</tr>
				<tr>
				<th scope="row"><label for="password">Password:</label></th>
				<td><input type="password" name="MostForce_password" id="MostForce_password" maxlength="100" value="" size="40" /></td>
				</tr>
				<tr>
				<th scope="row"><label for="password_retype">Retype password:</label></th>
				<td><input type="password" name="MostForce_password_retype" id="MostForce_password_retype" maxlength="100" value="" size="40" /></td>
				</tr>
				</table>

				<p class="ajax_message"></p>
				<p class="submit">
					<input type="hidden" name="MostFoce_website" value="<?php echo bloginfo('url'); ?>">
					<input type="submit" value="Create account" id="MostForce_submit" class="button-primary">
				</p>
				</div>
			</form>
			<form method="post" action="?page=mostforce_settings" id="save_new_license">
				<p>
				<input type="hidden" name="new_license_form" value="1">
				<input type="hidden" name="skill" value="0">
				<input type="hidden" name="license_number" value="0" id="new_license_number">
				</p>
			</form>
			</div>
		</div>
	</div>
	</div>
<?php
	}
}