@extends('emails.layouts.basic')

@section('content')   
    <tr><td><p style="font-family: 'Helvetica Neue'; font-size: 26px; font-weight: bold; color: #363636; margin: 1em 0;">尊敬的用户，{{ $user }}</p></td></tr>

    <tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
			感谢您选择 Anying 系统换汇，您的交易已经成功提交了一段时间，但我们还没有收到您的存款。
		</p></td></tr>

	<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
			您可以选择从您的银行账户转账到 Anying 账户，请您在 24 小时内完成这笔交易。
	<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
			同时，我们也欢迎您随时联系我们<a style='color: #225ea2; text-decoration: none;' href='mailto:info@anying.com'>info@anying.com</a>.
	</p></td></tr>
@stop