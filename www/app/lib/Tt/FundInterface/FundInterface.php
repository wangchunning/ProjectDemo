<?php namespace Tt\FundInterface;


/**
 * 
 */

class FundInterface {


	/**
	 * guid
	 *
	 * get a guid id by registering @ http://abr.business.gov.au/Webservices.aspx
	 *
	 * @var string
	 */
    private $guid = "d1dd7fd2-fd30-4f78-b3e7-8bb6a367c3c6"; 
	
	public function __construct()
    {

    }
	
	/**
	 * 获取 ABN 相关信息
	 *
	 * 
	 * @param  string  business number
	 * @return object
	 */
	public function fund($amount)
	{
		return true;
	}

}

