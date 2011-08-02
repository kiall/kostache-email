<?php defined('SYSPATH') or die('No direct script access.');
/**
 * KOstache based emails
 * 
 * @package    KOstache-Email
 * @category   Library
 * @author     Kiall Mac Innes
 * @copyright  (c) 2011 Kiall Mac Innes
 * @license    BSD
 */
class Email {
	
	/**
	 * @var  string  default email adapter
	 */
	public static $default = 'swiftmailer';
	
	/**
	 * @var string KOstache view name
	 */
	protected $_template_name = NULL;
	
	/**
	 * @var KOstache HTML Template
	 */
	protected $_template_html = NULL;
	
	/**
	 * @var KOstache Plain Template
	 */
	protected $_template_plain = NULL;
	
	/**
	 * @var array List of emails, and email specific data
	 */
	protected $_to = array();
	
	/**
	 * @var array Config
	 */
	protected $_config = NULL;
	
	public static function factory($template, $type = NULL)
	{
		
		if ($type === NULL)
		{
			$type = Email::$default;
		}
		
		$class = 'Email_'.ucfirst($type);

		return new $class($template);
	}
	
	public function __construct($template)
	{
		// Load email configuration
		$this->_config = Kohana::$config->load('email');
		
		$this->_template_name = $template;
		
		/**
		 * @todo .. Plain text version..
		 */
		try
		{
			$this->_template_html = Kostache::factory('email'.DIRECTORY_SEPARATOR.$template);
		}
		catch (Exception $e)
		{
			$this->_template_html = NULL;
		}

		if ($this->_template_html === NULL )
		{
			throw new Kohana_Exception('A template is required');
		}
	}
	
	/**
	 *
	 * @param string $email Recipent Email
	 * @param string $email Recipent Name
	 * @param array  $data  Email Specific Data
	 * @return Email 
	 */
	public function to($to, $name = NULL, $data = array())
	{
		if ($name === NULL)
		{
			$name = $to;
		}
		
		$this->_to[$to] = array($name, $data);
		
		return $this;
	}
		
	/**
	 * Assigns a variable by name.
	 *
	 * @param   string   variable name or an array of variables
	 * @param   mixed    value
	 * @return  Email
	 */
	public function set($key, $value = NULL)
	{
		$this->_template_html->set($key, $value);
		
		return $this;
	}
	
	/**
	 * Sends the emails
	 * 
	 * @return Email 
	 */
	public function send()
	{
		foreach ($this->_to as $recipient_email => $x)
		{
			list ($recipient_name, $data) = $x;
			
			$message_id = Text::random('alnum', 50);
			
			list($insert_id, $affected_rows) = DB::insert('email_audit', array(
				'recipient_email',
				'message_id',
				'template',
				'created',
			))->values(array(
				$recipient_email,
				$message_id,
				$this->_template_name,
				time(),
			))->execute();
		
			$this->_send($recipient_email, $recipient_name, $message_id, $data);
		}
		
		return $this;
	}
}