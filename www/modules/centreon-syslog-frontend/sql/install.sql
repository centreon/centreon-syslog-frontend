CREATE TABLE IF NOT EXISTS `mod_syslog_opt` (
  `id` int(11) NOT NULL auto_increment,
  `refresh_monitoring` int(15) NOT NULL default '10',
  `refresh_filters` int(15) NOT NULL default '240',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

INSERT INTO `mod_syslog_opt` VALUES (NULL, '10', '240');

CREATE TABLE IF NOT EXISTS `mod_syslog_filters_facility` (
  `key` varchar(255) default NULL,
  `value` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mod_syslog_filters_facility`
VALUE ('kern', 0), 
('user', '1'), 
('mail', '2'), 
('daemon', '3'), 
('auth', '4'), 
('security', '4'),
('syslog', '5'), 
('lpr', '6'), 
('news', '7'), 
('uucp', '8'), 
('cron', '9'), 
('authpriv', '10'), 
('ftp', '11'), 
('local0', '16'), 
('local1', '17'), 
('local2', '18'), 
('local3', '19'), 
('local4', '20'), 
('local5', '21'), 
('local6', '22'), 
('local7', '23');

CREATE TABLE IF NOT EXISTS `mod_syslog_filters_priority` (
  `key` varchar(255) default NULL,
  `value` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mod_syslog_filters_priority`
VALUE ('emerg', 0), 
('panic', 0), 
('alert', '1'), 
('crit', '2'), 
('error', '3'), 
('err', '3'), 
('warning', '4'),
('warn', '4'),
('notice', '5'), 
('info', '6'), 
('debug', '7');

CREATE TABLE IF NOT EXISTS `mod_syslog_collector` (
  `collector_id` int(11) NOT NULL auto_increment,
  `collector_name` varchar(255) default NULL,
  `db_server_address` varchar(45) default NULL,
  `db_server_port` int(11) default '3306',
  `db_type` varchar(45) default 'mysql',
  `db_name` varchar(45) default 'centreon_syslog',
  `db_username` varchar(45) default 'centreon_syslog',
  `db_password` varchar(45) default 'syslogapass',
  `db_table_logs` varchar(45) default 'logs',
  `db_table_logs_merge` varchar(45) default 'all_logs',
  `db_table_cache` varchar(45) default 'cache',
  `db_table_cache_merge` varchar(45) default 'all_cache',
  `ssh_server_address` varchar(45) default NULL,
  `ssh_server_port` int(11) default '22',
  `ssh_username` varchar(45) default 'syslog',
  `ssh_password` varchar(45) default NULL,
  `configuration_dir` varchar(255) default '/etc/centreon-syslog',
  `retention_days` int(11) default '31',
  `enable` enum('0','1') default '1',
  `comment` varchar(255) default NULL,
  PRIMARY KEY  (`collector_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `mod_syslog_hosts` (
  `id` int(11) NOT NULL auto_increment,
  `collector_id` int(15) default NULL,
  `host_centreon_id` int(11) default NULL,
  `host_syslog_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `host_syslog_ipv4` varchar(45) collate utf8_unicode_ci NOT NULL,
  `state` enum('0','1','2') default '0',
  PRIMARY KEY  (`id`),
  KEY `host_centreon_id` (`host_centreon_id`),
  KEY `host_syslog_name` (`host_syslog_name`),
  KEY `host_syslog_ipv4` (`host_syslog_ipv4`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Topology
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`, `topology_style_class`, `topology_style_id`, `topology_OnClick`)
VALUES ('', 'Syslog', 2, 204, 40, 1, './modules/centreon-syslog-frontend/include/monitoring/syslog.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Monitoring', 204, 20401, 40, 1, './modules/centreon-syslog-frontend/include/monitoring/syslog.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Search', 204, 20402, 40, 1, './modules/centreon-syslog-frontend/include/search/search.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Details', 204, 20403, 40, 1, './modules/centreon-syslog-frontend/include/details/eventDetails.php', NULL, '0', '1', '0', NULL, NULL, NULL) ,
('', 'Syslog', 6, 605, 91, 1, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Collectors', 605, NULL, NULL, 3, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Collectors', 605, 60502, 10, 3, './modules/centreon-syslog-frontend/include/configuration/configCollectors/collectors.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Hosts', 605, NULL, NULL, 1, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL) ,
('', 'Hosts', 605, 60501, 10, 1, './modules/centreon-syslog-frontend/include/configuration/configHosts/hosts.php', '&o=l', '0', '1', '1', NULL, NULL, NULL) ,
('', 'General', 605, NULL, NULL, 4, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Resfresh', 605, 60503, 10, 4, './modules/centreon-syslog-frontend/include/configuration/configOpt/refresh.php', '&o=f', '0', '1', '1', NULL, NULL, NULL) ;

-- Javascript topology
INSERT INTO `topology_JS` (`id_t_js`, `id_page`, `o`, `PathName_js`, `Init`) 
VALUES ('', '20402', NULL, './modules/centreon-syslog-frontend/include/search/javascript/exportCSV.js', NULL ) ,
('', '20402', NULL, './include/common/javascript/datePicker.js', NULL ) ,
('', '20402', NULL, './include/common/javascript/tool.js', NULL ) ;
