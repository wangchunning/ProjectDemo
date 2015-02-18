@extends('emails.layouts.basic')

@section('content')   
    <tr><td><p style="font-family: 'Helvetica Neue'; font-size: 26px; font-weight: bold; color: #363636; margin: 1em 0;">尊敬的用户，{{ $user }}</p></td></tr>

    <tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1em'>恭喜您！您的文件已经通过 Anying 的验证。</a></p></td></tr>

	<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1em'> <a style='color: #225ea2; text-decoration: none;' href='{{ $url }}' target="_blank">开始使用 Anying！</a> 体验不一样的换汇之旅！</p></td></tr>
@stop