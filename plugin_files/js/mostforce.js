(function($)
{
var MostForce =
{
	init: function()
	{
		this.externalLinks();
		this.resetLink();
		this.toggleForms();
		this.alreadyHaveAccountForm();
		this.newLicenseForm();
		this.controlPanelIframe();
		this.fadeChangesSaved();
		this.showAdvancedSettings();
	},

	externalLinks: function()
	{
		$('a.help').attr('target', '_blank');
	},

	resetLink: function()
	{
		$('#reset_settings_mostforce a').click(function()
		{
			return confirm('This will reset your MostForce plugin settings. Continue?');
		})
	},

	toggleForms: function()
	{
		var toggleForms = function()
		{
			// display account details page if license number is already known
			if ($('#choice_account').length == 0 || $('#choice_account_1').is(':checked'))
			{
				$('#mostforce_new_account').hide();
				$('#mostforce_already_have').show();
				$('#mostforce_login').focus();
			}
			else if ($('#choice_account_0').is(':checked'))
			{
				$('#mostforce_already_have').hide();
				$('#mostforce_new_account').show();

				if ($.trim($('#name').val()).length == 0)
				{
					$('#name').focus();
				}
				else
				{			
					$('#password').focus();
				}
			}
		};

		toggleForms();
		$('#choice_account input').click(toggleForms);
	},

	alreadyHaveAccountForm: function()
	{
		$('#mostforce_already_have form').submit(function()
		{
			if (parseInt($('#license_number').val()) == 0)
			{
				var login = $.trim($('#mostforce_login').val());
				if (!login.length)
				{
					$('#mostforce_login').focus();
					return false;
				}
				//alert(login);
				$('#mostforce_already_have .ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');
				$.getJSON('https://www.mostforce.com/plusGetLicenseInfo?type=getmsg&email='+$('#mostforce_login').val()+'&jsonpcallback=?', function(response)
				{
					if (response.error)
					{
						$('#mostforce_already_have .ajax_message').removeClass('wait').addClass('message').html('Incorrect MostForce login.');
						$('#mostforce_login').focus();
						return false;
					}
					else
					{
						$('#license_number').val(response.number);
						$('#mostforce_already_have form').submit();
					}
				});

				return false;
			}
		});		
	},

	newLicenseForm: function()
	{
		$('#mostforce_new_account form').submit(function()
		{
			
			if (parseInt($('#new_license_number').val()) > 0)
			{
				return true;
			}
			
			if (MostForce.validateNewLicenseForm())
			{
				$('#mostforce_new_account .ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');

				// Check if email address is available
				$.getJSON('https://www.mostforce.com/plusGetLicenseInfo?type=getcannew&email='+$('#MostForce_email').val()+'&jsonpcallback=?',
				function(response)
				{		
					
					if (response.res == '1')
					{
						MostForce.createLicense();
					}
					else if (response.res == '-1')
					{
						$('#mostforce_new_account .ajax_message').removeClass('wait').addClass('message').html('This email address is already in use. Please choose another e-mail address.');
					}
					else
					{
						$('#mostforce_new_account .ajax_message').removeClass('wait').addClass('message').html('Could not create account. Please try again later.');
					}
				});
			}

			return false;
		});
	},

	createLicense: function()
	{
		var url;
		$('#mostforce_new_account .ajax_message').removeClass('message').addClass('wait').html('Creating new account&hellip;');
		url = 'https://www.mostforce.com/plusGetLicenseInfo';
		url += '?name='+encodeURI($('#MostForce_name').val());
		url += '&email='+encodeURI($('#MostForce_email').val());
		url += '&password='+encodeURI($('#MostForce_password').val());
		url += '&MostFoce_website='+encodeURI($('#MostFoce_website').val());
		url += '&timezone_gmt='+encodeURI(this.calculateGMT());
		url += '&type=wordpress_signup';
		url += '&jsonpcallback=?';
		$.getJSON(url, function(data)
		{
			data = parseInt(data.res);
			if (data <1)
			{
				$('#mostforce_new_account .ajax_message').html('Could not create account. Please try again later.').addClass('message').removeClass('wait');
				return false;
			}
			// save new licence number
			$('#new_license_number').val(data);
			$('#save_new_license').submit();
		});
	},

	validateNewLicenseForm: function()
	{
		if ($('#MostForce_name').val().length < 1)
		{
			alert ('Please enter your name.');
			$('#MostForce_name').focus();
			return false;
		}

		if (/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i.test($('#MostForce_email').val()) == false)
		{
			alert ('Please enter a valid email address.');
			$('#MostForce_email').focus();
			return false;
		}

		if ($.trim($('#MostForce_password').val()).length < 6)
		{
			alert('Password must be at least 6 characters long');
			$('#MostForce_password').focus();
			return false;
		}

		if ($('#MostForce_password').val() !== $('#MostForce_password_retype').val())
		{
			alert('Both passwords do not match.');
			$('#MostForce_password').val('');
			$('#MostForce_password_retype').val('');
			$('#MostForce_password').focus();
			return false;
		}

		return true;
	},

	calculateGMT: function()
	{
		var date, dateGMTString, date2, gmt;

		date = new Date((new Date()).getFullYear(), 0, 1, 0, 0, 0, 0);
		dateGMTString = date.toGMTString();
		date2 = new Date(dateGMTString.substring(0, dateGMTString.lastIndexOf(" ")-1));
		gmt = ((date - date2) / (1000 * 60 * 60)).toString();

		return gmt;
	},

	controlPanelIframe: function()
	{
		var cp = $('#control_panel');
		if (cp.length)
		{
			var cp_resize = function()
			{
				var cp_height = window.innerHeight ? window.innerHeight : $(window).height();
				cp_height -= $('#wphead').height();
				cp_height -= $('#updated-nag').height();
				cp_height -= $('#control_panel + p').height();
				cp_height -= $('#footer').height();
				cp_height -= 70;

				cp.attr('height', cp_height);
			}
			cp_resize();
			$(window).resize(cp_resize);
		}
	},

	fadeChangesSaved: function()
	{
		$cs = $('#changes_saved_info');

		if ($cs.length)
		{
			setTimeout(function()
			{
				$cs.slideUp();
			}, 1000);
		}
	},

	showAdvancedSettings: function()
	{
		$('#advanced-link a').click(function()
		{
			if ($('#advanced').is(':visible'))
			{
				$(this).html('Show advanced settings&hellip;');
				$('#advanced').slideUp();
			}
			else
			{
				$(this).html('Hide advanced settings&hellip;');
				$('#advanced').slideDown();
			}

			return false;
		})
	}
};

$(document).ready(function()
{
	MostForce.init();
});
})(jQuery);