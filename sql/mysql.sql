CREATE TABLE `mod_docs_figures` (
  `id_fig` int(10) unsigned NOT NULL auto_increment,
  `id_res` int(10) unsigned NOT NULL default '0',
  `title` varchar(150) NOT NULL,
  `attrs` text NOT NULL,
  `desc` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id_fig`)
) ENGINE=MyISAM ;

CREATE TABLE `mod_docs_references` (
  `id_ref` int(10) unsigned NOT NULL auto_increment,
  `id_res` int(10) unsigned NOT NULL default '0',
  `title` varchar(150) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY  (`id_ref`)
) ENGINE=MyISAM ;

CREATE TABLE `mod_docs_resources` (
  `id_res` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `owner` int(11) NOT NULL,
  `owname` varchar(50) NOT NULL,
  `editors` text NOT NULL,
  `editor_approve` tinyint(1) NOT NULL default '0',
  `groups` text NOT NULL,
  `public` tinyint(1) NOT NULL default '0',
  `quick` tinyint(1) NOT NULL default '0',
  `nameid` varchar(150) NOT NULL,
  `show_index` tinyint(1) NOT NULL default '0',
  `reads` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rating` int(11) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '1',
  `featured` tinyint(1) NOT NULL default '0',
  `single` tinyint(1) NOT NULL default '0',
  `comments` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id_res`),
  KEY `nameid` (`nameid`),
  KEY `title` (`title`)
) ENGINE=MyISAM ;

CREATE TABLE `mod_docs_sections` (
  `id_sec` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `order` int(11) NOT NULL default '0',
  `id_res` int(11) NOT NULL default '0',
  `nameid` varchar(150) NOT NULL,
  `parent` int(10) unsigned NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `uname` varchar(40) NOT NULL,
  `created` int(10) NOT NULL default '0',
  `modified` int(10) NOT NULL default '0',
  `comments` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id_sec`),
  KEY `nameid` (`nameid`)
) ENGINE=MyISAM ;

CREATE TABLE `mod_docs_votedata` (
  `uid` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `date` int(10) NOT NULL,
  `res` int(11) NOT NULL,
  KEY `uid` (`uid`,`ip`,`res`)
) ENGINE=MyISAM;

CREATE TABLE `mod_docs_edits` (
`id_edit` int( 10 ) unsigned NOT NULL AUTO_INCREMENT ,
`id_sec` int( 10 ) NOT NULL default '0',
`title` varchar( 200 ) NOT NULL ,
`content` text NOT NULL ,
`order` int( 11 ) NOT NULL default '0',
`id_res` int( 11 ) NOT NULL default '0',
`nameid` varchar( 150 ) NOT NULL ,
`parent` int( 10 ) unsigned NOT NULL default '0',
`uid` int( 11 ) NOT NULL default '0',
`uname` varchar( 40 ) NOT NULL ,
`modified` int( 10 ) NOT NULL default '0',
PRIMARY KEY ( `id_edit` )
) ENGINE = MYISAM;

CREATE TABLE `mod_docs_meta` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `section` int(11) NOT NULL,
  `id_meta` bigint(20) NOT NULL auto_increment,
  `edit` tinyint(1) NOT NULL default 0,
  PRIMARY KEY  (`id_meta`),
  KEY `name` (`name`,`section`)
) ENGINE=MyISAM;
