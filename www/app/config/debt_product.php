<?php

return array(
		
	/**
	 * b2p
	 */
	Tx::PRODUCT_TYPE_B2P	=> array(
				'title'				=> '抵押贷',
				'credit_amount_min'	=> 3000,
				'credit_amount_max'	=> array(	CreditLevel::LEVEL_F => 500000,
												CreditLevel::LEVEL_E => 800000,
												CreditLevel::LEVEL_D => 1000000,
												CreditLevel::LEVEL_C => 1500000,
												CreditLevel::LEVEL_B => 2000000,
												CreditLevel::LEVEL_A => 3000000),	// 不同信用级别所能借款金额的最大值
				'rate_max'			=> 24, 		// 年利率最大值 %
				'rate_min'			=> 8,		// 年利率最小值%
				'period'			=> array(3 => 10, 6 => 11, 9 => 12, 12 => 13,15 => 13,18 => 13,24 => 13, 36 =>24),	// 借款期限（月）=> min_rate	
				'valid_day_cnt'		=> 5,		// 募集资金有效期 （天），过期可以撤回或部分募集
				'min_invest_amount'	=> 50,		// 最低投资金额
				'frozen_rate'		=> 10,		// 冻结保证金比例 10%
				'frozen_rate_vip'	=> 8,		// VIP冻结保证金比例
				'repayment_type'	=> Repayment::TYPE_MONTH,	// 还款方式，等额本息
				'trust_credit'		=> 50,		// 成功还款后获得的信用积分
				'repayment_in_advanced_rate' => 1, 	// 提前还款费率%
				'repayment_rate' 	=> 0.3, 	// 还款管理费%
	),

);