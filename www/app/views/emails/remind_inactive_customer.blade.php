@extends('emails.layouts.basic')

@section('content')   
    <tr><td><p style="font-family: 'Helvetica Neue'; font-size: 26px; font-weight: bold; color: #363636; margin: 1em 0;">
        尊敬的客户，{{ $user }}</p></td></tr>

    <tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
            感谢您选择 Anying 换汇系统。您已经很长一段时间没有登录 Anying 系统了。
    </p></td></tr>

    <tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
            为了保持您的账户出于激活状态，请重新登录 Anying 系统，并提交所有必须的文件。
    </p></td></tr>
    <tr>
        <td width='435'>
            <p style='font-family: Helvetica; font-size: 16px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
                <span style="color: #225ea2; text-decoration: none; display: block; max-width: 435px; word-wrap: break-word;">
                    <a href="{{ $url }}" target="_blank">返回 Anying 系统</a>
                </span>
            </p>
        </td>
    </tr>
    <tr>
        <td width='435'>
            <p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
                如果您还有其它问题，您可以在 <a href="http://www.anying.com/">FAQ 页面</a> 找到更多答案。 
                同时，我们也欢迎您随时联系我们 <a style='color: #225ea2; text-decoration: none;' href='mailto:info@anying.com'>info@anying.com</a>
            </p>
        </td>
    </tr>
@stop
