
<div class="form-group">
    {{ Form::label("integer_unsigned_default", $controller->LL('property.default'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
    <div class="col-sm-9">
        {{ Form::text("integer_unsigned_default") }}
    </div>
</div>

<div class="form-group">
	{{ Form::label("required", $controller->LL('property.required'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		{{ Form::hidden("required", 0) }}
		{{ Form::checkbox("required", 1, $model->required, array('class'=>'ace ace-switch ace-switch-3')) }}
		<span class="lbl"></span>
	</div>
</div>
<div class="form-group">
	{{ Form::label('integer_unsigned_min', $controller->LL('property.integer_unsigned_min'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		{{ Form::text('integer_unsigned_min') }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('integer_unsigned_max', $controller->LL('property.integer_unsigned_max'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		{{ Form::text('integer_unsigned_max') }}
	</div>
</div>