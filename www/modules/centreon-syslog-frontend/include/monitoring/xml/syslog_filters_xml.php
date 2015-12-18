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
	/*
	 * PHP require
	 */
	require_once "/etc/centreon/centreon.conf.php";
	require_once $centreon_path . "www/modules/centreon-syslog-frontend/include/common/header.php";
	require_once $centreon_path . "www/include/common/common-Func.php";
	
	require_once $syslog_mod_path . "/include/common/common-Func.php";
	require_once $syslog_mod_path . "/class/syslogXML.class.php";

	/*
	 * Get language 
	 */
	$locale = $oreon->user->get_lang();
	putenv("LANG=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages",  $syslog_mod_path . "locale/");
	bind_textdomain_codeset("messages", "UTF-8"); 
	textdomain("messages");
	
	/*
	 * Get selected option in lists
	 */
	if (isset($_GET['collector_id']) && $_GET['collector_id'] != "" )
		$collector_id = $_GET['collector_id'];
	else
		$collector_id = "";

	if (isset($_GET['program']) && $_GET['program'] != "" )
		$program_selected = $_GET['program'];
	else
		$program_selected = "";
	
	if (isset($_GET['host']) && $_GET['host'] != "")
		$host_selected = $_GET['host'];
	else
		$host_selected = "";

	if (isset($_GET['facility']) && $_GET['facility'] != "")
		$facility_selected = $_GET['facility'];
	else
		$facility_selected = "";
			
	if (isset($_GET['Ffacility']) && $_GET['Ffacility'] != "")
		$Ffacility_selected = $_GET['Ffacility'];
	else
		$Ffacility_selected = "";
			
	if (isset($_GET['severity']) && $_GET['severity'] != "")
		$severity_selected = $_GET['severity'];
	else
		$severity_selected = "";
		
	if (isset($_GET['Fseverity']) && $_GET['Fseverity'] != "")
		$Fseverity_selected = $_GET['Fseverity'];
	else
		$Fseverity_selected = "";

	
	header('Content-Type: text/xml');
	header('Pragma: no-cache');
	header('Expires: 0');
	header('Cache-Control: no-cache, must-revalidate');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
	
	/*
	 * Build SQL request
	 */
	if (is_numeric($collector_id)) {
	    $pearDB_syslog = new SyslogDB("syslog", $collector_id);
	    $cfg_syslog = getSyslogOption($collector_id);
	    $FilterHosts = getFilterHostsMerge($pearDB_syslog, $cfg_syslog);

		if (!isset($FilterHosts)) {
	    	echo "<root><error>"._("Problem to access to merge cache table. Please contact your administrator.")."</error></root>";
	    } else {
		    $FilterPrograms = getFilterProgramsMerge($pearDB_syslog, $cfg_syslog);
		    $FilterFacilities = getAllFacilities();
		    $FilterFFacilities = array("" => "", "gt" => ">", "ge" => ">=", "eq" => "=", "le" => "<=", "lt" => "<", "ne" => "!=");
		    $FilterPriorities = getAllSeverities();
		    $FilterFPriorities = array("" => "", "gt" => ">", "ge" => ">=", "eq" => "=", "le" => "<=", "lt" => "<", "ne" => "!=");
		    
		    /*
		     * Generate XML ouput
		    */
		     echo "<root>";
		    
		    # For headers
		    echo "<filters>";
		    echo "<filter>"._("Host")."</filter>";
		    echo "<filter>"._("Facility")."</filter>";
		    echo "<filter>"._("Severity")."</filter>";
		    echo "<filter>"._("Program")."</filter>";
		    echo "<filter>"._("Message")."</filter>";
		    echo "</filters>";
		    
		    # For hosts select box
		    echo "<hosts>";
		    if (preg_match('/^\d+$/', $collector_id)) {
		    	foreach ($FilterHosts as $key=>$value) {
		    		if (strcmp($value, $host_selected) == 0) {
		    			echo "<host selected=\"Y\">".$value."</host>";
		    		} else {
		    			echo "<host>".$value."</host>";
		    		}
		    	}
		    } else {
		    	echo "<host></host>";
		    }
		    echo "</hosts>";
		    
		    # For facilities select box
		    echo "<facilities>";
	     	foreach ($FilterFFacilities as $key=>$value) {
	    		if ((strcmp($Ffacility_selected, "") == 0) && (strcmp($value, "eq") == 0)) {
	   				echo "<Ffacility selected=\"Y\"><![CDATA["."="."]]></Ffacility>";
	     		} else if (strcmp($value, $Ffacility_selected) == 0) {
	    			echo "<Ffacility selected=\"Y\"><![CDATA[".$value."]]></Ffacility>";
	     		} else {
	    			echo "<Ffacility><![CDATA[".$value."]]></Ffacility>";
	     		}
	     	}
		    
	     	foreach ($FilterFacilities as $key=>$value) {
		   		if (strcmp($value, $facility_selected) == 0) {
			    	echo "<facility selected=\"Y\">".$value."</facility>";
			    } else {
					echo "<facility>".$value."</facility>";
	     		}
		    }
		    echo "</facilities>";
		    
		    # For severities select box
		    echo "<severities>";
		    foreach ($FilterFPriorities as $key=>$value) {
		    	if ((strcmp($Fseverity_selected, "") == 0) && (strcmp($value, "eq") == 0)) {
		    		echo "<Fseverity selected=\"Y\"><![CDATA["."="."]]></Fseverity>";
	     		} else if (strcmp($value, $Fseverity_selected) == 0) {
		    		echo "<Fseverity selected=\"Y\"><![CDATA[".$value."]]></Fseverity>";
	     		} else {
	     			echo "<Fseverity><![CDATA[".$value."]]></Fseverity>";
	     		}
	     	}
		    
	     	foreach ($FilterPriorities  as $key=>$value) {
		    	if (strcmp($value, $severity_selected) == 0) {
		    		echo "<severity selected=\"Y\">".$value."</severity>";
		    	} else {
	     			echo "<severity>".$value."</severity>";
		    	}
		    }
		    echo "</severities>";
		     	
		     	# For programs select box
		    echo "<programs>";
	     	if (preg_match('/^\d+$/', $collector_id)) {
	    		foreach ($FilterPrograms as $key=>$value) {
	    			if (strcmp($value, $program_selected) == 0) {
		    			echo "<program selected=\"Y\">".$value."</program>";
		    		} else {
		    			echo "<program>".$value."</program>";
	         		}
	         	}
		    } else {
				echo "<program></program>";
		    }
	    	echo "</programs>";
	    	echo "<msg/>";
	     	echo "</root>";
	    }
	} else {
		echo "<root><error>"._("Please select a collector.")."</error></root>";
	}
 ?>
