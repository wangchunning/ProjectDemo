<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin:0">
        <table cellspacing="0" cellpadding='0' border='0' width='100%' align='center'>
            <tr>
                <td bgcolor='#d7d9dc'></td>
                <td bgcolor='#d7d9dc' style='padding-top: 50px; padding-bottom: 50px;'>
                    <table cellspacing="0" cellpadding='0' border='0' width='610' align='center'>
                        <tr><td colspan="5"><img height="80" src="{{ asset('assets/img/email/email-header.png') }}" style="display: block;" width="610"  /></td></tr>
                        <tr>
                            <td width="6" style='background-image: url(/assets/img/email/body-left.jpg); background-repeat: repeat-y no-repeat;'><img align="top" height="100%" src="{{ asset('assets/img/email/email-left.jpg') }}" style="height: 100%; display: block;" width="6"  /></td>
                            <td width="64" bgcolor='#ffffff'></td>
                            <td width="470" bgcolor='#ffffff'>
                                <table cellspacing="0" border='0' cellpadding='0'>
                                    <tr>
                                        <td width="470">
                                            <table cellspacing="0" cellpadding='0' border='0'>

                                            @yield('content')
                                            
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width='470'>
                                            <p style='font-family: Helvetica; font-size: 16px; color: #363636; line-height: 22px; margin-bottom: 10px'>敬礼</p>
                                            <p style='font-family: Helvetica; font-size: 16px; color: #363636; margin-top: 0;'>Anying 团队</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="64" bgcolor='#ffffff'></td>
                            <td width="6" style='background-image: url(/assets/img/email/body-right.jpg); background-repeat: repeat-y no-repeat;'><img align="top" height="100%" src="{{ asset('assets/img/email/email-right.jpg') }}" style="height: 100%; display: block;" width="6"  /></td>
                        </tr>
                        <tr><td colspan='5'><img height="82" src="{{ asset('assets/img/email/email-bottom.jpg') }}" style="display: block" width="610"  /></td></tr>
                        <tr><td colspan='5'><p style='font-size: 12px; color: #5d5d5d; line-height: 14px; font-family: &quot;Helvetica Neue&quot;; margin: 1em 0 0.5em; text-align: center;'>如果不想再收到来自 Anying 的邮件，请更改您的<a style='color: #225ea2; text-decoration: underline;' href='#'>邮箱设置</a>。</p></td></tr>
                        <tr><td colspan='5'><p style='font-size: 10px; color: #5d5d5d; line-height: 14px; font-family: &quot;Helvetica Neue&quot;; margin: 0 0 0.5em; text-align: center;'>&#xA9; {{ date('Y') }} Anying Inc.</p></td></tr>
                  </table>
                </td>
                <td bgcolor='#d7d9dc'></td>
            </tr>
        </table>
    </body>
</html>                                
