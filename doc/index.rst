.. Centreon Syslog Frontend documentation master file, created by
   sphinx-quickstart on Fri Dec 21 15:08:20 2012.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Welcome to Centreon Syslog Frontend's documentation!
====================================================

The `Centreon Syslog project <http://forge.centreon.com/projects/centreon-syslog>`_ consists of several parts:

* `Centreon Syslog Frontend <http://forge.centreon.com/projects/centreon-syslog-frontend>`_
* `Centreon Syslog Server <http://forge.centreon.com/projects/centreon-syslog-server>`_
* `Centreon Syslog CLAPI <http://forge.centreon.com/projects/centreon-syslog-clapi>`_

The Centreon Syslog Frontend module is the graphic user interface of the project 
Centreon Syslog for Centreon local server. This interface allows to configure 
different Centreon Syslog Server modules as well as to view the syslog events 
collected by the latter modules. Besides viewing in real time syslog events 
of the collectors, the Centreon Syslog Frontend module also allows to make a 
search on past events.

The Centreon Syslog Server allows to manage syslog events stored into MySQL 
database by Syslog daemon (Rsyslog, Syslog-ng)

The Centreon Syslog CLAPI module allows you to extract recorded syslog events from 
databases to CSV, XML or ODT format. The extraction made from Centreon server is
stored in command line format.

.. note:: It is important to note that this version no longer requires "php-syslog-ng" although it was required for the "Syslog 1.1" version.

This documentation will explain to you how to install, use and manage a Centreon Syslog Frontend:

.. toctree::
  :maxdepth: 3

  installation/index
  configuration/index
  user/index

