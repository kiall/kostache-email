<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Email Audit Trail
 */
class Migration_Kostache_email_20110731223642 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, 'CREATE TABLE  `email_audit` (
`id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
`to` VARCHAR( 255 ) NOT NULL ,
`code` VARCHAR( 50 ) NOT NULL ,
`type` VARCHAR( 255 ) NOT NULL ,
`created` INT( 10 ) UNSIGNED NOT NULL ,
PRIMARY KEY (  `id` ) ,
INDEX (  `id` )
) ENGINE = INNODB;');
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE `email_audit`');
	}
}
