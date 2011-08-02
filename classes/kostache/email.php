<?php defined('SYSPATH') or die('No direct script access.');

class Kostache_Email extends Kostache {

	/**
	 * This will be set automatically when the message is sent.
	 * 
	 * @var string Message ID
	 */
	public $message_id = 'MESSAGE-ID-UNSET';
	
	/**
	 * @var string Message Subject
	 */
	protected $_subject = '';
	
	/**
	 * Gets the subject.
	 * 
	 * @return string 
	 */
	public function subject()
	{
		return $this->_subject;
	}

	/**
	 * @var string Sender Name
	 */
	protected $_sender_name = '';
	
	/**
	 * Gets the sender name.
	 * 
	 * @return string 
	 */
	public function sender_name()
	{
		return $this->_sender_name;
	}

	/**
	 * @var string Sender email email
	 */
	protected $_sender_email = 'noreply@localhost';
	
	/**
	 * Gets the sender email.
	 * 
	 * @return string 
	 */
	public function sender_email()
	{
		return $this->_sender_email;
	}

	/**
	 * @var string Reply-To email
	 */
	protected $_reply_to = NULL;
	
	/**
	 * Gets the Reply-To email.
	 * 
	 * @return string 
	 */
	public function reply_to()
	{
		return $this->_reply_to;
	}
	
	/**
	 * @var string Return-Path email (For bounces)
	 */
	protected $_return_path = NULL;
	
	/**
	 * Gets the Return-Path email.
	 * 
	 * @return string 
	 */
	public function return_path()
	{
		return $this->_return_path;
	}

	/**
	 * @var array SMTP Headers
	 */
	protected $_headers = array(
		'Auto-Submitted' => 'auto-generated', // See http://tools.ietf.org/html/rfc3834#section-5
		'X-Mailer'       => 'Kohana',
	);
	
	/**
	 * Gets the headers array.
	 * 
	 * @return string 
	 */
	public function headers()
	{
		return $this->_headers;
	}
}