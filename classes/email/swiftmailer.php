<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * KOstache based emails. Swiftmailer driver.
 *
 * Based on Jeremy Bush's Ciko emailer - https://github.com/zombor/Ciko-Notifier-Email/blob/master/classes/notifier/email.php
 * 
 * @package    KOstache-Email
 * @category   Library
 * @author     Kiall Mac Innes
 * @copyright  (c) 2011 Kiall Mac Innes
 * @license    BSD
 */
class Email_Swiftmailer extends Email {
	
	/**
	 * @var Swift_Mailer Swift mailer instance
	 */
	protected $_mailer = NULL;
	
	public function __construct($template)
	{
		parent::__construct($template);
		
		if ( ! class_exists('Swift_Message'))
		{
			require Kohana::find_file('vendor', 'swiftmailer/lib/swift_required');
		}
		
		$this->_mailer = $this->_swift();
	}
	
	public function _send($recipient_email, $recipient_name, $message_id, $data)
	{
		$view_html = clone $this->_template_html;
		$view_html->set($data)
			->set('message_id', $message_id)
			->set('recipient_email', $recipient_email)
			->set('recipient_name', $recipient_name);
		
		static $message = NULL;
		
		if ($message === NULL)
		{
			$message = Swift_Message::newInstance()
				->setCharset(Kohana::$charset)
				->setFrom(array($view_html->sender_email() => $view_html->sender_name()))
				->setReplyTo($view_html->reply_to());
		}
		
		$message->setReturnPath($view_html->return_path());

		$message->attach(new Swift_MimePart($view_html->render(), 'text/html'))
			->addTo($recipient_email, $recipient_name)
			->setSubject($view_html->subject());
		
		$this->_mailer->send($message);
		
		return $this;
	}

	/**
	 * Method to get a swiftmailer instance
	 * 
	 * @see https://github.com/shadowhand/email/tree/32ca8af13a2558ae5c535002e4280efcd726d4c9
	 *
	 * @return object swiftmailer object
	 */
	protected function _swift()
	{
		// Extract configured options
		extract($this->_config->as_array(), EXTR_SKIP);

		if ($driver === 'smtp')
		{
			// Create SMTP transport
			$transport = Swift_SmtpTransport::newInstance($options['hostname']);

			if (isset($options['port']))
			{
				// Set custom port number
				$transport->setPort($options['port']);
			}

			if (isset($options['encryption']))
			{
				// Set encryption
				$transport->setEncryption($options['encryption']);
			}

			if (isset($options['username']))
			{
				// Require authentication, username
				$transport->setUsername($options['username']);
			}

			if (isset($options['password']))
			{
				// Require authentication, password
				$transport->setPassword($options['password']);
			}

			if (isset($options['timeout']))
			{
				// Use custom timeout setting
				$transport->setTimeout($options['timeout']);
			}
		}
		elseif ($driver === 'sendmail')
		{
			// Create sendmail transport
			$transport = Swift_SendmailTransport::newInstance();

			if (isset($options['command']))
			{
				// Use custom sendmail command
				$transport->setCommand($options['command']);
			}
		}
		else
		{
			// Create native transport
			$transport = Swift_MailTransport::newInstance();

			if (isset($options['params']))
			{
				// Set extra parameters for mail()
				$transport->setExtraParams($options['params']);
			}
		}

		// Create the SwiftMailer instance
		return Swift_Mailer::newInstance($transport);
	}
}