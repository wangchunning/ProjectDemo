@extends('emails.layouts.basic')

@section('content')
    <tr><td><p style="font-family: 'Helvetica Neue'; font-size: 26px; font-weight: bold; color: #363636; margin: 1em 0;">尊敬的用户！</p></td></tr>
    <tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
        <strong> {{ $manager }} </strong> 邀请您以 <strong>{{ $roles }}</strong> 的身份管理 Anying 系统</p>
        <p>如果您接受邀请，请点击下面的链接:</p>
    </td>
    </tr>
    <tr>
        <td width='435'>
            <p style='font-family: Helvetica; font-size: 16px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
                <span style="color: #225ea2; text-decoration: none; display: block; max-width: 435px; word-wrap: break-word;">
                    <a href="{{ $url }}" target="_blank">接受邀请</a>
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