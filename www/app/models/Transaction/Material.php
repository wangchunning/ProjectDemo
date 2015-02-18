<?php namespace Tt\Model;


class Material extends \Eloquent {

    /**
     * Whether or not the primary key is auto incrementing?
     *
     * @var bool
     */
    //public $incrementing = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = 'gov_asset';

    /**
     * The PK used by the model.
     *
     * @var string
     */
   // protected $primaryKey = 'TYPE'; 
   
	const TYPE_COMPANY			= 0;
	const TYPE_CREDIT_REPORT 	= 1;
    const TYPE_WORK 			= 2;
    const TYPE_SALARY 			= 3;
    const TYPE_SALARY_FLOW 		= 4;
    const TYPE_SOCIETY_CARD 	= 5;
    const TYPE_PROPERTY 		= 6;
    const TYPE_DRIVE_LICENSE	= 7;
    const TYPE_GRADUATE_CERTIFICATE = 8;

    public static function type_desc($type)
    {
    	switch ($type)
    	{
    		case Material::TYPE_COMPANY:
    			return '公司认证';
    
    		case Material::TYPE_CREDIT_REPORT:
    			return '个人信用报告';
    	}
    
    	return '';
    }
    
}