<?php
/*
 * Copyright 2005-2011 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 * 
 * This program is free software; you can redistribute it and/or modify it under 
 * the terms of the GNU General Public License as published by the Free Software 
 * Foundation ; either version 2 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with 
 * this program; if not, see <http://www.gnu.org/licenses>.
 * 
 * Linking this program statically or dynamically with other modules is making a 
 * combined work based on this program. Thus, the terms and conditions of the GNU 
 * General Public License cover the whole combination.
 * 
 * As a special exception, the copyright holders of this program give MERETHIS 
 * permission to link this program with independent modules to produce an executable, 
 * regardless of the license terms of these independent modules, and to copy and 
 * distribute the resulting executable under terms of MERETHIS choice, provided that 
 * MERETHIS also meet, for each linked independent module, the terms  and conditions 
 * of the license of that module. An independent module is a module which is not 
 * derived from this program. If you modify this program, you may extend this 
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 * 
 * For more information : contact@centreon.com
 * 
 * Project name : Centreon Syslog
 * Module name: Centreon-Syslog-Frontend
 * 
 * SVN : $URL$
 * SVN : $Id$
 * 
 */

	if (!isset($oreon)) {
		exit();
	}
	
	/*
	 * Path to the configuration dir
	 */
	$syslog_mod_path = $centreon_path . "www/modules/centreon-syslog-frontend/";
	$path = $syslog_mod_path . "include/configuration/configOpt/";
	
	/*
	 * Set language
	 */
	$locale = $oreon->user->get_lang();
	putenv("LANG=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages", $syslog_mod_path . "locale/");
	bind_textdomain_codeset("messages", "UTF-8");
	textdomain("messages");

	/*
	 * Pear library
	 */
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/advmultiselect.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	/*
	 * PHP functions
	 */
	require_once $path."DB-Func.php";
	require_once "./include/common/common-Func.php";

	/*
	 * Database retrieve information for Centreon-Syslog
	 */
	$cfg_syslog = getRefreshOption();

	/*
	 * Var information to format the element
	 */
	$attrsText = array("size"=>"5");

	/*
	 * Form begin
	 */
	$form = new HTML_QuickForm('Form', 'post', "?p=".$p);
	$form->addElement('header', 'title', _("Refresh Options"));

	# Database information
	$form->addElement('text', 'refresh_monitoring', _("Refresh Interval for monitoring"), $attrsText);
	$form->addElement('text', 'refresh_filters', _("Refresh Interval for filters"), $attrsText);

	$redirect =& $form->addElement('hidden', 'o');
	$redirect->setValue($o);

	/*
	 * Form Rules
	 */	
	$form->applyFilter('_ALL_', 'trim');
	$form->addRule('refresh_monitoring', _("Required Field"), 'required');
	$form->addRule('refresh_filters', _("Required Field"), 'required');

	if (isset($cfg_syslog)) {
		$form->setDefaults($cfg_syslog);
	} else {
		$form->setDefaults(array(
		"refresh_monitoring "=>'10',
		"refresh_filters"=>'240'));
	}
	#End of form definition

	if ($form->validate()) {
		if ($form->getSubmitValue("submitC")) {
			updateRefreshOption();
		}
	}
	
	/*
	 * Smarty template Init
	 */
	$tpl = new Smarty();
	$tpl = initSmartyTpl($syslog_mod_path . "include/administration", $tpl);

	/*
	 * Add buttons
	 */
	if ($o == "m") {
		$subC =& $form->addElement('submit', 'submitC', _("Save"), array("class" => "btc bt_success"));
		$res =& $form->addElement('reset', 'reset', _("Reset"), array("class" => "btc bt_default"));
		$redirect->setValue("f");
	} else if ($o == "f") {
		$form->freeze();
		$form->addElement('button', 'modify', _("Modify"), array("class" => "btc bt_default", "onClick" => "javascript:window.location.href='?p=".$p."&o=m'"));
		
	}
	
	/*
	 * Apply a template definition
	 */
	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$renderer->setRequiredTemplate('{$label}&nbsp;<font color="red" size="1">*</font>');
	$renderer->setErrorTemplate('<font color="red">{$error}</font><br />{$html}');
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->assign('o', $o);
	$tpl->display($path . "refresh.ihtml");
 ?>
