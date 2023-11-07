<?php
    defined('_JEXEC') or die('Restricted access');
    
	$db = \JFactory::getDbo();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'sms_status_change' AND folder = 'jshoppingorder' AND `type` = 'plugin'");
	$db->execute();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'sms_status_change' AND folder = 'jshoppingadmin' AND `type` = 'plugin'");
	$db->execute();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'sms_status_change' AND folder = 'jshoppingcheckout' AND `type` = 'plugin'");
	$db->execute();
	
	$db->setQuery("ALTER TABLE `#__jshopping_order_status` DROP COLUMN `send_sms`");
	$db->execute();
	
	$model_langs = \JSFactory::getModel('Languages', 'JshoppingModel');
	$languages = $model_langs->getAllLanguages(1);	
	
	foreach($languages as $lang) {
		$db->setQuery("ALTER TABLE `#__jshopping_order_status` DROP COLUMN `sms_text_".$lang->language."`");
		$db->execute();
	}
	
	jimport('joomla.filesystem.folder');
	foreach(array(
        'components/com_jshopping/addons/sms_status_change/',
        'components/com_jshopping/lang/addon_sms_status_change/',
        'plugins/jshoppingadmin/sms_status_change/',
        'plugins/jshoppingorder/sms_status_change/',
		'plugins/jshoppingcheckout/sms_status_change/'
    ) as $folder){JFolder::delete(JPATH_ROOT.'/'.$folder);}