<?
class site_class
{
	function site_class()
	{
	}
	
	function get_site_info($site_id)
	{
		return sites_getSiteInfo($site_id);
	}

	function get_ipaygate_info($site_id)
	{
		$info = sites_getSiteInfo($site_id);
		$info = unserialize($info['cs_additional_info']);
		$ipay = array();
		
	}
}
?>