@foreach ($rates as $rate)
<tr data-id="{{ $rate->id }}">
    <td>
    	@if (check_perm('delete_basic_rate',FALSE))
    	<a href="javascript:;" title="Remove" class="basic-remove-btn fa fa-minus-circle"></a>
    	@endif
    </td>
    <td>{{ time_to_tz($rate->created_at) }}</td>    
    <td>{{ $rate->operator_name }}</td>
    <td>{{ currencyFlag(substr($rate->currency, 0, 3)) . substr($rate->currency, 0, 3) }}</td>    
    <td>{{ currencyFlag(substr($rate->currency, 3)) . substr($rate->currency, 3) }}</td>    
    <td class="rate">1 : {{ $rate->rate }}</td>
</tr>
@endforeach