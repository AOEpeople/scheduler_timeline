#
# Table structure for table 'tx_schedulertimeline_domain_model_log'
#
CREATE TABLE tx_schedulertimeline_domain_model_log (
  uid           INT(11)             NOT NULL auto_increment,
  pid           INT(11) DEFAULT '0' NOT NULL,
  tstamp        INT(11) DEFAULT '0' NOT NULL,
  crdate        INT(11) DEFAULT '0' NOT NULL,
  cruser_id     INT(11) DEFAULT '0' NOT NULL,

  task          INT(11) DEFAULT '0' NOT NULL,
  starttime     INT(11) DEFAULT '0' NOT NULL,
  endtime       INT(11) DEFAULT '0' NOT NULL,
  exception     text                NOT NULL,
  returnmessage text                NOT NULL,
  processid     INT(11) DEFAULT '0' NOT NULL,

  PRIMARY KEY (uid),
  KEY parent (pid)
);

#
# Table structure for table 'tx_scheduler_task'
#
CREATE TABLE tx_scheduler_task (
  pid INT(11) DEFAULT '0' NOT NULL
);
