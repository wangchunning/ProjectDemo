<?php namespace Lib\Email;

class Provider {

    /**
     * A list of common used email providers
     *
     * @var array
     */
	protected $providers = array(
		'gmail.com'           => 	'http://mail.google.com',
		'me.com'              =>    'https://www.icloud.com/#mail',
		'icloud.com'          =>    'https://www.icloud.com/#mail',
		'hotmail.com'         =>    'http://www.hotmail.com',
		'outlook.com'         =>    'http://www.outlook.com',
		'yahoo.com'           => 	'http://mail.yahoo.com',
		'aol.com'             =>	'http://webmail.aol.com',
		'aim.com'             =>	'http://webmail.aol.com/?offerId=aimmail-en-us',
		'msn.com'             =>	'https://accountservices.msn.com/',
		'mail.com'            =>    'http://www.mail.com/int/',
		'126.com'             => 	'http://mail.126.com',
		'163.com'             => 	'http://mail.163.com',
		'sina.com'            =>	'http://mail.sina.com',
		'vip.163.com'         =>	'http://vip.163.com',
		'yeah.net'            =>	'http://www.yeah.net',
		'qq.com'              =>	'http://mail.qq.com/cgi-bin/loginpage',
		'tom.com'             => 	'http://mail.tom.com',
		'sohu.com'            =>	'http://mail.sohu.com',
		'139.com'             =>	'http://mail.139.com',
		'hexun.com'           =>	'http://mail.hexun.com',
		'eyou.com'            =>	'http://www.eyou.com',
		'21cn.com'            =>	'http://mail.21cn.com'
		);

    /**
     * Resolve an email address
     *
     * @param  string $email The email to resolve.
     * @return Yoozi\Email\Address 
     */
	public function resolve($email)
	{
		if ( ! filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			throw new InvalidArgumentException(
                "\$email($email) is not a valid email address"
            );
		}

		$value = strtolower($email);
		list(, $domain) = explode('@', $value);
		list($provider) = explode('.', $domain);
		$url = isset($this->providers[$domain]) ? $this->providers[$domain] : sprintf('http://mail.%s', $domain);

		return new Address(array(
			'value'     => $value,
			'provider'  => $provider,
			'domain'    => $domain,
			'url'       => $url
		));
	}
}