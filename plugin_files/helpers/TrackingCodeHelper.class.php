<?php

require_once('MostForceHelper.class.php');

class TrackingCodeHelper extends MostForceHelper
{
	public function render()
	{
		
		if (MostForce::get_instance()->is_installed())
		{
			$skill = MostForce::get_instance()->get_skill();
			$license_number = MostForce::get_instance()->get_license_number();
			
			return <<<HTML
<script type="text/javascript">
if(!__mostforce)
{
	var __mostforce = {}; 
	__mostforce.license ={$license_number};
	__mostforce.target  = 'https://www.mostforce.com/';
	(function(){
		var joinSC = document.createElement('script'); 
		joinSC.type = 'text/javascript'; 
		joinSC.async = true; 
		var joinDate    = new Date(); 
		var versonStr = joinDate.getFullYear().toString()+joinDate.getMonth().toString()+joinDate.getDate().toString(); 
		joinSC.src = __mostforce.target+'/widget/mostforce.js?mvJust='+versonStr+"2"; 
		var joinScript = document.getElementsByTagName('script')[0]; 
		joinScript.parentNode.insertBefore(joinSC, joinScript); 
	})(); 
}
</script>
HTML;
		}

		return '';
	}
}