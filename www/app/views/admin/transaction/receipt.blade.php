@section('title')
Related Receipt
@stop

@section('content')
    @include('admin.transaction.subnav')
    <div class="container">       
        <div class="white-box-body all-radius transaction-detail">
            <h3 class="mt0">
                Related Receipt
                <span class="back-btn f16p"><a href="javascript:;" onclick="javascript:history.back(-1);">Back</a> </span>
            </h3>
            <div class="row">
                <div class="col-sm-3 pull-right">
                    <div class="tcenter">
                    <?php $tran = $receipt_obj->transaction('Deposit'); ?>
                    @if ($tran AND $tran->status == 'Waiting for fund' AND overdueCheck($tran->created_at))
                        <div class="receipt-status label-danger">
                            <p class="f20p">Status</p>
                            <p>{{ $receipt['status'] }}</p>
                        </div>
                        <p class="red tcenter">{{ overdueCheck($tran->created_at) }}</p>
                    @else
                        <div class="receipt-status label-{{ receiptStatusStyle($receipt['status']) }}">
                            <p class="f20p">Status</p>
                            <p>{{ $receipt['status'] }}</p>
                        </div>
                    @endif
                    </div>
                    @if (check_perms('print,download_pdf,email_reminder', 'OR', FALSE))
                    <div>
                        <h5>Receipt:</h5>
                        @if (check_perm('email_reminder', FALSE))
                            @if ($tran AND $tran->status == 'Waiting for fund')
                            <p><span class="fa fa-envelope"></span> <a class="underline" href="javascript:;" data-id="{{$tran->id}}" id="email-reminder">Email Reminder</a></p>
                            @endif
                        @endif
                        @if (check_perm('print', FALSE))
                        <p><span class="fa fa-print"></span> <a class="underline">Print</a></p>
                        @endif
                        @if (check_perm('download_pdf', FALSE))
                        <p><span class="fa fa-download"></span> <a class="underline">Download PDF</a></p>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="col-sm-9 pull-left">
                    <div>
                        <p>{{ date_word($receipt['created_at']) }}</p>
                        <h4>Receipt Number: <span class="ml15">{{ $receipt['id'] }}</span></h4>
                    </div>

                    @if ($receipt['type'] == 'Deposit')
                    <hr>
                    <div>
                        <h5>Transfer To：</h5>   
                        <div>
                            {{ currencyName($deposit['currency']) }} Balance
                        </div>
                    </div>
                    <hr>
                    <div class="flat-panel">
                        <h5>Payment Details</h5>
                        <div class="row ml0 mr0 add-fund-detail">
                            <div class="col-sm-6">
                                <strong>Total Amount：</strong><span class="highlight">{{ currencyFormat($deposit['currency'], $deposit['amount'], TRUE) }}</span>
                            </div>
                            <div class="col-sm-6">
                                <strong>Due by：</strong><span class="highlight">{{ Date('d/m/Y', strtotime($receipt['created_at']) + 172800) }}</span>
                            </div>
                        </div>
                    </div>

                    @elseif ($receipt['type'] == 'Withdraw')
                    @foreach ($data as $d) 
                    <hr class="mt10">
                    <div class="flat-panel receipt-item-panel">  
                        <div class="row mt20">
                            <div class="col-sm-6">
                                <p><strong>Withdraw To</strong><br>{{ $d['recipient']['account_name'] }}</p>
                                <p>
                                    <strong>Bank Details</strong>   
                                    <br>
                                    Transfer country： {{ $d['recipient']['country']  }}
                                    <br>
                                    SWIFT/BIC Code：  {{ $d['recipient']['swift_code'] }}
                                    <br>
                                    Bank's Name：{{ sprintf('%s %s', $d['recipient']['bank_name'], $d['recipient']['branch_name']) }}
                                    <br>    
                                    {{ sprintf('%s：%s', (isset($d['recipient']['country']) ? $d['recipient']['country'] : $d['recipient']['transfer_country']) == 'Australia' ? 'BSB' : 'Sort Code', $d['recipient']['account_bsb']) }}
                                    <br>
                                    Account Number：{{ $d['recipient']['account_number'] }}
                                    <br> 

                                        @if (isset($d['recipient']['bank_street_number']))
                                        Bank Address: {{ sprintf('%s %s %s %s %s %s', 
                                                                $d['recipient']['bank_unit_number'],
                                                                $d['recipient']['bank_street_number'],
                                                                $d['recipient']['bank_street'],
                                                                $d['recipient']['bank_city'],
                                                                $d['recipient']['bank_state'],
                                                                $d['recipient']['bank_postcode']
                                                                )
                                                     }}   
                                        @else
                                        Bank Address: {{ sprintf('%s %s %s %s %s %s', 
                                                                $d['recipient']['unit_number'],
                                                                $d['recipient']['street_number'],
                                                                $d['recipient']['street'],
                                                                $d['recipient']['city'],
                                                                $d['recipient']['state'],
                                                                $d['recipient']['postcode']
                                                                )
                                                     }} 
                                        @endif                                   
                                </p>  
                                <p>
                                    <strong>Reason</strong>
                                    <br>
                                    {{ $d['reason'] }}
                                @if ($d['message'])
                                    <br>
                                    {{ $d['message'] }} (message)
                                @endif
                                </p>                                                  
                            </div>
                            <div class="col-sm-3">
                                <p>
                                    <strong>Recipient Address：</strong>
                                    <br>
                                    {{ $d['recipient']['address'] }}
                                @if ($d['recipient']['address_2'])
                                    <br>
                                    {{ $d['recipient']['address_2'] }}
                                @endif
                                @if (isset($d['recipient']['address_3']) AND $d['recipient']['address_3'])
                                    <br>
                                    {{ $d['recipient']['address_3'] }}
                                @endif
                                    <br>
                                    {{ $d['recipient']['transfer_country'] }}
                                    <br>
                                    {{ $d['recipient']['postcode'] }}
                                </p>
                                <p>
                                    <strong>Phone Number</strong>
                                    <br>
                                    {{ sprintf('+%s %s', $d['recipient']['phone_code'], $d['recipient']['phone'])}}
                                </p>
                            </div>
                            <div class="col-sm-3">
                                <p>
                                    <span class="grey"><strong>Amount</strong></span><br>
                                    <span class="f20p">{{ currencyFormat($currency, $d['amount'], TRUE) }}</span>
                                </p>
                                <p>
                                    <span class="grey"><strong>Transaction Fee</strong></span><br>
                                    <span class="f20p">{{ currencyFormat($currency, $d['fee']['total'], TRUE) }}</span>
                                </p>                        
                            </div>  
                        </div>                      
                    </div>
                    @endforeach

                    @else
                    <div class="flat-panel">
                        <h5>Transfer To：</h5>   
                        
                        @if ($recipient)                        
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
                                @if (isset($recipient['bank_street_number']))    
                                    Address：{{ sprintf('%s %s %s %s %s %s', 
                                                        $recipient['bank_unit_number'],
                                                        $recipient['bank_street_number'],
                                                        $recipient['bank_street'],
                                                        $recipient['bank_city'],
                                                        $recipient['bank_state'],
                                                        $recipient['bank_postcode']
                                                        )
                                                }} 
                                    <br> 
                                @endif       
                                </p>  
                                <p>
                                    <strong>Reason</strong>
                                    <br>
                                    {{ $reason }}
                                @if ($message)
                                    <br>
                                    {{ $message }} (message)
                                @endif
                                </p>                                                  
                            </div>
                            <div class="col-sm-6">
                                <p>
                                    <strong>Recipent Address：</strong>
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
                        @else
                        <div>
                            {{ $fx['lock_currency_want'] }} Balance
                        </div>
                        @endif 
                        <hr>                                                                   
                    </div>
                    <div class="flat-panel">
                        <h5>Deal Details</h5>
                        <div class="deal-detail">
                            <div class="deal-detail-title">Transferring {{ currencyFormat($fx['lock_currency_have'], $fx['lock_amount'], TRUE) }}</div>
                            <table class="table deal-detail-table">
                                <tbody>
                                    <tr>
                                        <td>Local Amount</td>
                                        <td>Exchange Rate</td>
                                        <td>Foreign Amount</td>
                                    </tr>
                                    <tr>
                                        <td>{{ currencyFormat($fx['lock_currency_have'], $fx['lock_amount'], TRUE) }}</td>
                                        <td>{{ sprintf('1 %s = %s %s', $fx['lock_currency_have'], $fx['lock_rate'], $fx['lock_currency_want']) }}</td>
                                        <td>{{ currencyFormat($fx['lock_currency_want'], $fx['lock_amount'] * $fx['lock_rate'], TRUE) }}</td>
                                    </tr>
                                @if (isset($fee['items']))
                                    <tr>
                                        <td colspan="3">
                                        @foreach ($fee['items'] as $fee)
                                            <strong>{{ $fee['name'] }}：</strong>{{ currencyFormat($fx['lock_currency_have'], $fee['fee'], TRUE) }}<br>
                                        @endforeach
                                        </td>
                                    </tr>                       
                                @endif
                                </tbody>
                            </table>
                            <div class="deal-detail-footer">
                                @if ($payment_method == 'balance')
                                <div class="text-right" id="balance_amount">
                                    <span class="amount-title">From Balance：</span>
                                    <span class="amount">{{ currencyFormat($fx['lock_currency_have'], $payment['deduct'], TRUE) }}</span><br>
                                    <span class="amount-title">Need To Pay：</span>
                                    <span class="amount">{{ currencyFormat($fx['lock_currency_have'], $payment['pay'], TRUE) }}</span>
                                </div>
                            @else
                                <div class="text-right" id="deposit_amount">
                                    <strong>Need To Pay：</strong> 
                                    <span class="amount">{{ currencyFormat($fx['lock_currency_have'], $payment['total'], TRUE) }}</span>                                                           
                                </div>
                            @endif
                            </div>
                        </div>
                        <hr>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row user-detail mt20">
                <h3 class="col-sm-12">{{ $user->full_name }} 
                    {{ $user->business ? sprintf('<span class="f16p">%s</span>', $user->business->business_name) : '' }} 
                    {{ $user->profile->status == 'unverified' ? '<span class="f16p color-error">(unverified)</span>' : '<span class="f16p color-468">(verified)</span>'}}                            
                    @if (is_vip($user->profile->vip_type))                             
                    <span class="f13p label {{ vip_label_class($user->profile->vip_type) }}">
                        {{ $user->profile->vip_type }}
                    </span>
                    @endif 
                </h3>
                <hr class="mt0 ml15 mr15">
                <div class="col-xs-12 col-md-8 row">
                    <div class="col-xs-6 col-md-6">E: {{ $user->email }}</div>
                    <div class="col-xs-6 col-md-6">A: {{ sprintf('%s <br> %s %s %s %s', $user->profile->unit_number, $user->profile->street_number, $user->profile->street, $user->profile->city, $user->profile->postcode) }}</div>
                    <div class="col-xs-6 col-md-6">M: {{ $user->verify->security_verified ? $user->verify->security_mobile : '' }}</div>
                    <div class="col-xs-6 col-md-6">B: {{ sprintf('%s/%s/%s', $user->profile->birth_day, $user->profile->birth_month, $user->profile->birth_year) }}</div>
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
            <div class="mt20" id="note-panel" data-uid="{{ $user->uid }}" data-type="receipt" data-item="{{ $receipt['id'] }}">
                <h4>Notes 
                    @if (check_perm('add_note', FALSE))
                    <span><a class="underline add-note" href="javascript:;">+Add</a></span>
                    @endif
                </h4>
                @foreach ($notes as $note)
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
        </div>
    </div>
@stop

@section('inline-js')
{{ HTML::script('assets/js/lib/admin/note.js') }}
@stop
