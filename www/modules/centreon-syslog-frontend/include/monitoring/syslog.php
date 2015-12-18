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
 
 	/*
	 * Control user identity
	 */
	if (!isset ($oreon))
        exit ();

    include ("/etc/centreon/centreon.conf.php");
	require_once ($centreon_path . "www/modules/centreon-syslog-frontend/include/common/header.php");

    global $cfg_syslog, $refresh_options;

    /*
     * Set language
     */
    $locale = $oreon->user->get_lang();
    putenv("LANG=$locale");
    setlocale(LC_ALL, $locale);
    bindtextdomain("messages", $syslog_mod_path . "locale/");
    bind_textdomain_codeset("messages", "UTF-8");
    textdomain("messages");

    require $syslog_mod_path. "include/common/common-Func.php";
    require $syslog_mod_path. "include/monitoring/javascript/syslog_js.php";

    $collectorList = getCollectorList();
    $init = 0;

	echo "<link href=\"./modules/centreon-syslog-frontend/css/syslog.css\" type=\"text/css\" rel=\"stylesheet\">";
	echo "<table width=\"100%\">";
	echo "  <tr class=\"list_two\" align=\"center\">";
	echo "          <td class=\"ListColCenter\">";
	echo "                  "._("Collector:")."&nbsp;&nbsp;&nbsp;";
	echo "                  <select onChange=\"javascript:rebuild_page();\" name=\"collector_id\" id=\"collector_id\" >";
	foreach ($collectorList as $key => $value) {
		if (($init == 0) && preg_match('/^$/', $value) == 0) {
			echo "				<option value=\"".$key."\" selected>".$value."</option>";
			$init++;
		} else {
			echo "				<option value=\"".$key."\">".$value."</option>";
		}
	}
	echo "					</select>";
	echo "          </td>";
	echo "          <td class=\"ListColCenter\">";
	echo "                  "._("Refresh:")."&nbsp;&nbsp;&nbsp;<input onclick=\"javascript:ajax_handler(this.value)\" name=\"ajax\" id=\"ajaxBtn\" value=\""._("stop")."\" type=\"button\" />";
	echo "          </td>";
	echo "  </tr>";
	echo "</table>";
	echo "<div id=\"ajaxFilters\"></div>";
	echo "<br>";
	echo "<div id=\"ajaxLog\"></div>";
?>
<script type="text/javascript">
	setTimeout('build_ajaxFilters()', 500);
	setTimeout('build_ajax(this)', 1000);
</script>
