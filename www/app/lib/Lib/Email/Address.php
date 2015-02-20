<?php namespace Lib\Email;

class Address {

    /**
     * Email address to be determined
     *
     * @var string
     */
    public $value = '';

    /**
     * Provider of an email address
     *
     * @var string
     */
	public $provider = '';

    /**
     * Domain part of an email address
     *
     * @var string
     */
    public $domain = '';

    /**
     * Url of the provider login page
     *
     * @var string
     */
	public $url = '';

    /**
     * Initialize a new address
     *
     * @param  array  $email
     * @return void
     */
	public function __construct($email = array())
	{
		if ($email)
		{
			foreach ($email as $attribute => $value)
			{
				if (isset($this->$attribute))
				{
					$this->$attribute = $value;
				}
			}
		}
	}
}