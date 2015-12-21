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

-- Topology
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`, `topology_style_class`, `topology_style_id`, `topology_OnClick`) 
VALUES ('', 'Syslog', NULL, 2, 240, 40, 1, './modules/centreon-syslog-frontend/include/monitoring/syslog.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Monitoring', './img/icones/16x16/text_view.gif', 204, 24001, 40, 1, './modules/centreon-syslog-frontend/include/monitoring/syslog.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Search', './img/icones/16x16/text_view.gif', 204, 24002, 40, 1, './modules/centreon-syslog-frontend/include/search/search.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Details', NULL, 204, 24003, 40, 1, './modules/centreon-syslog-frontend/include/details/eventDetails.php', NULL, '0', '1', '0', NULL, NULL, NULL) ,
('', 'Syslog', './img/icones/16x16/text_view.gif', 6, 605, 91, 1, './modules/centreon-syslog-frontend/include/configuration/configCollectors/collectors.php', NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Collectors', NULL, 605, NULL, NULL, 3, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Collectors', './img/icones/16x16/server_network.gif', 605, 60502, 10, 3, './modules/centreon-syslog-frontend/include/configuration/configCollectors/collectors.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'General', NULL, 605, NULL, NULL, 4, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Resfresh', './img/icones/16x16/server_network.gif', 605, 60503, 10, 4, './modules/centreon-syslog-frontend/include/configuration/configOpt/refresh.php', '&o=f', '0', '1', '1', NULL, NULL, NULL) ;

-- Javascript topology
INSERT INTO `topology_JS` (`id_t_js`, `id_page`, `o`, `PathName_js`, `Init`) 
VALUES ('', '24002', NULL, './modules/centreon-syslog-frontend/include/search/javascript/exportCSV.js', NULL ) ,
('', '24002', NULL, './include/common/javascript/tool.js', NULL ) ;
