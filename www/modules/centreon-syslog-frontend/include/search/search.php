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
 * SVN : $URL:$
 * SVN : $Id:$
 * 
 */
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
	
	# Pagination
	include("./include/common/autoNumLimit.php");

	/*
	 * Pear library
	 */
	require_once "HTML/QuickForm.php";
	require_once 'HTML/QuickForm/advmultiselect.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	/*
	 * PHP functions
	 */
	require_once $syslog_mod_path. "include/common/common-Func.php";
	require_once "./include/common/common-Func.php";

	/*
	 * Database retrieve information for Centreon-Syslog
	 */
	$pearCentreonDB = new SyslogDB("centreon");
	
	$collectorList = getCollectorList();

	# QuickSearch form
	include_once("./include/common/quickSearch.php");

	# Set limit & num
	$DBRESULT =& $pearCentreonDB->query("SELECT `value` FROM options WHERE `key`=\"maxViewMonitoring\" LIMIT 1");
	$row = $DBRESULT->fetchRow();
	$gopt = $row["value"];
	if (PEAR::isError($DBRESULT)) {
		print "Mysql Error : ".$DBRESULT->getMessage()."\n";
	}

	# Get end Post values
	isset ($_GET["num"]) ? $num = $_GET["num"] : $num = 0;
	isset ($_GET["search"]) ? $search = $_GET["search"] : $search = NULL;

	# Get collectors
	( isset($_POST["collectors"]) && ($_POST["collectors"] != ""  )) ? $collectorP = $_POST["collectors"] : $collectorP = NULL;
	( isset($_GET["collectors"]) && ($_GET["collectors"] != ""  )) ? $collectorG = $_GET["collectors"] : $collectorG = NULL;
	( isset($collectorP) ) ? $collector = $collectorP : $collector = $collectorG ;
	
	# Get filters values from post form
	( isset($_POST["filter_program"]) && ($_POST["filter_program"] != ""  )) ? $filter_programP = $_POST["filter_program"] : $filter_programP = NULL;
	( isset($_GET["filter_program"]) && ($_GET["filter_program"] != ""  )) ? $filter_programG = $_GET["filter_program"] : $filter_programG = NULL;	
	( isset($_POST["filter_host"]) && ($_POST["filter_host"] != "" )) ? $filter_hostP = $_POST["filter_host"] : $filter_hostP = NULL;
	( isset($_GET["filter_host"]) && ($_GET["filter_host"] != "" )) ? $filter_hostG = $_GET["filter_host"] : $filter_hostG = NULL;	
	( isset($_POST["filter_facility"]) && ($_POST["filter_facility"] != "" )) ? $filter_facilityP = $_POST["filter_facility"] : $filter_facilityP = NULL;
	( isset($_GET["filter_facility"]) && ($_GET["filter_facility"] != "" )) ? $filter_facilityG = $_GET["filter_facility"] : $filter_facilityG = NULL;	
 	( isset($_POST["filter_Ffacility"]) && ($_POST["filter_Ffacility"] != "" )) ? $filter_FfacilityP = $_POST["filter_Ffacility"] : $filter_FfacilityP = NULL;
	( isset($_GET["filter_Ffacility"]) && ($_GET["filter_Ffacility"] != "" )) ? $filter_FfacilityG = $_GET["filter_Ffacility"] : $filter_FfacilityG = NULL;	
 	( isset($_POST["filter_severity"]) && ($_POST["filter_severity"] != "" )) ? $filter_severityP = $_POST["filter_severity"] : $filter_severityP = NULL;
 	( isset($_GET["filter_severity"]) && ($_GET["filter_severity"] != "" )) ? $filter_severityG = $_GET["filter_severity"] : $filter_severityG = NULL; 	
 	( isset($_POST["filter_Fseverity"]) && ($_POST["filter_Fseverity"] != "" )) ? $filter_FseverityP = $_POST["filter_Fseverity"] : $filter_FseverityP = NULL;
 	( isset($_GET["filter_Fseverity"]) && ($_GET["filter_Fseverity"] != "" )) ? $filter_FseverityG = $_GET["filter_Fseverity"] : $filter_FseverityG = NULL; 	
 	( isset($_POST["filter_msg"]) && ($_POST["filter_msg"] != "" )) ? $filter_msgP = $_POST["filter_msg"] : $filter_msgP = NULL;
 	( isset($_GET["filter_msg"]) && ($_GET["filter_msg"] != "" )) ? $filter_msgG = $_GET["filter_msg"] : $filter_msgG = NULL; 	

	if (isset($_GET["StartDate"])) {
		$start_date = $_GET["StartDate"];
		$StartDate = mktime (0, 0, 0, substr($start_date, 0, 2), substr($start_date, 3, 2), substr($start_date, 6, 4));
	} else if (isset($_POST["StartDate"])) {
		$start_date = $_POST["StartDate"];
		$StartDate = mktime (0, 0, 0, substr($start_date, 0, 2), substr($start_date, 3, 2), substr($start_date, 6, 4));
	} else
		$StartDate =  time();

	if (isset($_GET["EndDate"])) {
		$end_date = $_GET["EndDate"];
		$EndDate = mktime (0, 0, 0, substr($end_date, 0, 2), substr($end_date, 3, 2), substr($end_date, 6, 4));
	} else if (isset($_POST["EndDate"])) {
		$end_date = $_POST["EndDate"];
		$EndDate = mktime (0, 0, 0, substr($end_date, 0, 2), substr($end_date, 3, 2), substr($end_date, 6, 4));
	} else
		$EndDate = time();

	if (isset($_GET["StartTime"])) {
		$StartTime = $_GET["StartTime"];
	} else if (isset($_POST["StartTime"])) {
		$StartTime = $_POST["StartTime"];
	} else
		$StartTime = (Date("H")-1).":".Date("i");

	if (isset($_GET["EndTime"])) {
		$EndTime = $_GET["EndTime"];
	} else if (isset($_POST["EndTime"])) {
		$EndTime = $_POST["EndTime"];
	} else
		$EndTime = Date("H:i");

	if (isset($_POST["end_hidden"])) {
		$end_date = $_POST["end_hidden"];
		$EndDate = mktime (0, 0, 0, substr($end_date, 0, 2), substr($end_date, 3, 2), substr($end_date, 6, 4));
	}
	if (isset($_POST["start_hidden"]))	{
		$start_date = $_POST["start_hidden"];
		$StartDate = mktime (0, 0, 0, substr($start_date, 0, 2), substr($start_date, 3, 2), substr($start_date, 6, 4));
	}

	if (isset($_POST["start_time"]))
		$StartTime = $_POST["start_time"];
		
	if (isset($_POST["end_time"]))
		$EndTime = $_POST["end_time"];

	$filter_program = ( isset($filter_programP) ) ? $filter_program = $filter_programP : $filter_program = $filter_programG ;
	$filter_host = ( isset($filter_hostP)) ? $filter_host = $filter_hostP  : $filter_host = $filter_hostG;
	$filter_facility = ( isset($filter_facilityP)) ? $filter_facility = $filter_facilityP  : $filter_facility = $filter_facilityG;
	$filter_Ffacility = ( isset($filter_FfacilityP)) ? $filter_Ffacility = $filter_FfacilityP  : $filter_Ffacility = $filter_FfacilityG;	
	$filter_severity = ( isset($filter_severityP)) ? $filter_severity = $filter_severityP  : $filter_severity = $filter_severityG;
	$filter_Fseverity = ( isset($filter_FseverityP)) ? $filter_Fseverity = $filter_FseverityP  : $filter_Fseverity = $filter_FseverityG;
	$filter_msg = ( isset($filter_msgP)) ? $filter_msg = $filter_msgP  : $filter_msg = $filter_msgG;

	$FilterHosts = array();
	$FilterPrograms = array();
	$FilterPriorities = array();
	$FilterFacilities = array();
	$elemArr = array();
	
	$error = 0;
	
	if (isset($collector)) {
		$pearSyslogDB = new SyslogDB("syslog", $collector);
		$cfg_syslog = getSyslogOption($collector);

		$FilterHosts = getFilterHostsMerge($pearSyslogDB, $cfg_syslog);
		$FilterPrograms = getFilterProgramsMerge($pearSyslogDB, $cfg_syslog);
		$FilterFacilities = getFilterFacilitiesMerge();
		$FilterPriorities = getFilterPrioritiesMerge();
		
		$sql_filter = array();
	
		if (isset($filter_program))
			array_push($sql_filter ," (program = '". htmlentities($filter_program , ENT_QUOTES) ."')  ");

		if (isset($filter_host))
				array_push($sql_filter ," (host = '". htmlentities($filter_host , ENT_QUOTES) ."')  ");

		if (isset($filter_facility)) {
			if ((strcmp($filter_Ffacility, "") == 0) || (strcmp($filter_Ffacility, "eq") == 0)) {
				array_push($sql_filter ," (facility = '". htmlentities($filter_facility , ENT_QUOTES) ."') ");
			} else {
				$list_facilities = getListOfFacilities($filter_facility, $filter_Ffacility);
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
	
		if (isset($filter_severity)) {
			if ((strcmp($filter_Fseverity, "") == 0) || (strcmp($filter_Fseverity, "eq") == 0)) {
				array_push($sql_filter ," (priority = '". htmlentities($filter_severity , ENT_QUOTES) ."') ");
			} else {
				$list_priorities = getListOfSeverities($filter_severity, $filter_Fseverity);
				$list = "";
				$listKeys = array_keys($list_priorities);
				foreach ($list_priorities as $key=>$value) {
					if (strcmp($list, "") != 0) {
						$list .= ",";
					}
					$list .= "'".$key."'";
				}
				array_push($sql_filter ," (priority IN (".$list.")) ");
			}
		}				
	
		if (isset($filter_msg))
			array_push($sql_filter ," (msg LIKE '%". htmlentities($filter_msg , ENT_QUOTES) ."%') ");	
	
		if (isset($StartDate))
			$start_sql = strftime("%Y-%m-%d " , $StartDate).$StartTime;
	
		if (isset($EndDate))
			$end_sql = strftime("%Y-%m-%d " , $EndDate).$EndTime;
	
		if (isset($sql_filter))
			$req_sql_filter = join(" AND " , $sql_filter);
	
		if($search) {
				$req = "SELECT * FROM ".$cfg_syslog["db_table_logs_merge"]." WHERE msg LIKE '%".htmlentities($search, ENT_QUOTES)."%' ORDER BY datetime ";
		} else {
			if (count( $sql_filter ) > 0 ) 
				$req = "SELECT * FROM ".$cfg_syslog["db_table_logs_merge"]." WHERE datetime > '$start_sql' AND datetime <= '$end_sql' AND ".$req_sql_filter." ORDER BY datetime ";
			else
				$req = "SELECT * FROM ".$cfg_syslog["db_table_logs_merge"]." WHERE datetime > '$start_sql' AND datetime <= '$end_sql' ORDER BY datetime ";
		}
	
		$DBRESULT =& $pearSyslogDB->query($req);
		if (PEAR::isError($DBRESULT)) {
			$error = 1;
			echo "<table><tr><td><font color=\"red\"><b>";
			if (preg_match('/no such table/',$DBRESULT->getMessage())) {
				echo _("Unable to access to table 'db_table_logs_merge', please check the centreon mysql database on this collector.");
			} else {
				echo "Mysql Error : ".$DBRESULT->getMessage();
			}
			echo "</font></b></td></tr></table><br\>";
			
		} else {
			$rows = $DBRESULT->numrows();
		
			if(($num * $limit) > $rows)
				$num = round($rows / $limit) - 1;
			$lstart = $num * $limit;
		
			if ($lstart <= 0)
				$lstart = 0;
		
			$query = $req  . " DESC, seq DESC LIMIT $lstart,$limit";
		
			$DBRESULT1 =& $pearSyslogDB->query($query);
				if (PEAR::isError($DBRESULT1))
						print "Mysql Error : ".$DBRESULT1->getMessage();
		
			while ($DBRESULT1->fetchInto($data)) {
				$elemArr[] = array("RowMenu_link"=>"main.php?p=24003&collector=".$collector."&host=".$data["host"]."&facility=".$data["facility"]."&priority=".$data["priority"]."&datetime=".$data["datetime"]."&program=".$data["program"],
								"RowMenu_datetime"=>$data["datetime"],
								"RowMenu_host"=>$data["host"],
								"RowMenu_facility"=>$data["facility"],
								"RowMenu_priority"=>$data["priority"],
								"RowMenu_tag"=>$data["tag"],
								"RowMenu_program"=>$data["program"],
								"RowMenu_msg"=>htmlentities(utf8_decode($data["msg"])));
			}
		}
	} else {
		$error = 1;
		echo "<table><tr><td><b><h3>"._("Please select a collector.")."</h3></b></td></tr></table><br\>";
	}

	# Smarty template Init
	$tpl = new Smarty();
	$path = $syslog_mod_path."include/search/";
	$tpl = initSmartyTpl($path, $tpl);
	$tpl->assign('startDate', date("m/d/Y", $StartDate));
	$tpl->assign('endDate', date("m/d/Y", $EndDate));
	$tpl->assign('startTime', $StartTime);
	$tpl->assign('endTime', $EndTime);
	$tpl->assign("headerMenu_collectors", _("Poller"));
	$tpl->assign("headerMenu_datetime", _("Date / Time"));
	$tpl->assign("headerMenu_host", _("Host"));
	$tpl->assign("headerMenu_facility", _("Facility"));
	$tpl->assign("headerMenu_severity", _("Severity"));
	$tpl->assign("headerMenu_program", _("Program"));
	$tpl->assign("headerMenu_msg", _("Message"));
	$tpl->assign('limit', $limit);

	# Attributs definition for form_filter quickform
	$attrsText = array("size"=>"75");
	$attrsTextDate 	= array("size"=>"11", "style"=>"font-family:Verdana, Tahoma;font-size:9px;height:13px;border: 0.5px solid gray;");
	$attrsTextHour 	= array("size"=>"5", "style"=>"font-family:Verdana, Tahoma;font-size:9px;height:13px;border: 0.5px solid gray;");

	# QuickForm form_filter
	$form_filter = new HTML_QuickForm('Formfilter', 'post', "?p=".$p."&collectors=".$collector);

	$form_filter->addElement('select', 'collectors', "", $collectorList, array("onChange"=>"javascript:window.location.href='?p=".$p."&collectors='+this.value"));
	$form_filter->setDefaults(array('collectors' => $collector));
	
	$form_filter->addElement('select', 'filter_host', " ", $FilterHosts);
	$form_filter->setDefaults(array('filter_host' => $filter_host));  

	$form_filter->addElement('select', 'filter_facility', " ", $FilterFacilities);
	$form_filter->setDefaults(array('filter_facility' => $filter_facility));  

	$FilterFFacilities = array("" => "", ">" => ">", ">=" => ">=", "=" => "=", "<=" => "<=", "<" => "<", "!=" => "!=");
	$form_filter->addElement('select', 'filter_Ffacility', " ", $FilterFFacilities);
	$form_filter->setDefaults(array('filter_Ffacility' => $filter_Ffacility));

	$form_filter->addElement('select', 'filter_severity', " ", $FilterPriorities);
	$form_filter->setDefaults(array('filter_severity' => $filter_severity)); 

	$FilterFSeverity = array("" => "", ">" => ">", ">=" => ">=", "=" => "=", "<=" => "<=", "<" => "<", "!=" => "!=");
	$form_filter->addElement('select', 'filter_Fseverity', " ", $FilterFSeverity);
	$form_filter->setDefaults(array('filter_Fseverity' => $filter_Fseverity));

	$form_filter->addElement('select', 'filter_program', " ", $FilterPrograms);
	$form_filter->setDefaults(array('filter_program' => $filter_program)); 

	$form_filter->addElement('text', 'filter_msg', "", $attrsText );
	$form_filter->addElement('text', 'start_date', _("From"), $attrsTextDate);
	$form_filter->addElement('text', 'start_time', '', $attrsTextHour);
	$form_filter->addElement('text', 'end_date', _("To"), $attrsTextDate);
	$form_filter->addElement('text', 'end_time',  '', $attrsTextHour);

	if (!isset($filter_start_date))
		$filter_start_date = "";
	if (!isset($filter_end_date))
		$filter_end_date = "";
	if (!isset($filter_start_time))
		$filter_start_time = "";
	if (!isset($filter_end_time))
		$filter_end_time = "";

	$tab_value = array("filter_start_date"=>$filter_start_date, "filter_end_date"=>$filter_end_date, 
		"filter_start_time"=>$filter_start_time,"filter_end_time"=>$filter_end_time);
   	$form_filter->setDefaults($tab_value);

   	if (!$error) {
		$form_filter->addElement('submit', 'filter_search',  _("Search"), "class='btc bt_success'" );
		$form_filter->addElement('reset', 'reset',  _("Reset"), "class='btc bt_default'");
   	}
	$renderer_filter =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form_filter->accept($renderer_filter);
	$tpl->assign('Formfilter', $renderer_filter->toArray());

	$form = new HTML_QuickForm('Formfilterhidden');

	$form->addElement('hidden', 'collectors');
	$form->addElement('hidden', 'filter_host');
	$form->addElement('hidden', 'filter_facility');
	$form->addElement('hidden', 'filter_Ffacility');
	$form->addElement('hidden', 'filter_severity');
	$form->addElement('hidden', 'filter_Fseverity');
	$form->addElement('hidden', 'filter_program');
	$form->addElement('hidden', 'filter_msg');
	$form->addElement('hidden', 'start_hidden');
	$form->addElement('hidden', 'start_time');	
	$form->addElement('hidden', 'end_hidden');
	$form->addElement('hidden', 'end_time');

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form->accept($renderer);
	$tpl->assign('Formfilterhidden', $renderer->toArray());

	$tpl->assign("elemArr", $elemArr);
	$tpl->display($path. "template/search.ihtml");
?>
