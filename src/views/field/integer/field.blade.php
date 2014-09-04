
<div class="form-group">
    {{ Form::label("integer_default", $controller->LL('property.default'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
    <div class="col-sm-9">
        {{ Form::text("integer_default", $model->integer_default) }}
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
	{{ Form::label('integer_min', $controller->LL('property.integer_min'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		{{ Form::text('integer_min', $model->integer_min) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('integer_max', $controller->LL('property.integer_max'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		{{ Form::text('integer_max', $model->integer_max) }}
	</div>
</div>