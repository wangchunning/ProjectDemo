@section('title')
{{ $type }} Transaction Detail
@stop

@section('content')
    @include('admin.transaction.subnav')
    <div class="container">       
        <div class="white-box-body all-radius transaction-detail">
            <h3 class="mt0">
                Transaction Detail
                <span class="back-btn f16p"><a href="javascript:;" onclick="javascript:history.back(-1);">Back</a></span>
            </h3>
            @if ($detail->status == 'Canceled' OR $detail->status == 'Voided')
            <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p>This transaction requirement was {{ $detail->status }}.</p>
            </div>
            @endif

            @if (Session::has('action'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>Success!</h4>
                <p>Your changes have been successfully updated.</p>
            </div>
            @endif
            @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('error') }}
            </div>
            @endif  
            <div class="row">
                <div class="col-sm-3 pull-right">
                    <div class="tcenter">
                        @if ($detail->status == 'Waiting for fund' AND overdueCheck($detail->created_at))
                            <div class="transaction-status label-danger">
                            <p class="f20p">Status</p>
                            <p>{{ $detail->status }}</p>
                        </div>
                        <p class="red tcenter">{{ overdueCheck($detail->created_at )}}</p>
                        @else
                        <div class="transaction-status label-{{ transactionStatusStyle($detail->status) }}">
                            <p class="f20p">Status</p>
                            <p>{{ $detail->status }}</p>
                        </div>
                        @endif
                    </div>
                    @if (check_perms('email_reminder,sms_reminder', 'OR', FALSE))
                        @if ($detail->status == 'Waiting for fund')
                        <div>
                            <h5>Reminder:</h5>
                            @if (check_perm('sms_reminder', FALSE))
                            <p><span class="fa fa-mobile"></span> <a class="underline">SMS Reminder</a></p>
                            @endif
                            @if (check_perm('email_reminder', FALSE))
                            <p><span class="fa fa-envelope"></span> <a class="underline" href="javascript:;" data-id="{{$detail->id}}" id="email-reminder">Email Reminder</a></p>
                            @endif
                        </div>
                        @endif
                    @endif

                    @if (check_perms('print,download_pdf', 'OR', FALSE))
                    <div>
                        <h5>Transaction:</h5>
                        @if (check_perm('print', FALSE))
                        <p><span class="fa fa-print"></span> <a class="underline">Print</a></p>
                        @endif
                        @if (check_perm('download_pdf', FALSE))
                        <p><span class="fa fa-download"></span> <a class="underline">Download PDF</a></p>
                        @endif
                    </div>
                    @endif

                    @if (action_check($detail->id, $detail->receipt_id))
                    <div class="transaction-action">
                        <h5>Action:</h5>
                        @if (trans_perm($detail->type, 'clear', FALSE))
                        <p>
                            <a class="btn btn-primary" href="/admin/transaction/clear/{{ $detail->id }}">Clear</a>
                        </p>
                        @endif

                        @if (trans_perm($detail->type, 'voided', FALSE))
                        <p>
                            <a class="btn btn-default" href="/admin/transaction/cancel/Voided/{{ $detail->id }}">Void</a>
                        </p>
                        @endif

                        @if (trans_perm($detail->type, 'cancel', FALSE))
                        <p>
                            <a class="btn btn-default" href="/admin/transaction/cancel/Canceled/{{ $detail->id }}">Cancel</a>
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="col-sm-9 pull-left">
                    <div>
                        <p>{{ date_word($detail->created_at) }}</p>
                        <h4>{{ $type }} Transaction Number: <span class="ml15">{{ $detail->id }}</span></h4>
                    </div>
                    <hr>
                    @if ($detail->type == 'Deposit')
                    <div>
                        <h5>Transfer To：</h5>   
                        <div>
                            {{ currencyName($currency) }} Balance
                        </div>
                    </div>
                    <hr>
                    <div class="flat-panel">
                        <h5>Payment Details</h5>
                        <div class="row ml0 mr0 add-fund-detail">
                            <div class="col-sm-6">
                                <strong>Total Amount：</strong><span class="highlight">{{ currencyFormat($currency, $amount, TRUE) }}</span>
                            </div>
                            <div class="col-sm-6">
                                <strong>Due by：</strong><span class="highlight">{{ Date('d/m/Y', strtotime($detail->created_at) + 172800) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- deposit bank info -->
                    <div class="flat-panel mt40">
                        <h5>Bank</h5>
                        <div class="pannel-body table-responsive bank-panel">             
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="40"></th>
                                        <th>Account Number</th>
                                        <th>Bank Name</th>
                                        <th>Balance</th>
                                        <th>Currency</th> 
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if ($detail->bank)
                                    @foreach ($detail->bank as $b)
                                    <tr class="{{ $b->item_id }}" data-id="{{ $detail->id }}">
                                        <td class="no-padding">
                                            @if (action_check($detail->id, $detail->receipt_id))
                                            <div class="actions">
                                                @if (trans_perm($detail->type, 'edit_bank', FALSE))
                                                <a href="javascript:;" title="Edit" class="bank-edit-btn fa fa-pencil"></a>
                                                @endif
                                                @if (trans_perm($detail->type, 'remove_bank', FALSE))
                                                <a href="javascript:;" title="Remove" class="bank-remove-btn fa fa-minus-circle"></a>
                                                @endif                             
                                            </div>
                                            @endif
                                        </td>
                                        <td>{{ $b->account_number }}</td>
                                        <td>{{ sprintf('%s %s', $b->bank, $b->branch ? '(' . $b->branch .')': '') }}</td>
                                        <td>{{ number_format($b->getAmount($currency), 2) }}</td>
                                        <td>{{ $currency }}</td>
                                        <td class="editable">{{ $b->amount }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            @if (action_check($detail->id, $detail->receipt_id))
                                @if (trans_perm($detail->type, 'edit_bank', FALSE))
                                <div>
                                    @if ($detail->diff_amount)
                                    <span class="btn btn-default" data-id="{{ $detail->id }}" id="add-amount"> Add Amount </span>
                                    @endif
                                    
                                    <div id="add-bank-panel" data-id="{{ $detail->id }}" class="hidden">
                                        <input type="hidden" id="max-amount" value="{{ $detail->diff_amount }}">
                                        <div class="row">
                                            <div class="col-sm-3">Choose a Bank</div>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="bank-select"></select>
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-3">Amount</div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control number-only" id="new-amount" value="{{ $detail->diff_amount }}">                                    
                                            </div>
                                        </div>
                                        <div>
                                            <a href="javascript:;" class="btn btn-primary" id="save-bank-btn">Save</a>
                                            <a href="javascript:;" class="btn btn-default" id="cancel-btn">Cancel</a>                                                                                                                                        
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <!-- /deposit bank info -->

                    @elseif ($detail->type == 'Withdraw')
                    <h5>Withdraw To：</h5>   
                    <div class="row mt20">
                        <div class="col-sm-6">
                            <p><strong>Account Name</strong><br>{{ $recipient['account_name'] }}</p>
                            <p>
                                <strong>Bank Details</strong>
                            @if (isset($recipient['country']))
                                <br>
                                Country： {{ $recipient['country'] }}
                            @endif
                            @if ($recipient['swift_code'])
                                <br>
                                SWIFT/BIC Code：  {{ $recipient['swift_code'] }} 
                            @endif
                                <br>
                                Bank's Name：{{ sprintf('%s %s', $recipient['bank_name'], $recipient['branch_name']) }}
                                <br>
                                Account Number：{{ sprintf('%s %s', $recipient['account_bsb'], $recipient['account_number']) }}
                                <br> 
                            @if (isset($recipient['street_number']))    
                                Bank Address ：{{ sprintf('%s %s %s %s %s %s', 
                                                        $recipient['unit_number'],
                                                        $recipient['street_number'],
                                                        $recipient['street'],
                                                        $recipient['city'],
                                                        $recipient['state'],
                                                        $recipient['postcode']
                                                        )
                                                 }} 
                                <br>
                            @endif      
                            </p>                                                  
                        </div>
                        <div class="col-sm-6">
                            <p>
                                <strong>Recipient Address：</strong>
                                <br>
                                {{ $recipient['address'] }}
                            @if ($recipient['address_2'])
                                <br>
                                {{ $recipient['address_2'] }}
                            @endif
                            @if (isset($recipient['address_3']) AND $recipient['address_3'])
                                <br>
                                {{ $recipient['address_3'] }}
                            @endif
                                <br>
                                {{ $recipient['transfer_country'] }}
                                <br>
                                {{ $recipient['postcode'] }}
                            </p>
                            <p>
                                <strong>Phone Number</strong>
                                <br>
                                {{ sprintf('+%s %s', $recipient['phone_code'], $recipient['phone'])}}
                            </p>
                        </div>
                    </div>
                    <div class="flat-panel">
                        <h5>Withdraw Details</h5>
                        <table class="table deal-detail-table">
                            <tbody>
                                <tr>
                                    <td>Amount</td>
                                </tr>
                                <tr>
                                    <td class="highlight">{{ currencyFormat($currency, $amount, TRUE) }}</td>
                                </tr>
                                @if (isset($fee_total))
                                <?php $converted_fee_arr = $fee_total; ?>
                                <tr>
                                    <td>
                                        <strong>Transaction Fee: </strong>
                                        <span class="highlight">
                                            {{ currencyFormat($converted_fee_arr['convert_fee_currency'], 
                                                        $fee['total'] * $converted_fee_arr['convert_fee_rate'], TRUE) }}
                                        </span>
                                    </td>
                                </tr> 
                                @else
                                <tr>
                                    <td>
                                        <strong>Transaction Fee: </strong>
                                        <span class="highlight">
                                            {{ currencyFormat($currency, $fee['total'], TRUE) }}
                                        </span>
                                    </td>
                                </tr>                                
                                @endif                 
                            </tbody>
                            @if ($receipt->type == 'Withdraw')
                            <tfoot>
                                <!--tr>
                                    <td colspan="3">
                                        <div class="text-right">
                                            <span class="amount-title">From Balance：</span>
                                            <span class="amount highlight">
                                                {{-- currencyFormat($currency, $amount + $receipt_data['fee']['total'], TRUE) --}}
                                            </span><br>
                                        </div>
                                    </td>
                                </tr-->
                            </tfoot>
                            @endif
                        </table>
                    </div>

                    <!-- withdraw bank info -->
                    <div class="flat-panel mt40">
                        <h5>Bank</h5>
                        <div class="pannel-body table-responsive bank-panel">             
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="40"></th>
                                        <th>Account Number</th>
                                        <th>Bank Name</th>
                                        <th>Balance</th>
                                        <th>Currency</th>                                        
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if ($detail->bank)
                                    @foreach ($detail->bank as $b)
                                    <tr class="{{ $b->item_id }}" data-id="{{ $detail->id }}">
                                        <td class="no-padding">
                                            @if (action_check($detail->id, $detail->receipt_id))
                                            <div class="actions">
                                                @if (trans_perm($detail->type, 'edit_bank', FALSE))
                                                <a href="javascript:;" title="Edit" class="bank-edit-btn fa fa-pencil"></a>
                                                @endif
                                                @if (trans_perm($detail->type, 'remove_bank', FALSE))
                                                <a href="javascript:;" title="Remove" class="bank-remove-btn fa fa-minus-circle"></a>
                                                @endif                             
                                            </div>
                                            @endif
                                        </td>
                                        <td>{{ $b->account_number }}</td>
                                        <td>{{ sprintf('%s %s', $b->bank, $b->branch ? '(' . $b->branch .')': '') }}</td>
                                        <td>{{ number_format($b->getAmount($b->currency), 2) }}</td>
                                        <td>{{ $b->currency }}</td>
                                        <td class="editable">{{ $b->amount }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            @if (action_check($detail->id, $detail->receipt_id))
                                @if (trans_perm($detail->type, 'edit_bank', FALSE))
                                <div>
                                    {{-- @if ($detail->diff_amount) --}}
                                    <span class="btn btn-default" data-id="{{ $detail->id }}" id="add-amount"> Add Amount </span>
                                    {{-- @endif --}}
                                    <div id="add-bank-panel" data-id="{{ $detail->id }}" class="hidden">
                                        <input type="hidden" id="max-amount" value="{{ $detail->diff_amount }}">
                                        <div class="row">
                                            <div class="col-sm-3">Choose a Bank</div>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="bank-select">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-3">Amount</div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control number-only" id="new-amount" value="{{-- $detail->diff_amount --}}">                                    
                                            </div>
                                        </div>
                                        <div>
                                            <a href="javascript:;" class="btn btn-primary" id="save-bank-btn">Save</a>
                                            <a href="javascript:;" class="btn btn-default" id="cancel-btn">Cancel</a>                                                                                                                                        
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <!-- /withdraw bank info -->

                    @else
                    <div class="flat-panel">
                        <h5>Deal Details</h5>
                        <div class="deal-detail">
                            <div class="deal-detail-title">Transferring {{ currencyFormat($lock_currency_have, $lock_amount, TRUE) }}</div>
                            <table class="table deal-detail-table">
                                <tbody>
                                    <tr>
                                        <td>Local Amount</td>
                                        <td>Exchange Rate</td>
                                        <td>Foreign Amount</td>
                                    </tr>
                                    <tr>
                                        <td>{{ currencyFormat($lock_currency_have, $lock_amount, TRUE) }}</td>
                                        <td>{{ sprintf('1 %s = %s %s', $lock_currency_have, $lock_rate, $lock_currency_want) }}</td>
                                        <td>{{ currencyFormat($lock_currency_want, $lock_amount * $lock_rate, TRUE) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="deal-detail-footer text-right">
                                Total Recharge : <strong>{{ sprintf('%s %s', $lock_amount * $lock_rate , $lock_currency_want) }}</strong>
                            </div>
                        </div>
                        <hr>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row user-detail mt20">
                <h3 class="col-sm-12">{{ $detail->user->full_name }} 
                    {{ $detail->user->business ? sprintf('<span class="f16p">%s</span>', $detail->user->business->business_name) : '' }} 
                    {{ $detail->user->profile->status == 'unverified' ? '<span class="f16p color-error">(unverified)</span>' : '<span class="f16p color-468">(verified)</span>'}}                            
                    @if (is_vip($detail->user->profile->vip_type))                             
                    <span class="f13p label {{ vip_label_class($detail->user->profile->vip_type) }}">
                        {{ $detail->user->profile->vip_type }}
                    </span>
                    @endif                    
                </h3>
                <hr class="mt0 ml15 mr15">
                <div class="col-xs-12 col-md-8 row">
                    <div class="col-xs-6 col-md-6">E: {{ $detail->user->email }}</div>
                    <div class="col-xs-6 col-md-6">A: {{ sprintf('%s <br> %s %s %s %s', $detail->user->profile->unit_number, $detail->user->profile->street_number, $detail->user->profile->street, $detail->user->profile->city, $detail->user->profile->postcode) }}</div>
                    <div class="col-xs-6 col-md-6">M: {{ $detail->user->verify->security_verified ? $detail->user->verify->security_mobile : '' }}</div>
                    <div class="col-xs-6 col-md-6">B: {{ sprintf('%s/%s/%s', $detail->user->profile->birth_day, $detail->user->profile->birth_month, $detail->user->profile->birth_year) }}</div>
                </div>
            </div>
            <div class="row mt20">
                <h3 class="col-sm-12">Related Receipt</h3>
                <div class="col-sm-12">
                    <hr class="mt0">
                    <div>
                        <p>{{ date_word($receipt->created_at) }}</p>
                        <h4>Receipt Number: <span class="ml15">{{ link_to('/admin/transaction/receipt/' . $receipt->id, $receipt->id) }}</span></h4>
                    </div>
                </div>
            </div>

            @if ($transactions->toArray())
            <h3 class="mt40">Related Transactions</h3>
            <hr class="mt0">
            <div class="table-responsive">
                <table class="table table-hover table-striped transaction">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Transaction ID</th>
                            <th>Type</th>
                            <th>Payment Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tran)
                            <tr>
                                <td>
                                    {{ date_word($tran->created_at) }}
                                </td>
                                <td>{{ link_to('/admin/transaction?uid='.$tran->uid, pretty_str($tran->user->full_name)) }}</td>
                                <td>
                                    @if (trans_perm($tran->type, NULL, FALSE))
                                    {{ link_to('/admin/transaction/detail/'.$tran->id, $tran->id) }}
                                    @else
                                    {{ $tran->id }}
                                    @endif
                                </td>
                                <td>{{ $tran->type }}</td>
                                <td>
                                    @if ($tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                                        <span class="label label-danger">{{ sprintf('<i class="fa fa-exclamation-triangle"></i> %s', overdueCheck($tran->created_at)) }}</span>
                                    @else
                                        <span class="label label-{{ transactionStatusStyle($tran->status) }}">{{ $tran->status }}</span>
                                    @endif
                                </td>
                                <td class="amount">
                                    @if($tran->type == 'Deposit')
                                        <font class="green">+{{ $tran->amountFormat }}</font>
                                    @elseif($tran->type == 'FX Deal')
                                        <font class="green">{{ $tran->amountFormat }}</font>
                                    @elseif($tran->type == 'Withdraw')
                                        <font class="red">-{{ $tran->amountFormat }}</font>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if (check_perm('view_note', FALSE))
            <div class="mt20" id="note-panel" data-uid="{{ $detail->uid }}" data-type="transaction" data-item="{{ $detail->id }}">
                <h4>Notes 
                    @if (check_perm('add_note', FALSE))
                    <span><a class="underline add-note" href="javascript:;">+Add</a></span>
                    @endif
                </h4>
                <hr class="mt0">
                @foreach ($detail->notes as $note)
                <div class="desc">
                    <p>
                        <span class="f16p">{{ $note->operator->full_name }}</span> 
                        @if (check_perm('edit_note', FALSE))
                        <a class="underline desc-edit" data-id="{{ $note->id }}" href="javascript:;">edit</a> 
                        @endif
                        @if (check_perm('remove_note', FALSE))
                        <a class="underline desc-delete" data-id="{{ $note->id }}" href="javascript:;">delete</a> 
                        @endif
                        <span class="pull-right">{{ date_word($note->created_at) }}</span>
                    </p>
                    <div>
                        <p class="desc-show">{{ $note->desc }}</p>
                        <div class="desc-input">
                            <div>
                                <input type="text" class="tran-desc form-control" name="desc" value="{{ $note->desc }}">
                                <a href="javascript:;" class="btn btn-primary save-desc-btn">Update</a> <a href="javascript:;" class="btn btn-default cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="desc-input mt10" id="new-note">
                    <div>
                        <input type="text" class="tran-desc form-control" name="desc">
                        <a href="javascript:;" class="btn btn-primary save-desc-btn">Add</a> <a href="javascript:;" class="btn btn-default cancel-btn">Cancel</a>
                    </div>
                </div>

                <div class="inline color-error" id="form-error-tip"></div>
            </div>
            @endif

            <!-- Activity -->
            <div id="activity-panel" class="mt20">
                <h4>Activity</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tbody>
                        {{ $activities->isEmpty() ? '<tr><td colspan="4" align="center">- No Records to Display -</td></tr>' : '' }}
                        @foreach ($activities as $activity)                            
                            <tr>
                                <td>{{ $activity->user->full_name }}</td>
                                <td>{{ $activity->action }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ date_word($activity->created_at) }}</td>                                
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- //Activity -->

        </div>
    </div>

@stop

@section('inline-css')
{{ HTML::style('assets/css/selectize.bootstrap3.css') }}
@stop

@section('inline-js')
{{ HTML::script('assets/js/selectize.js') }}
{{ HTML::script('assets/js/lib/admin/transaction.js') }}
{{ HTML::script('assets/js/lib/admin/note.js') }}
<script>
    $(function() {
        $( ".transaction-action a" ).on('click', function(){
            $.fancybox.showLoading();
            $(this).attr('disabled', 'disabled');
        });
    });
</script>
@stop
