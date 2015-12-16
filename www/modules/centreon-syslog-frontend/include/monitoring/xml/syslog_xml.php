<?php
/*
 * Copyright 2005-2013 MERETHIS
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
 	# PHP functions
 	require_once "@CENTREON_ETC@centreon.conf.php";
 	require_once $centreon_path . "www/modules/centreon-syslog-frontend/include/common/header.php";
	require_once $centreon_path . "www/include/common/common-Func.php";

	require_once $syslog_mod_path . "include/common/common-Func.php";
	require_once $syslog_mod_path . "/class/syslogDB.class.php";
	require_once $syslog_mod_path . "/class/syslogXML.class.php";

	# Get language 
	$locale = $oreon->user->get_lang();
	putenv("LANG=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages",  $syslog_mod_path . "locale/");
	bind_textdomain_codeset("messages", "UTF-8"); 
	textdomain("messages");
	
	/*
	 * Get selected option in lists
	 */
	if (isset($_GET['collector_id']) && $_GET['collector_id'] != "")
		$collector_id = $_GET['collector_id'];
	else
		$collector_id = "";
		
	
	if (isset($_GET['Ffacility']) && $_GET['Ffacility'] != "" && $_GET['Ffacility'] != "undefined")
		$Ffacility_selected = $_GET['Ffacility'];
	else
		$Ffacility_selected = "";
			
	if (isset($_GET['Fseverity']) && $_GET['Fseverity'] != "" && $_GET['Fseverity'] != "undefined")
		$Fseverity_selected = $_GET['Fseverity'];
	else
		$Fseverity_selected = "";

	if (!preg_match('/^\d+$/', $collector_id)) {
	    header('Content-Type: text/xml');
    	header('Pragma: no-cache');
    	header('Expires: 0');
    	header('Cache-Control: no-cache, must-revalidate');
    	$buffer = new SyslogXML();
    	$buffer->startElement("error");
    	$buffer->writeElement("msg", _("Please select a collector."));
    	$buffer->endElement();
    	$buffer->output();
    	exit;
	}

	/*
	 * Build SQL request
	 */
	$pearDB = new syslogDB("centreon");
	$pearDB_syslog = new SyslogDB("syslog", $collector_id);
	$cfg_syslog = getSyslogOption($collector_id);
	
	$sql_filter = array();
	if (isset($_GET['program']) && $_GET['program'] != "" && $_GET['program'] != "undefined")
		array_push($sql_filter ," (program = '". htmlentities($_GET['program'] , ENT_QUOTES) ."') ");

	if (isset($_GET['host']) && $_GET['host'] != "" && $_GET['host'] != "undefined")
		array_push($sql_filter ," (host = '". htmlentities($_GET['host'] , ENT_QUOTES) ."')  ");
			
	if (isset($_GET['facility']) && $_GET['facility'] != "" && $_GET['facility'] != "undefined") {
		if ((strcmp($Ffacility_selected, "") == 0) || (strcmp($Ffacility_selected, "eq") == 0)) {
			array_push($sql_filter ," (facility = '". htmlentities($_GET['facility'] , ENT_QUOTES) ."') ");
		} else {
			$list_facilities = getListOfFacilities($_GET['facility'], $Ffacility_selected);
			$list = "";
			$listKeys = array_keys($list_facilities);
			foreach ($list_facilities as $key=>$value) {
				if (strcmp($list, "") != 0) {
					$list .= ",";
				}
				$list .= "'".$key."'";
			}
			array_push($sql_filter ," (facility IN (".$list.")) ");
		}
	}

	if (isset($_GET['severity']) && $_GET['severity'] != "" && $_GET['severity'] != "undefined") {
		if ((strcmp($Fseverity_selected, "") == 0) || (strcmp($Fseverity_selected, "eq") == 0)) {
			array_push($sql_filter ," (priority = '". htmlentities($_GET['severity'] , ENT_QUOTES) ."') ");
		} else {
			$list_priorities = getListOfSeverities($_GET['severity'], $Fseverity_selected);
			$list = "";
			$listKeys = array_keys($list_priorities);
			foreach ($list_priorities as $key=>$value) {
				if (strcmp($list, "") != 0) {
					$list .= ",";
				}
				$list .= "'".$key."'";
			}
			array_push($sql_filter ," (priority IN (".$list."))  ");
		}
	}
	
	if (isset($_GET['msg']) && $_GET['msg'] != "" && $_GET['msg'] != "undefined")
		array_push($sql_filter ," (msg LIKE '%". htmlentities($_GET['msg'] , ENT_QUOTES) ."%') ");

	$req_sql_filter = "";	
	if (isset( $sql_filter ))
		$req_sql_filter = join(" AND " , $sql_filter);

	if ($req_sql_filter != "")
		$req = "SELECT * FROM ".$cfg_syslog["db_table_logs"]." WHERE ".$req_sql_filter." ORDER BY datetime DESC, seq DESC LIMIT 50";			
	else
		$req = "SELECT * FROM ".$cfg_syslog["db_table_logs"]." ORDER BY datetime DESC, seq DESC LIMIT 50";	

	$DBRESULT =& $pearDB_syslog->query($req);

 	$buffer = new SyslogXML();
 	$buffer->startElement("root");
 	$buffer->writeElement("label_datetime", _("Date / Time"), false);
 	$buffer->writeElement("label_host", _("Host"), false);
 	$buffer->writeElement("label_facility", _("Facility"), false);
 	$buffer->writeElement("label_severity", _("Severity"), false);
 	$buffer->writeElement("label_program", _("Program"), false);
 	$buffer->writeElement("label_msg", _("Message"), false);

 	$style = "list_two";

 	$priority_color = array ('debug' => 'badge badge_debug', 'info' => 'badge badge_info', 'notice' => 'badge badge_notice',
		'warning' => 'badge badge_warning', 'warn' => 'badge badge_warning',
		'error' => 'badge badge_error',	'err' => 'badge badge_error', 
		'critical' => 'badge badge_critical', 'crit' => 'badge badge_critical', 
		'alert' => 'badge badge_alert', 'emerg' => 'badge badge_emerg');

 	if ($DBRESULT->numRows() == 0) {
 		$buffer->startElement("error");
 		$buffer->writeElement("msg", _("No informations"));
 		$buffer->endElement();
 	} else {
 		while($row =& $DBRESULT->fetchRow()) {
	 		$buffer->startElement("syslog");
	 		$buffer->writeElement("host", $row["host"]);
	 		$buffer->writeElement("facility", $row["facility"]);
	 		$buffer->writeElement("severity", $row["priority"]);
	 		$buffer->writeElement("datetime", $row["datetime"]);
	 		$buffer->writeElement("program", $row["program"]);
	 		$buffer->writeElement("msg", utf8_decode($row["msg"]));
	 		$style == "list_two" ? $style = "list_one" : $style = "list_two";
	 		$buffer->writeElement("style", $style); 		
	 		$buffer->writeElement("prio_class", $priority_color[$row['priority']]);
	 		$buffer->endElement();
 		}
 	}

 	$buffer->endElement();
	header('Content-Type: text/xml');
	header('Pragma: no-cache');
	header('Expires: 0');
	header('Cache-Control: no-cache, must-revalidate'); 
	$buffer->output();
 ?>
