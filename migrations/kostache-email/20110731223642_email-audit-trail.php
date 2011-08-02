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
`recipient_email` VARCHAR( 255 ) NOT NULL ,
`message_id` VARCHAR( 50 ) NOT NULL ,
`template` VARCHAR( 255 ) NOT NULL ,
`bounced` BOOLEAN NOT NULL DEFAULT  \'0\' , 
`bounce_processed` BOOLEAN NOT NULL DEFAULT  \'0\' , 
`created` INT( 10 ) UNSIGNED NOT NULL ,
`updated` INT( 10 ) UNSIGNED DEFAULT NULL ,
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
