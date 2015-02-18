<?php

return array(
	'role'
			=> array(
					'title'		=> '角色权限管理',
					'actions'	=>
					array(
							array(
									'view_role'			=> '查看角色、权限',
									'write_role'		=> '修改角色、权限',
							)
					)
			),
	'customer'
			=> array(
					'title'		=> '前台用户管理',
					'actions'	=>
					array(
							array(
									'view_customer'			=> '查看前台用户',
									'write_customer'		=> '修改前台用户',
									
									'upgrade_vip'			=> 'Upgrade VIP',
									'verify_customer'		=> 'Verify Customers',
									'activate_customer'		=> 'Activate Customers',
									'archive_customer'		=> 'Archive Customers',
									'delete_customer'		=> 'Delete Customers',
									'view_customer_note'	=> 'View Notes',
									'edit_customer_note'	=> 'Edit Notes',
									'add_customer_note'		=> 'Add Notes',
									'remove_customer_note'	=> 'Remove Notes'
							)
					)
			),
	'admin'
			=> array(
					'title'		=> '后台管理员管理',
					'actions'	=>
					array(
							array(
									'view_admin'		=> '查看管理员用户',
									'write_admin'		=> '修改管理员用户（包含查看权限）',
							)
					)
			),
	'credit_level'
		=> array(
			'title'		=> '信用积分管理',
			'actions'   => 
			array(
				array(
						'view_credit_level'			=> '查看信用积分',
						'write_credit_level'			=> '修改信用积分',
					),
				)			
	),
	'finance'
		=> array(
			'title'		=> '资金管理',
			'actions'	=>
			array(
				array(
					'view_withdraw'		=> '查看提现记录',
					'audit_withdraw'	=> '审批提现记录',
				)
			)
	),
	'report'
		=> array(
			'title'		=> '统计报表',
			'actions'	=>
			array(
				array(
					'view_activity_log'		=> '查看管理员操作日志'						
				)						
			)
	),	
		
	'debt'
		=> array(
			'title'		=> '借款管理',
			'actions'	=>
			array(
				array(
					'view_debt'		    	=> '查看借款项目',
					'write_debt'	    	=> '审核、修改借款项目',
				),
			)
	),	
	'billing'
		=> array(
			'title'		=> 'Billing Managerment',
			'actions'	=>
			array(
				array(
					'view_bill'				=> 'View Bills',
					'add_bill'				=> 'Add Bill',
					'void_bill'				=> 'Void Bill',
					'clear_bill'			=> 'Clear Bill',
					'export_bill'			=> 'Export Bill'
				)
			)
	),
);