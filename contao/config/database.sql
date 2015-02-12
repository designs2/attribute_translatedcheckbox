-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_metamodel_attribute`
--

CREATE TABLE `tl_metamodel_attribute` (
  `check_publish` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table `tl_metamodel_filtersetting`
--

CREATE TABLE `tl_metamodel_filtersetting` (
  `check_ignorepublished` char(1) NOT NULL default '',
  `check_allowpreview` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 
-- Table `tl_metamodel_translatedcheckbox`
-- 

CREATE TABLE `tl_metamodel_translatedcheckbox` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `att_id` int(10) unsigned NOT NULL default '0',
  `item_id` int(10) unsigned NOT NULL default '0',
  `langcode` varchar(5) NOT NULL default '',
  `value` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `attvalue` (`att_id`, `value`),
  KEY `attlang` (`att_id`, `langcode`),
  KEY `attitem` (`att_id`, `item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
