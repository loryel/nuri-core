<?php
/**
 * menuMobile class
 * mobile class of the menu module
 *
 * @Original_author NHN
 * @Adaptor NURI Project (developer@nuricms.org)
 * @package /modules/menu
 * @version 0.1
 */
class menuMobile extends moduleObject
{
	/**
	 * Result data list
	 * @var array
	 */
	var $result = array();

	/**
	 * Menu depth arrange
	 * @return void
	 */
	function straightenMenu($menu_item, $depth)
	{
		if(!$menu_item['link']) return;
		$obj->href = $menu_item['href'];
		$obj->depth = $depth;
		$obj->text = $menu_item['text'];
		$this->result[] = $obj;
		if(!$menu_item['list']) return;
		foreach($menu_item['list'] as $item)
		{
			$this->straightenMenu($item, $depth+1);
		}
	}

	/**
	 * Display menu
	 * @return void
	 */
	function dispMenuMenu()
	{
		$menu_srl = Context::get('menu_srl');
		$oAdminModel =& getAdminModel('menu');
		$menu_info = $oAdminModel->getMenu($menu_srl);

		if(!$menu_srl)
		{
			$oMenuAdminController = getAdminController('menu');
			$homeMenuCacheFile = $oMenuAdminController->getHomeMenuCacheFile();

			if(is_readable($homeMenuCacheFile))
			{
				include($homeMenuCacheFile);
			}
			$menu_info->php_file = './files/cache/menu/'.$homeMenuSrl.'.php';
		}

		if(is_readable($menu_info->php_file)) include($menu_info->php_file);
		if(is_array($menu->list))
		{
			foreach($menu->list as $menu_item)
			{
				$this->straightenMenu($menu_item, 0);
			}
		}

		Context::set('menu', $this->result);

		$this->setTemplatePath(sprintf("%stpl/",$this->module_path));
		$this->setTemplateFile('menu.html');
	}
}
/* End of file menu.mobile.php */
/* Location: ./modules/menu/menu.mobile.php */
