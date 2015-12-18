UPDATE topology SET topology_page = '240' WHERE topology_page = '204' AND topology_name = 'Syslog';
UPDATE topology SET topology_parent = '240', topology_page = '24001' WHERE topology_page = '20401' AND topology_name = 'Monitoring';

DELETE FROM topology_JS WHERE id_page = '20402' AND PathName_js = './modules/centreon-syslog-frontend/include/search/javascript/exportCSV.js';
DELETE FROM topology_JS WHERE id_page = '20402' AND PathName_js = './include/common/javascript/jquery/jquery-ui.js';
DELETE FROM topology_JS WHERE id_page = '20402' AND PathName_js = './include/common/javascript/tool.js';


UPDATE topology SET topology_parent = '240', topology_page = '24002' WHERE topology_page = '20402' AND topology_name = 'Search';

INSERT INTO topology_JS (id_page, PathName_js) VALUES ('24002', './modules/centreon-syslog-frontend/include/search/javascript/exportCSV.js'),('24002', './include/common/javascript/datePicker.js'),('24002', './include/common/javascript/tool.js');

UPDATE topology SET topology_parent = '240', topology_page = '24003' WHERE topology_page = '20403' AND topology_name = 'Details';
