			<div class="form-group" id="table-search-container">
				@if (isset($status_arr))
				<div class="col-sm-2 pr0">
					{{ Form::select('status', $status_arr, $status, array('class' => 'form-control')) }}
				</div>
				@endif
				@if (isset($status_arr))
				<div class="col-sm-2 pr0 pl5">
				@else
				<div class="col-sm-2 pr0">
				@endif
					<div class="addon-input-group">
						<input type="text" id="start_date" name="start_date" placeholder="开始时间"
							value="{{ $start_date }}" class="form-control datepicker">
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<div class="col-sm-2 pr0 pl5">
					<div class="addon-input-group">
						<input type="text" id="end_date" name="end_date" placeholder="结束时间"
							value="{{ $end_date }}" class="form-control datepicker">
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<div class="col-sm-3 pr0 pl5">
					<div class="input-group">
						<input type="text" id="search" name="search" placeholder="{{ isset($search_placeholder) ? $search_placeholder : '用户名' }}"
								value="{{ $search }}" class="form-control">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
						</span>
					</div><!-- /input-group -->
				</div>
			</div>