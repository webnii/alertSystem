alertSystem
===========

// Basic Alert System
// by Webnii Internet Services LLC

// HOW TO: API Calls
// You can do API-like calls to the system by
// following the example in the curlTest.php file.

Edit the db.php file to add your database's credentials.

Run the following SQL queries in the MySQL database you want to use. 

DROP TABLE IF EXISTS `alertAlerts`;

CREATE TABLE `alertAlerts` (
  `alertNum` int(10) NOT NULL auto_increment,
  `alertType` varchar(55) default NULL,
  `alertTime` datetime default NULL,
  `alertDisplayTime` datetime default NULL,
  `alertSeverity` int(1) default NULL,
  `alertText` blob,
  `alertCleared` int(1) default '0',
  `alertClearedTime` datetime default NULL,
  PRIMARY KEY  (`alertNum`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

/*Data for the table `alertAlerts` */

insert  into `alertAlerts`(`alertNum`,`alertType`,`alertTime`,`alertDisplayTime`,`alertSeverity`,`alertText`,`alertCleared`,`alertClearedTime`) values (52,'manual','2014-07-03 12:25:04','2020-07-03 12:24:50',3,'This is a test future alert.',0,NULL),(51,'manual','2014-07-03 12:24:44','2014-07-03 12:23:41',1,'This is a test cleared alert.',1,'2014-07-03 12:24:48'),(50,'manual','2014-07-03 12:23:39','2014-07-03 12:24:16',3,'This is a test critical alert.',0,NULL),(49,'manual','2014-07-03 12:24:16','2014-07-03 12:24:06',2,'This is a test warning alert.',0,NULL),(48,'manual','2014-07-03 12:23:59','2014-07-03 12:23:48',1,'This is a test informational alert.',0,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
