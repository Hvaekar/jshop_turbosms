<?php
    defined('_JEXEC') or die('Restricted access');
    
    \JHTML::_('bootstrap.tooltip');
    \JSFactory::loadExtLanguageFile('addon_sms_status_change');
    \JFactory::getDocument()->addStyleDeclaration('.jshop_edit .controls { display: block; }');
    
    $params = (object) $this->params;
    $yes_no_options = array();
    $yes_no_options[] = \JHTML::_('select.option', '1', \JText::_('JYES'));
    $yes_no_options[] = \JHTML::_('select.option', '0', \JText::_('JNO'));
	
	$always_options = array();
    $always_options[] = \JHTML::_('select.option', '1', \JText::_('JYES'));
    $always_options[] = \JHTML::_('select.option', '0', _JSHOP_SMSSC_ALWAYS_SEND_CHECK);
	
	$model_langs = \JSFactory::getModel('Languages', 'JshoppingModel');
	$langs = $model_langs->getAllLanguages(1);
	$languages[] = \JHTML::_('select.option', '', _JSHOP_SMSSC_LANG_USE_ORDER_LANG);
	foreach($langs as $lang) {
		$languages[] = \JHTML::_('select.option', $lang->language, $lang->name);
	}
?>
<fieldset class="form-horizontal">
    <legend><?php echo \JText::_('JOPTIONS'); ?></legend>
    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip" title=""><?php echo _JSHOP_SMSSC_ACTIVE; ?></label>
        </div>
        <div class="controls">
            <?php echo \JHTML::_('select.genericlist', $yes_no_options, 'params[active]', 'class="chzn-color-state form-select" style="max-width:240px"', 'value', 'text', ( isset($params->active) ? $params->active : 1 ) ); ?>
        </div>
    </div>
	<div class="control-group">
        <div class="control-label">
            <label class="hasTooltip" title=""><?php echo _JSHOP_SMSSC_ALWAYS_SEND; ?></label>
        </div>
        <div class="controls">
            <?php echo \JHTML::_('select.genericlist', $always_options, 'params[always]', 'class="chzn-color-state form-select" style="max-width:240px"', 'value', 'text', ( isset($params->always) ? $params->always : 1 ) ); ?>
        </div>
    </div>
	
	<div class="control-group">
        <div class="control-label">
            <label class="hasTooltip" title=""><?php echo _JSHOP_SMSSC_LANG; ?></label>
        </div>
        <div class="controls">
            <?php echo \JHTML::_('select.genericlist', $languages, 'params[lang]', 'class="chzn-color-state form-select" style="max-width:240px"', 'value', 'text', ( isset($params->lang) ? $params->lang : 1 ) ); ?>
        </div>
    </div>
	
	<div class="control-group">
        <div class="control-label">
            <label class="hasTooltip" title=""><?php echo _JSHOP_SMSSC_TURBOSMS_LOGIN; ?></label>
        </div>
        <div class="controls">
            <input type="text" class="form-control" name="params[turbosms_login]" value="<?php echo empty($params->turbosms_login) ? '' : $params->turbosms_login; ?>">
        </div>            
    </div>
	<div class="control-group">
        <div class="control-label">
            <label class="hasTooltip" title=""><?php echo _JSHOP_SMSSC_TURBOSMS_PASSWORD; ?></label>
        </div>
        <div class="controls">
            <input type="password" autocomplete="off" class="form-control" name="params[turbosms_password]" value="<?php echo empty($params->turbosms_password) ? '' : $params->turbosms_password; ?>">
        </div>            
    </div>
	<div class="control-group">
        <div class="control-label">
            <label class="hasTooltip" title=""><?php echo _JSHOP_SMSSC_TURBOSMS_SENDER; ?></label>
        </div>
        <div class="controls">
            <input type="text" class="form-control" name="params[turbosms_sender]" value="<?php echo empty($params->turbosms_sender) ? '' : $params->turbosms_sender; ?>">
        </div>            
    </div>
	
	<div class="control-group" style="margin-top: 100px;">
        <?php echo _JSHOP_SMSSC_FOR_DONATE; ?>        
    </div>
</fieldset>