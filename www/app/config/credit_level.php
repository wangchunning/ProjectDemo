<?php

return array(
		
	CreditLevel::LEVEL_A	=> array(
		'trust_credit_max'	=> 251,	// 可借款金额最大值
		'trust_credit_min'	=> 300,
		'service_fee_rate'	=> 0,		// 初期服务费 %
		'color'				=> 'green',
	),
	CreditLevel::LEVEL_B	=> array(
		'trust_credit_max'	=> 201,	// 可借款金额最大值
		'trust_credit_min'	=> 250,
		'service_fee_rate'	=> 1,		// 初期服务费 %
		'color'				=> 'lightgreen',
	),
	CreditLevel::LEVEL_C	=> array(
		'trust_credit_max'	=> 151,	// 可借款金额最大值
		'trust_credit_min'	=> 200,
		'service_fee_rate'	=> 1.5,		// 初期服务费 %
		'color'				=> 'orange',
	),
	CreditLevel::LEVEL_D	=> array(
		'trust_credit_max'	=> 101,	// 可借款金额最大值
		'trust_credit_min'	=> 150,
		'service_fee_rate'	=> 2,		// 初期服务费 %
		'color'				=> 'deeporange',
	),
	CreditLevel::LEVEL_E	=> array(
		'trust_credit_max'	=> 51,	// 可借款金额最大值
		'trust_credit_min'	=> 100,
		'service_fee_rate'	=> 2.5,		// 初期服务费 %
		'color'				=> 'red',
	),
	CreditLevel::LEVEL_F	=> array(
		'trust_credit_max'	=> 0,	// 可借款金额最大值
		'trust_credit_min'	=> 50,
		'service_fee_rate'	=> 3,		// 初期服务费 %
		'color'				=> 'deepred',
	),

);