CREATE TABLE `mod_docs_edits` (
  `id_edit` int(10) unsigned NOT NULL,
  `id_sec` int(10) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `id_res` int(11) NOT NULL DEFAULT '0',
  `nameid` varchar(150) NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `uname` varchar(40) NOT NULL,
  `modified` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_docs_figures` (
`id_fig` int(10) unsigned NOT NULL,
  `id_res` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL,
  `attrs` text NOT NULL,
  `desc` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_docs_meta` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `section` int(11) NOT NULL,
`id_meta` bigint(20) NOT NULL,
  `edit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_docs_references` (
  `id_ref` int(10) unsigned NOT NULL,
  `id_res` int(10) unsigned NOT NULL DEFAULT '0',
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_docs_resources` (
  `id_res` int(11) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  `owner` int(11) NOT NULL,
  `owname` varchar(50) NOT NULL,
  `editors` text NOT NULL,
  `editor_approve` tinyint(1) NOT NULL DEFAULT '0',
  `groups` text NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `quick` tinyint(1) NOT NULL DEFAULT '0',
  `nameid` varchar(150) NOT NULL,
  `show_index` tinyint(1) NOT NULL DEFAULT '0',
  `reads` int(11) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `single` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(10) NOT NULL DEFAULT '0',
  `license` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_docs_sections` (
`id_sec` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `id_res` int(11) NOT NULL DEFAULT '0',
  `nameid` varchar(150) NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `uid` int(11) NOT NULL DEFAULT '0',
  `uname` varchar(40) NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  `comments` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_docs_votedata` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `date` int(10) NOT NULL,
  `res` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `mod_docs_edits`
 ADD PRIMARY KEY (`id_edit`);

ALTER TABLE `mod_docs_figures`
 ADD PRIMARY KEY (`id_fig`);

ALTER TABLE `mod_docs_meta`
 ADD PRIMARY KEY (`id_meta`), ADD KEY `name` (`name`,`section`);

ALTER TABLE `mod_docs_references`
 ADD PRIMARY KEY (`id_ref`);

ALTER TABLE `mod_docs_resources`
 ADD PRIMARY KEY (`id_res`), ADD KEY `nameid` (`nameid`), ADD KEY `title` (`title`);

ALTER TABLE `mod_docs_sections`
 ADD PRIMARY KEY (`id_sec`), ADD KEY `nameid` (`nameid`);

ALTER TABLE `mod_docs_votedata`
 ADD KEY `uid` (`uid`,`ip`,`res`);


ALTER TABLE `mod_docs_edits`
MODIFY `id_edit` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `mod_docs_figures`
MODIFY `id_fig` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `mod_docs_meta`
MODIFY `id_meta` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mod_docs_references`
MODIFY `id_ref` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `mod_docs_resources`
MODIFY `id_res` int(11) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `mod_docs_sections`
MODIFY `id_sec` int(10) unsigned NOT NULL AUTO_INCREMENT;