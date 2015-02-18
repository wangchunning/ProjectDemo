@extends('emails.layouts.basic')

@section('content')   
    <tr><td><p style="font-family: 'Helvetica Neue'; font-size: 26px; font-weight: bold; color: #363636; margin: 1em 0;">Dear {{ $user }},</p></td></tr>

    <tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>

		很抱歉，您提交的 <b>{{ $type }}</b> 文件不符合 Anying 的要求。请重新上传您的文件。
	</p></td></tr>


<tr><td width='435'>
<p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 10px; margin-bottom: 0.5em'>
  上传文件向导：
</p>
<ul style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
  <li>上传文件必须是 JPG, GIF, PNG 或 BMP 格式</li>
  <li>文件大小必须小于 5MB</li>
  <li>上传文件必须能够清晰的看清您的身份或地址信息</li>
</ul>
</td></tr>

<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1em'> <a style='color: #225ea2; text-decoration: none;' href='{{ $url }}' target="_blank">上传文件</a> </p></td></tr>
<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
	我们将审核您的图片文件，同时保留在必要时修改文件的权利。
</p></td></tr>

<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
	我们将尽快验证您所提交的文件，一般情况是几个小时，请您耐心等待。一旦您的文件通过验证，您就可以使用 Anying 换汇系统的全部功能了。
</p></td></tr>
<tr><td width='435'><p style='font-family: Helvetica; font-size: 14px; color: #363636; line-height: 22px; margin-bottom: 1.3em'>
	如果您的文件没有达到“上传文件向导”的标准，我们将不会通过您的验证请求。
</p></td></tr>

@stop