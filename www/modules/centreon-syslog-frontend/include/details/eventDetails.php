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
 * SVN : $URL:$
 * SVN : $Id:$
 * 
 */	

	include ("/etc/centreon/centreon.conf.php");

	require_once $centreon_path . "www/modules/centreon-syslog-frontend/include/common/header.php";
	
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
	require 'HTML/QuickForm.php';
	require 'HTML/QuickForm/advmultiselect.php';
	require 'HTML/QuickForm/Renderer/ArraySmarty.php';
	
	/*
	 * Common functions
	 */
	require_once $syslog_mod_path . 'class/syslogDB.class.php';
	require_once $syslog_mod_path . 'include/common/common-Func.php';
	require_once $centreon_path . "www/include/common/common-Func.php";
	
	/*
	 * Build PEAR DB object
	 */
	if (isset($_GET['collector']) && $_GET['collector'] != "" )
		$pearSyslogDB = new SyslogDB("syslog", $_GET['collector']);
	else
		exit(1);
	
	/*
	 * Database retrieve information for Centreon-Syslog
	 */
	$cfg_syslog = getSyslogOption($_GET['collector']);
	
	/*
	* Get filters
	*/
	$filters = array();
	if (isset($_GET['host']) && $_GET['host'] != "" )
		$filters['host'] = htmlentities($_GET['host'] , ENT_QUOTES);
	if (isset($_GET['facility']) && $_GET['facility'] != "" )
		$filters['facility'] = htmlentities($_GET['facility'] , ENT_QUOTES);
	if (isset($_GET['priority']) && $_GET['priority'] != "" )
		$filters['priority'] = htmlentities($_GET['priority'] , ENT_QUOTES);
	if (isset($_GET['datetime']) && $_GET['datetime'] != "" )
		$filters['datetime'] = htmlentities($_GET['datetime'] , ENT_QUOTES);
	if (isset($_GET['program']) && $_GET['program'] != "" )
		$filters['program'] = htmlentities($_GET['program'] , ENT_QUOTES);
	
	$eventDetails = getEvent($pearSyslogDB, $cfg_syslog, $filters);
	
	$attrsText = array("size"=>"100%");
	
	$form = new HTML_QuickForm('Form');
	$form->addElement('header', 'title', _("Details of event"));
	$form->addElement('text', 'host', _("Host"), $attrsText);
	$form->addElement('text', 'datetime', _("Date / Time"), $attrsText);
	$form->addElement('text', 'facility', _("Facility"), $attrsText);
	$form->addElement('text', 'priority', _("Severity"), $attrsText);
	$form->addElement('text', 'tag', _("Tag"), $attrsText);
	$form->addElement('text', 'program', _("Program"), $attrsText);
	$form->addElement('text', 'msg', _("Message"), $attrsText);
	$form->addElement('text', 'seq', _("Sequence"), $attrsText);
	$form->addElement('text', 'counter', _("Compteur"), $attrsText);
	$form->addElement('text', 'fo', _("FO"), $attrsText);
	$form->addElement('text', 'lo', _("LO"), $attrsText);
	$form->addElement("button", "back", _("Back"), array("class" => "btc bt_default", "onClick" => "javascript:history.back()"));

	$form->setDefaults($eventDetails);
	$form->freeze();
	
	# Smarty template Init
	$tpl = new Smarty();
	$path = $syslog_mod_path . "include/details/";
	$tpl = initSmartyTpl($path, $tpl);
	
	$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->display("eventDetails.ihtml");
?>
