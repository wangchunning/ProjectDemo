<?php namespace Lib\Ret;


class Ret {
	
	const RET_SUCC 		= 0;
	const RET_FAILED	= 1;
	
	/**
	 * 验证模块 1000 - 1999
	 */
	/* 已经验证通过的 email 再次被验证 */
	const RET_EMAIL_DUPLICATED_VERIFY 	= 1001;
	
	/**
	 * 取款模块 2000 - 2999
	 */
	/* 可用余额不足 */
	const RET_WITHDRAW_BALANCE_NOT_ENOUGH	= 2000;
	
	/**
	 * 投资模块 3000 - 3999
	 */
	/* 可用余额不足 */	
	const RET_INVEST_BALANCE_NOT_ENOUGH		= 3000;
}
