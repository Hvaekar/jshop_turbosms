<?php
    defined('_JEXEC') or die('Restricted access');

    class plgJshoppingAdminSms_status_change extends JPlugin {
        
        private $_params;
		
		public function __construct(&$subject, $config) {
            parent::__construct($subject, $config);
            
            $addon = \JSFactory::getTable('addon', 'jshop');
            $addon->loadAlias('sms_status_change');
            $this->_params = (object) $addon->getParams();
        }

        public function onBeforeSaveOrderStatus(&$post) {
            $jinp = \JFactory::getApplication()->input;
            $model_langs = \JSFactory::getModel('Languages','JshoppingModel');
            $languages = $model_langs->getAllLanguages(1);
            foreach($languages as $lang) {
				$post['sms_text_'.$lang->language] = trim($post['sms_text_'.$lang->language]);
            }
        }
        
        public function onBeforeEditOrderStatus(&$view) {
			$order_status = $view->order_status;
			JSFactory::loadExtLanguageFile("addon_sms_status_change");
            $model_langs = \JSFactory::getModel('Languages', 'JshoppingModel');
            $languages = $model_langs->getAllLanguages(1);
			
			$html="";
			
			if( !isset($this->_params->active) || !empty($this->_params->active) ) {
				$html.="<tr><td>"._JSHOP_SMSSC_ACTIVE."</td><td><input type='hidden' name='send_sms' value='0'><input type = \"checkbox\" name = \"send_sms\" id = \"send_sms\" value = \"1\"";
				if($order_status->send_sms) $html.=' checked = "checked"';
				$html.="/></td></tr>";
			
				foreach($languages as $lang){
					$sms_text = 'sms_text_'.$lang->language;
					$html .= '<tr><td class="key">' . _JSHOP_SMSSC_TEXT . ' ('.$lang->lang.')</td><td><textarea name="'.$sms_text.'" class="form-control wide" rows="5">'.$order_status->$sms_text.'</textarea></td></tr>';
				}
			}
			
			$view->{'etemplatevar'} .= $html;
			
			$desc = '<tr><td colspan="2"><small><strong>'._JSHOP_SMSSC_VALID_REPLACERS.'</strong><br>';
			$desc .= _JSHOP_SMSSC_CLIENT.': {first_name}, {last_name}<br>';
			$desc .= _JSHOP_SMSSC_ORDER.': {order_number}, {order_number_without_zeros}, {order_status}, {comment}, {tracking_number}, {number}, {tracking_number_url}, {shipping}, {payment}, {order_total}, {shipping_price}, {payment_price}, {order_tax}, {currency_code}<br>';
			$desc .= _JSHOP_SMSSC_VENDOR.': {company}, {address}, {zip}, {city}, {country}, {phone}, {fax}</small></td></tr>';
			$view->{'etemplatevar'} .= $desc;
        }

    }