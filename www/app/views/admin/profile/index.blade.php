@section('title')
Profile - Manager
@stop

@section('content')
    @include('admin.manager_info')
    <div class="container">
        <div class="all-radius white-box-body form-horizontal manager-detail">

            <h3>{{ $manager->first_name }} Profile</h3>
            <hr class="mt0">
            <div class="form-group">
                <label class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{ sprintf('%s %s %s',$manager->first_name, $manager->middle_name, $manager->last_name) }}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2">Email</label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{ $manager->email }}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2">Time Zone</label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{ timezones($manager->timezone) }}</p>
                </div>
            </div>

            <h3>Roles</h3>
            <hr class="mt0">
            <div class="manager-role">
                @foreach ($manager->roles as $r)
                <div class="mb20">
                    <h4 class="mb0">{{ $r->name }}</h4>
                    <p>{{ $r->description }}</p>
                </div>
                @endforeach
            </div>
            <hr class="mt0">
            <div class="row ml0">
                <a class="btn btn-primary col-sm-2" href="/admin/profile/edit">Edit</a>
            </div>
        </div>
    </div>
@stop

@section('inline-js')
 {{ HTML::script('assets/js/lib/admin/user.js') }}
@stop
