@section('title')
Logs
@stop

@section('content')
    @include('admin.center.subnav')
    <div class="container">
        <div class="flat-panel white-box-body all-radius">
            <div>
                <h3 class="mt0">{{ $user ? sprintf('%s Logs ', $user->uid == Auth::admin()->user()->uid ? 'My' : $user->first_name . '\'s') : 'Activity Logs' }} {{ check_perm('view_log', FALSE) ? '<span class="ml15 f14p"><a class="underline" href="/admin/logs">Back</a></span>' : ''}}</h3>
                <div class="log-filter">

                    @if ( ! $user)
                    <div class="btn-group">
                        <button type="button" id="user_button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        All Users <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>{{ link_to('admin/logs', 'All Users') }}</li>
                            @foreach ($managers as $m)
                            <li><a href="/admin/logs/?uid={{ $m->uid }}">{{ $m->full_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" id="role_button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        {{ Input::get('role') ? Role::find(Input::get('role'))->name: 'All Roles' }} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>{{ link_to(current_url(TRUE, array('role', 'page')), 'All Roles') }}</li>
                            @foreach ($roles as $role)
                            <li>{{ link_to(current_url(TRUE, array('role', 'page')) . '&role=' . $role['id'], $role['name']) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="btn-group">
                        <button type="button" id="activity_button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        {{ Input::get('activity_type') ? $activities[Input::get('activity_type')] : 'All Activity' }} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>{{ link_to(current_url(TRUE, array('activity_type', 'page')), 'All Activity') }}</li>
                            @foreach ($activities as $act_key => $act)
                            <li>{{ link_to(current_url(TRUE, array('activity_type', 'page')) . '&activity_type=' . $act_key, $act) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" id="date_word_button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        {{ Input::get('dw') ? commonDate(Input::get('dw')) : 'All Date' }} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            @foreach (commonDate() as $k => $dw)
                            <li>{{ link_to(current_url(TRUE, array('dw', 'page')) . '&dw=' . $k, $dw) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <form class="navbar-form date-pick" action="{{ current_url(TRUE,array('start', 'expriy')) }}" role="search">
                        <div class="form-group">
                            <input type="text" id="start" name="start" value="{{ Input::get('start') }}" class="form-control datepicker">
                            <span class="fa fa-calendar"></span>
                        </div>
                         to 
                        <div class="form-group">
                            <input type="text" id="expriy" name="expriy" value="{{ Input::get('expriy') }}" class="form-control datepicker">
                            <span class="fa fa-calendar"></span>
                        </div>
                        <button type="button" id="date_search" class="btn btn-default">Show</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover contents-list table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            @if ( ! $user OR $user->uid != Auth::admin()->user()->uid)
                            <th>User</th>
                            @endif
                            <th>Action</th>
                            <th>Activity Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{ $logs->isEmpty() ? '<tr><td colspan="5" align="center">- No Records to Display -</td></tr>' : '' }}
                        @foreach ($logs as $log)
                            <tr>
                                <td>
                                    {{ date_word($log->created_at) }}
                                </td>
                                @if (! $user OR $user->uid != Auth::admin()->user()->uid)
                                <td class="color-666">
                                    {{ link_to('admin/logs/?uid=' . $log->user->uid, pretty_str($log->user->full_name)) }}
                                </td>
                                @endif
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->activity_type }}</td>
                                <td class="log-desc">{{ $log->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- show more button ? -->
                @if ( ! $logs->isEmpty() AND $total_count > 50)
                <button class="btn btn-default btn-block btn-gry" id="btn_show_more_tx">
                    <input type="hidden" id="request_url" value="{{ url('admin/logs/json') }}">
                    <input type="hidden" id="request_param" value="{{ http_build_query($args) }}">
                    <span class='pull-left ml10' id='btn_show_more_tx_content'>1 - {{ count($logs) }} record(s), total {{ $total_count }} record(s)</span>
                    <span class='pull-right mr10'>Show more logs<i class="fa fa-arrow-circle-down lightblue ml10"></i></span>
                </button>
                @endif
            </div>
        </div>
    </div>
@stop

@section('inline-css')
{{ HTML::style('assets/jquery-ui/jquery-ui.min.css') }}
@stop

@section('inline-js')
{{ HTML::script('assets/jquery-ui/jquery-ui.min.js') }}
{{ HTML::script('assets/js/lib/admin/search.js') }}
{{ HTML::script('assets/datatable/jquery.dataTables.min.js') }}
{{ HTML::script('assets/js/lib/admin/contents-list.js') }}
<script>
    $(function() {
        $( ".datepicker" ).datepicker();
        $('.flat-panel table').delegate('.log-desc', 'click', function(){
            $(this).removeClass('log-desc');
        });
    });
</script>
@stop