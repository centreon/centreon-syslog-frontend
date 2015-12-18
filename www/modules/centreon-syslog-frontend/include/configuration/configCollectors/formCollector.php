<?php
/*
 * Copyright 2005-2010 MERETHIS
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

	if (!isset($oreon))
		exit();

	/*
	 * Database retrieve information for Poller
	 */
	$cfg_poller = array();
	if (($o == "c" || $o == "w") && $id) {
		$DBRESULT = $pearDB->query("SELECT * FROM `mod_syslog_collector` WHERE `collector_id` = '".$id."' LIMIT 1");
		# Set base value
		$cfg_poller = array_map("myDecode", $DBRESULT->fetchRow());
		$DBRESULT->free();
	}

	/*
	 * Var information to format the element
	 */
	$attrsText 		= array("size"=>"50");
	$attrsText2		= array("size"=>"20");
	$attrsText3		= array("size"=>"5");
	$attrsTextarea 	= array("rows"=>"5", "cols"=>"40");

	/*
	 *  Form begin
	 */
	$form = new HTML_QuickForm('Form', 'post', "?p=".$p."&id=".$id);
	if ($o == "a")
		$form->addElement('header', 'title', _("Add a Poller Configuration File"));
	else if ($o == "c")
		$form->addElement('header', 'title', _("Modify a Poller Configuration File"));
	else if ($o == "w")
		$form->addElement('header', 'title', _("View a Poller Configuration File"));

	/*
	 * Poller Configuration basic information
	*/
	# Database information
	$form->addElement('text', 'collector_name',	_("Collector name"), $attrsText);
	$form->addElement('text', 'db_server_address', _("IP or DNS name"), $attrsText);
	$form->addElement('text', 'db_server_port', _("Database port"), $attrsText);
	$form->addElement('text', 'db_type', _("Database type"), $attrsText);
	$form->addElement('text', 'db_name', _("Database name"), $attrsText);
	$form->addElement('text', 'db_username', _("Database user"), $attrsText2);
	$form->addElement('password', 'db_password', _("Password of Database user"), $attrsText2);
	$form->addElement('text', 'db_table_logs', _("Logs table name"), $attrsText);
	$form->addElement('text', 'db_table_logs_merge', _("Logs Merge table name"), $attrsText);
	$form->addElement('text', 'db_table_cache', _("Cache table name"), $attrsText);
	$form->addElement('text', 'db_table_cache_merge', _("Cache Merge table name"), $attrsText);

	# SSH information
	$form->addElement('text', 'ssh_server_address', _("IP or DNS name"), $attrsText);
	$form->addElement('text', 'ssh_username', _("Username for SSH connection"), $attrsText2);
	$form->addElement('password', 'ssh_password', _("Password for SSH connection"), $attrsText2);
	$form->addElement('text', 'ssh_server_port', _("SSH port"), $attrsText3);

	# Configuration
	$form->addElement('text', 'configuration_dir', _("Configuration directory"), $attrsText);
	$form->addElement('text', 'retention_days', _("Duration of retention of data"), $attrsText3);
	$form->addElement('textarea', 'comment', _("Comment"), $attrsTextarea);
	$Tab = array();
	$Tab[] = HTML_QuickForm::createElement('radio', 'enable', null, _("Enabled"), '1');
	$Tab[] = HTML_QuickForm::createElement('radio', 'enable', null, _("Disabled"), '0');
	$form->addGroup($Tab, 'enable', _("Status"), '&nbsp;');	

	if (isset($_GET["o"]) && $_GET["o"] == 'a') {
		$form->setDefaults(array(
		"collector_name" => '',
		"db_server_address" => '',
		"db_server_port" => '3306',
		"db_type" => 'mysql',
		"db_name"=>'centreon_syslog',
		"db_username"=>'syslogadmin',
		"db_password" => '',
		"db_table_logs"=>'logs',
		"db_table_logs_merge"=>'all_logs',
		"db_table_cache"=>'cache',
		"db_table_cache_merge"=>'all_cache',
		"ssh_server_address" => '',
		"ssh_username "=>'syslog',
		"ssh_password" =>'',
		"ssh_server_port" =>'22',
		"configuration_dir" =>'/etc/centreon-syslog',
		"retention_days" =>'31',
		"comment" =>'',
		"enable"=>'1'));
	} else {
		$form->setDefaults($cfg_poller);
	}
	
	$form->addElement('hidden', 'id');
	$redirect = $form->addElement('hidden', 'o');
	$redirect->setValue($o);

	/*
	 * Form Rules
	 */
	$form->applyFilter('_ALL_', 'trim');
	$form->addRule('collector_name', _("Required Field"), 'required');
	$form->addRule('collector_name', _("Name is already in use"), 'exist');
	$form->addRule('db_server_address', _("Required Field"), 'required');
	$form->addRule('db_server_port', _("Required Field"), 'required');
	$form->addRule('db_type', _("Required Field"), 'required');
	$form->addRule('db_name', _("Required Field"), 'required');
	$form->addRule('db_username', _("Required Field"), 'required');
	$form->addRule('db_password', _("Required Field"), 'required');
	$form->addRule('db_table_logs', _("Required Field"), 'required');
	$form->addRule('db_table_cache', _("Required Field"), 'required');

	/*
	 * Smarty template Init
	 */
	$tpl = new Smarty();
	$tpl = initSmartyTpl($path, $tpl);

	$tpl->assign('syslog_db_general', _("General DB information"));
	$tpl->assign('syslog_db_logs', _("Logs tables"));
	$tpl->assign('syslog_db_cache', _("Cache tables"));
	$tpl->assign('syslog_ssh', _("SSH information"));
	$tpl->assign('syslog_server_configuration', _("Configuration of syslog collector"));
	$tpl->assign('syslog_monitoring_configuration', _("Frontend configuration"));
	$tpl->assign('days', _("days"));
	$tpl->assign('seconds', _("seconds"));
	$tpl->assign('sort1', _("Database"));		
	$tpl->assign('sort2', _("SSH"));		
	$tpl->assign('sort3', _("Configuration"));

	if ($o == "w")	{
		/*
		 * Just watch a Poller information
		 */
		if ($centreon->user->access->page($p) != 2) {
			$form->addElement('button', 'change', _("Modify"), array("class" => "btc bt_default", "onClick"=>"javascript:window.location.href='?p=".$p."&o=c&id=".$id."'"));
			if (extension_loaded('ssh2')) {
				$form->addElement('button', 'export',  _("Export configuration"), array("class" => "btc bt_success", "id" => "ajaxBtn", "onClick" => "javascript:ajax_generate(".$id.");") );
			} else {
				print ("<div class='ssh2' align='center'>");
				print ("<br/>"._("Export button is hidden because SSH2 libraries for PHP was not installed."));
				print ("<br/>"._("Please refer to install guide for Centreon-Syslog-Frontend."));
				print ("<br/></div>");
			}
		}
		$form->setDefaults($cfg_poller);
		$form->freeze();
	} else if ($o == "c") {
		/*
		 * Modify a Poller information
		 */
		$subC = $form->addElement('submit', 'submitC', _("Save"), array("class" => "btc bt_success"));
		$res = $form->addElement('reset', 'reset', _("Reset"), array("class" => "btc bt_default"));
	    $form->setDefaults($cfg_poller);
	} else if ($o == "a") {
		/*
		 * Add a Poller information
		 */
		$subA = $form->addElement('submit', 'submitA', _("Save"), array("class" => "btc bt_success"));
		$res = $form->addElement('reset', 'reset', _("Reset"), array("class" => "btc bt_default"));
	}

	$valid = false;
	if ($form->validate())	{
		$pollerObj = $form->getElement('id');
		if ($form->getSubmitValue("submitA"))
			insertPollerInDB();
		else if ($form->getSubmitValue("submitC"))
			updatePollerInDB($id);
		$o = NULL;
		$valid = true;
	}
	
	if ($valid) {
		require_once($path."listCollectors.php");
	} else {
		/*
		 * Apply a template definition
		 */
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
		$renderer->setRequiredTemplate('{$label}&nbsp;<font color="red" size="1">*</font>');
		$renderer->setErrorTemplate('<font color="red">{$error}</font><br />{$html}');
		$form->accept($renderer);	
		$tpl->assign('form', $renderer->toArray());
		$tpl->assign('o', $o);
		$tpl->display("formCollector.ihtml");
	}
?>
