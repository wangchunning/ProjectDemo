  
            <div id="lock-rate-pannel" class="flat-panel">        
                <h2><i class="fa fa-money"></i>汇率报价</h2>
                <div>            
                    <div class="row">
                        {{ Form::open(array('url' => 'lockrate')) }}
                        <div class="col-sm-5 converter-pannel">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>手持货币</label>
                                    {{ currencySelecter('currency_have') }}
                                </div>
                                <div class="col-sm-6">
                                    <label>购入货币</label>
                                    <select id="currency_want" name="currency_want" class="form-control">
                                    </select>
                                </div> 
                            </div>   
                            <div class="text-center"><span class="equal-label" id="swap_currency_btn">=</span></div>                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>手持金额</label>
                                    <input type="text" name="amount_have" id="amount_have" value="1" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label>购入金额</label>
                                    <input type="text" name="amount_want" id="amount_want" value="1" class="form-control">                                                              
                                </div>
                            </div>
                            <div class="rate-pannel mt20">
                                <div class="spot-rate all-radius">
                                    <p><strong class="f16p">交易汇率：</strong><span id="converter-label">AUD/AUD</span> <span id="rate-label">1</span><span class="label label-warning" id="count-down">30s</span></p>                            
                                    <input type="hidden" id="spot-rate" name="spot-rate">
                                </div>
                                <small class="help-block">* 交易汇率每 30 秒刷新一次</small>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg btn-primary">锁定汇率</button>
                                </div>                                          
                            </div> 
                        </div>
                        {{ Form::close() }}
                        <div class="col-sm-7">
                            <div id="charter" style="height:300px;"></div>                          
                        </div>                    
                    </div>
                </div>
            </div>        