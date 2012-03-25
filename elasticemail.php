<?php namespace Elasticemail;

class Email
{
	/**
	 * API domain for ElasticEmail
	 *
	 * @var string
	 */

	const domain = 'api.elasticemail.com';

	/**
	 * API options
	 *
	 * @var array
	 */

	protected $options = array(
		'username' => '',
		'api_key'  => '',
	);

	/**
	 * Request defaults
	 *
	 * @var array
	 */

	protected $defaults = array(
		'from'          => '',
		'from_name'     => '',
		'reply_to'      => '',
		'reply_to_name' => '',
		'channel'       => '',
	);

	/**
	 * API request response
	 *
	 * @var string
	 */

	protected $response = '';

	/**
	 * Initialize the E-mail class
	 *
	 * @param  $username string API username
	 * @param  $api_key  string API key
	 * @param  $defaults array  Request defaults
	 * @return void
	 */

	public function __construct($username, $api_key, $defaults = array())
	{
		$this->options['username'] = $username;
		$this->options['api_key']  = $api_key;
		$this->defaults            = array_merge($this->defaults, $defaults);
	}

	/**
	 * Send an e-mail
	 *
	 * @param  $to          string Recipient e-mail address (semicolon separated, if multiple)
	 * @param  $subject     string E-mail subject
	 * @param  $text        string E-mail body in text/plain
	 * @param  $html        string E-mail body in text/html (optional)
	 * @param  $attachments string E-mail attachment IDs, (optional; semicolon separated, if multiple)
	 * @return string
	 */

	public function send($to, $subject, $text, $html = null, $attachments = null)
	{
		$this->response = '';

		return $this->call("/mailer/send", "POST", array_merge($this->defaults, array(
			'to'          => $to,
			'subject'     => $subject,
			'body_text'   => $text,
			'body_html'   => $html,
			'attachments' => $attachments,
		)));
	}

	/**
	 * Send the actual request to the API
	 *
	 * @param  $path   string API endpoint path
	 * @param  $method string HTTP method
	 * @param  $data   array  Request parameters
	 * @return string
	 */

	private function call($path, $method, $data)
	{
		$socket = fsockopen("ssl://" . self::domain, 443, $errno, $errstr, 30);

		if(! $socket)
			return false;

		$body    = http_build_query(array_filter(array_merge($this->options, $data)));
		$length  = strlen($body);

		$header  = "$method $path HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: $length\r\n\r\n";

		fputs($socket, $header . $body);

		while(! feof($socket))
		{
			$this->response .= fread($socket, 1024);
		}

		fclose($socket);

		return $this->response;
	}
}