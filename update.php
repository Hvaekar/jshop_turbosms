<?php
	defined('_JEXEC') or die('Restricted access');

	$name = 'JoomShopping addon - Send SMS for status change';
	$type = 'plugin';
	$element = 'sms_status_change';
	$folders = array('jshoppingorder', 'jshoppingadmin', 'jshoppingcheckout');
	$version = '1.0.0';
	$cache = '{"creationDate":"1.11.2023","author":"Hvaekar","authorEmail":"hvaekar@gmail.com","authorUrl":"https://github.com/Hvaekar","version":"'.$version.'"}';
	$params = '{}';

	$db = \JFactory::getDbo();
	$model_langs = \JSFactory::getModel('Languages', 'JshoppingModel');
	$languages = $model_langs->getAllLanguages(1);	
	$i=0;
	foreach($folders as $folder){
		$db->setQuery("SELECT `extension_id` FROM `#__extensions` WHERE `element`='".$element."' AND `folder`='".$folder."'");
		$id = $db->loadResult();
		if(!$id) {
			$query = "INSERT INTO `#__extensions`(`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`) VALUES
			('".$name."', '".$type."', '".$element."', '".$folder."', 0, 1, 1, 0,'".addslashes($cache)."','".addslashes($params)."')";
			
			if ($i == 0) {
				$db->setQuery("ALTER TABLE `#__jshopping_order_status` ADD COLUMN `send_sms` BOOLEAN NOT NULL DEFAULT 0");
				$db->execute();
				
				foreach($languages as $lang) {
					$db->setQuery("ALTER TABLE `#__jshopping_order_status` ADD COLUMN `sms_text_".$lang->language."` TEXT");
					$db->execute();
				}
			}
			$i++;
		} else {
			$query = "UPDATE `#__extensions` SET `name`='".$name."', `manifest_cache`='".addslashes($cache)."', `params`='".addslashes($params)."' WHERE `extension_id`=".$id;
		}
		$db->setQuery($query);
		$db->execute();
	}

	$addon = \JSFactory::getTable('addon', 'jshop');
	$addon->loadAlias($element);
	$addon->set('name', $name);
	$addon->set('version', $version);
	$addon->set('uninstall', '/components/com_jshopping/addons/'.$element.'/uninstall.php');
	$addon->store();