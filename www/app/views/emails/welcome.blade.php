@extends('emails.layouts.basic')

@section('content')   
    <tr><td><p style="font-family: 'Helvetica Neue'; font-size: 26px; font-weight: bold; color: #363636; margin: 1em 0;">尊敬的用户，{{ $user }}</p></td></tr>

    <tr><td width='435'><p style='font-family: Helvetica; font-size: 24px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>欢迎使用 Anying！</p></td></tr>

	<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
		Anying 换汇是业界领先的换汇平台，我们提供安全、高效的交易方式，使您不必担心传统换汇带来的风险。现在您已成功注册了 Anying 系统。
		

	</p></td></tr>

	<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
		您使用 Email <a href="mailto:{{ $email }}">{{ $email }}</a> 作为联系方式及登录 Anying 系统的用户名。
		我们需要验证您 Email 的真实性，请点击下面的链接，登录 Anying 系统。
	</p></td></tr>

	<tr><td width='435'><p style='font-family: Helvetica; font-size: 16px; color: #363636; line-height: 22px; margin-bottom: 1.3em'><span style="color: #225ea2; text-decoration: none; display: block; max-width: 435px; word-wrap: break-word;">
  		<a href="{{ $url }}" target="_blank">验证 Email ></a></span></p></td>
  	</tr>

	<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
		请您<b>务必</b>先激活您的 Email。<br>
		如果您对系统的功能、使用有问题，欢迎您电话我们，我们专业的客服人员会指导您完成操作。
		
	</p></td></tr>

	<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
		如果您有任何问题请联系我们 
		<a style='color: #225ea2; text-decoration: none;' href='mailto:info@anying.com'>info@anying.com</a>
	</p></td></tr>
@stop