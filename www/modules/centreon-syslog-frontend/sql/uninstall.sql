DROP TABLE IF EXISTS `mod_syslog_opt`;
DROP TABLE IF EXISTS `mod_syslog_collector`;
DROP TABLE IF EXISTS `mod_syslog_filters_priority`;
DROP TABLE IF EXISTS `mod_syslog_filters_facility`;

DELETE FROM `topology` WHERE `topology_parent` = '240' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_page` = '24001' AND `topology_name` = "Monitoring";
DELETE FROM `topology` WHERE `topology_page` = '24002' AND `topology_name` = "Search";
DELETE FROM `topology` WHERE `topology_page` = '24003' AND `topology_name` = "Details";
DELETE FROM `topology` WHERE `topology_page` = '240' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_parent` = '605' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_parent` = '605' AND `topology_name` = "Collectors";
DELETE FROM `topology` WHERE `topology_page` = '60502' AND `topology_name` = "Collectors";
DELETE FROM `topology` WHERE `topology_parent` = '605' AND `topology_name` = "General";
DELETE FROM `topology` WHERE `topology_page` = '60503' AND `topology_name` = "Resfresh";
DELETE FROM `topology` WHERE `topology_page` = '605' AND `topology_name` = "Syslog";

DELETE FROM `topology_JS` WHERE `id_page` = '240' LIMIT 1;
DELETE FROM `topology_JS` WHERE `id_page` = '24001' LIMIT 1;
DELETE FROM `topology_JS` WHERE `id_page` = '24002' LIMIT 1;
