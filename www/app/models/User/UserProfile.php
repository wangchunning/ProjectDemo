<?php namespace Tt\Model;


class UserProfile extends \Eloquent {

    /**
     * Whether or not the primary key is auto incrementing?
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_profile';

    /**
     * The PK used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    const GENDER_VALUE_MALE		= 0;
    const GENDER_VALUE_FEMALE	= 1;
    /**
     * 根据身份证号设置性别、生日等字段
     */
	public function set_attr_from_id_number()
	{
		/**
		 * 18位的身份证号码 如：130429####%%%%0078
		 * ，1~6位为地区代码，其中1、2位数为各省级政府的代码，3、4位数为地、市级政府的代码，5、6位数为县、区级政府代码。如13（河北省）04（邯郸市）29（永年县）
		 * ，7~14位为出生年月日 
		 * ，第17位如果是单数为男性分配码，双数为女性分配码
		 * 
		 * 15位的身份证号码：
		 * ，1~6位为地区代码
		 * ，7~8位为出生年份(2位)，9~10位为出生月份，11~12位为出生日期
		 * ，第15位如果是单数为男性分配码，双数为女性分配码
		 */
		$_gender_number = UserProfile::GENDER_VALUE_MALE;
		
		$_id_len = strlen($this->id_number);
		if ($_id_len == 15)
		{
			$this->birthday = '19' . substr($this->id_number, 6, 6);
			$_gender_number = substr($this->id_number, 14, 1);
		}
		else if ($_id_len == 18)
		{
			$this->birthday = substr($this->id_number, 6, 8);
			$_gender_number = substr($this->id_number, 16, 1);
		}
		
		$_gender_number % 2 == 0 ? 
			$_gender_number = UserProfile::GENDER_VALUE_FEMALE
									:
								UserProfile::GENDER_VALUE_MALE;
		
	}

	public function gender_desc()
	{
		$_desc = '';
		if ($this->gender == UserProfile::GENDER_VALUE_MALE)
		{
			$_desc = '男';
		}
		else if ($this->gender == UserProfile::GENDER_VALUE_FEMALE)
		{
			$_desc = '女';
		}
			
		return $_desc;
	}
	
	public function education_desc()
	{
		$_mapping = UserProfile::education_arr();
		
		return isset($_mapping[$this->education]) ? 
						$_mapping[$this->education] : '-';
		
	}
	
	public static function education_arr()
	{
		/**
		 * id => value
		 */
		return array(
				0 => '高中或以下',
				1 => '大专',
				2 => '本科',
				3 => '研究生或以上'
		);
	}
	
	public function marriage_desc()
	{
		$_mapping = UserProfile::marriage_arr();
	
		return isset($_mapping[$this->marriage]) ?
		$_mapping[$this->marriage] : '-';
	
	}
	
	public static function marriage_arr()
	{
		/**
		 * id => value
		 */
		return array(
				0 => '已婚',
				1 => '未婚',
				2 => '离异',
				3 => '丧偶'
		);
	}
	
	public static function company_category_desc($category)
	{
		$_mapping = UserProfile::company_category_arr();
	
		return isset($_mapping[$category]) ?
		$_mapping[$category] : '-';
	
	}
	public static function company_category_arr()
	{
		/**
		 * id => value
		 */
		return array(
				0 => "制造业",
				1 => "IT",
				2 => "政府机关",
				3 => "媒体/广告",
				4 => "零售/批发",
				5 => "教育/培训",
				6 => "公共事业",
				7 => "交通运输业",
				8 => "房地产业",
				9 => "能源业",
				10 => "金融/法律",
				11 => "餐饮/旅馆业",
				12 => "医疗/卫生/保健",
				13 => "建筑工程",
				14 => "农业",
				15 => "娱乐服务业",
				16 => "体育/艺术",
				17 => "公益组织",
				18 => "其它",
		);
	}
	
	public static function company_scale_arr()
	{
		/**
		 * id => value
		 */
		return array(
				0 => '10 人以下',
				1 => '10 ~ 100 人',
				2 => '100 ~ 500 人',
				3 => '500 人以上'
		);
	}
	
	public static function salary_arr()
	{
		/**
		 * id => value
		 */
		return array(
				0 => '1000 元以下',
				1 => '1001-2000 元',
				2 => '2000-5000 元',
				3 => '5000-10000 元',
				4 => '10000-20000 元',
				5	=> '20000-50000 元',
				6	=> '50000 元以上',
		);
	}
}