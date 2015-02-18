@section('title')
Activity
@stop

@section('content')
    @include('admin.customer.subnav')
    <div class="container">     
        <div class="dashboard-body">
            <h4>{{ $user->full_name }} <span class="back-btn f14p"><a href="{{ url('admin/customers/overview', $user->uid) }}" class="underline">Back</a></span></h4>
            <h4 class="mt30">Activity</h4>
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
                    <tfoot>
                        <tr>
                            <td colspan="5">{{ $activities->links() }}</td>
                        <tr>
                    </tfoot> 
                </table>
            </div>
        </div>
    </div>    
@stop
