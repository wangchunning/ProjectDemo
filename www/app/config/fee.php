<?php

return array(
		
	/**
	 * fund fee: 百分比 + 最大值
	 */
	'fund'	=> array(		
				array(
					'symbol'		=> 'fund_fee',
					'title'			=> '充值费用',
					'max_amount'	=> 100,
					'rate'			=> 0.5,	// 0.5%
				),
	),
	/**
	 * withdraw fee: 划分区域，每笔固定金额
	 */		
	'withdraw'	=> array(
				array(
					'symbol'		=> 'withdraw_fee',
					'title'			=> '提现费用',
					// range_min <= fee < range_max
					'range_min'		=> 0,
					'range_max'		=> 20000,
					'amount'		=> 1,	// 每笔收多少
				),
				array(
					'symbol'		=> 'withdraw_fee',
					'title'			=> '提现费用',
					// range_min <= fee < range_max
					'range_min'		=> 20000,
					'range_max'		=> 50000,
					'amount'		=> 3,	// 每笔收多少
				),
				array(
					'symbol'		=> 'withdraw_fee',
					'title'			=> '提现费用',
					// range_min <= fee < range_max
					'range_min'		=> 50000,
					'range_max'		=> 0, 	// 0 表示无限制
					'amount'		=> 5,	// 每笔收多少
				),
	),
);