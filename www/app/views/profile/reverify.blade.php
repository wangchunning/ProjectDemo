@section('title')
个人信息验证
@stop

@section('body-tag')
<body class="profile">
@stop

@section('content')
    <div class="container flat-panel">
        <div class="all-radius white-box-body">
        <h2><i class="fa fa-list-alt"></i>  身份验证</h2>
        <hr class="mt0">
            @if ($errors->has())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif 
            @if (!empty($user->verify->photo_id_request))
            <div class="alert alert-danger">
                <p>您的 "身份 ID 文件"不符合 Anying 的要求, 请重新上传有效的文件 </p>                
            </div>
            @endif  
            @if (!empty($user->verify->addr_proof_request))
            <div class="alert alert-danger">
                <p>Your "地址证明文件"不符合 Anying 的要求, 请重新上传有效的文件 </p>                
            </div>
            @endif                         
            {{ Form::open(array('url' => 'profile/verify', 'role' => 'form', 'id' => 'profileVerifyFrm')) }}
                <p>为了能进行换汇操作，Anying 需要您提供身份验证文件
                </p>
                <span class="help-block">请上传如下文件：</span>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="mt40 upload-field flat-panel row">
                            <div class="col-sm-6" id="photo-id-container">
                                <h4>带有照片的身份证件</h4>
                                <ul>
                                    <li>驾照, 或</li>
                                    <li>护照</li>
                                </ul>
                                @if ( ! $user->verify->photo_id_verified)
                                    <button id="photo-id-upload" type="button" class="btn btn-default">选择文件</button>
                                @endif
                                <p id="photo-id-name" class="dblock no-file color-737">
                                @if ( ! Input::old('photo_id_file', $user->verify->photo_id_file))
                                    <span class="ml10">未选择任何文件</span>
                                @endif
                                </p>
                            </div>

                            <div class="col-sm-6 img-field" id="photo-id-field">
                                @if (Input::old('photo_id_file', $user->verify->photo_id_file))
                                    <input type="hidden" name="photo_id_file" value="{{ Input::old('photo_id_file', $user->verify->photo_id_file) }}">
                                    <a class="group" rel="group_photo" href="/{{ Input::old('photo_id_file', $user->verify->photo_id_file) }}"><img src="/{{ Input::old('photo_id_file', $user->verify->photo_id_file) }}" class="img-responsive"></a>
                                    <p>
                                        @if ($user->verify->photo_id_verified)
                                        <span class="label label-primary">已通过验证</span>
                                        @else
                                        <a class="remove" href="javascript:;"><i class="fa fa-times remove"></i>删除</a>
                                        @endif
                                    </p>
                                @else
                                    <a class="group" rel="group_photo" href="/assets/img/passport.png"><img src="/assets/img/passport.png" class="img-responsive"></a><br>
                                    <a class="group" rel="group_photo" href="/assets/img/driverslicense.png"><img src="/assets/img/driverslicense.png" class="img-responsive"></a>
                                @endif  
                            </div>              
                        </div>
                        <div class="mt40 upload-field mb40 flat-panel row">
                            <div class="col-sm-6" id="addr-proof-container">
                                <h4>地址证明文件</h4>
                                <ul>
                                    <li>水电煤账单, 或</li>
                                    <li>政府发放的证件</li>
                                </ul>
                                @if ( ! $user->verify->addr_proof_verified)
                                    <button id="addr-proof-upload" type="button" class="btn btn-default">选择文件</button>
                                @endif
                                
                                <p id="addr-proof-name" class="dblock no-file color-737">
                                @if ( ! Input::old('addr_proof_file', $user->verify->addr_proof_file))    
                                    <span class="ml10" >未选择任何文件</span>
                                @endif
                                </p>
                            </div>
                            <div class="col-sm-6 img-field" id="addr-proof-field">
                                @if (Input::old('addr_proof_file', $user->verify->addr_proof_file))
                                    <input type="hidden" name="addr_proof_file" value="{{ Input::old('addr_proof_file', $user->verify->addr_proof_file) }}">
                                    <a class="group" rel="group_addr_proof" href="/{{ Input::old('addr_proof_file', $user->verify->addr_proof_file) }}"><img src="/{{ Input::old('addr_proof_file', $user->verify->addr_proof_file) }}" class="img-responsive"></a>
                                    @if ($user->verify->addr_proof_verified)
                                    <span class="label label-primary">已通过验证</span>
                                    @else
                                    <p><a class="remove" href="javascript:;"><i class="fa fa-times"></i>删除</a></p>
                                    @endif
                                @else
                                    <a class="group" rel="group_addr_proof" href="/assets/img/water.png"><img src="/assets/img/water.png" class="img-responsive"></a>
                                @endif                        
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 grey-radius">
                        <div>
                            <h4>上传图片向导</h4>
                            <ul>
                                <li><i class="fa fa-check"></i> 图片文件格式必须是 JPG,JPEG,GIF,PNG</li>
                                <li><i class="fa fa-check"></i> 图片文件大小必须小于 5 MB</li>
                                <li><i class="fa fa-check"></i> 图片文件必须能够清晰的显示您的个人或地址信息</li>
                            </ul>
                        </div>
                        <hr class="double-line">
                        <div>
                            <h4 class="green">请您</h4>
                            <ul>
                                <li><i class="green fa fa-check"></i> 图片文件清晰</li>
                                <li><i class="green fa fa-check"></i> 保证能看清您的照片</li>
                                <li><i class="green fa fa-check"></i> 保证能看清您的地址信息</li>
                            </ul>
                        </div>
                        <hr class="double-line">
                        <div>
                            <h4 class="red">请您不要</h4>
                            <ul>
                                <li><i class="red fa fa-times"></i> 使用模糊的图片</li>
                                <li><i class="red fa fa-times"></i> 无法明确标识身份的文件</li>
                            </ul>
                        </div>
                        <hr class="double-line">
                        <div>
                        <p>
                        请确保您所提供的证明文件是最近 6 个月的并且是合法的。文件上的名字和地址必须和您在 Anying 的注册信息相对应
                        </p>                
                        <span class="help-block"><strong>Note：</strong><em>您的个人信息我们不会提供给任何第三方</em></span>
                        </div>
                    </div>
                </div>
                
                <hr>
                @if ( ! $user->verify->photo_id_verified  OR ! $user->verify->addr_proof_verified)
                <button type="submit" class="btn btn-primary btn-lg">提交</button> 或, {{ link_to('/home', '取消') }} 
                @endif          
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('inline-js')
{{ HTML::script('assets/plupload/plupload.full.js') }}
{{ HTML::script('assets/js/lib/profile.js')}}
<script>
$(function(){
    photoIdUpload();
    addrProofUpload();
    $('.upload-field').delegate('.remove', 'click', function(){
        var field = $(this).parents('.upload-field');
        field.find('input[type="hidden"]').val('');
        field.find('img').attr('src','').hide();
        $(this).hide();
        field.children('div').find('.no-file').html('<span class="ml10">未选择任何文件</span>');
    });

    $(".upload-field .group").fancybox();
})
</script>
@stop