<div class="form-group">
    {{ Form::hidden("{$field->code}", 0) }}
	<?php
	$domAttr = ['class' => 'ace ace-switch ace-switch-3'];
	$disabled = false;

	if (!$model->exists)
	{
		$value = $field->active_default;
	}
	else
	{
		$value = $model->{$field->code};
	}

	if ((!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update))
	{
		$domAttr['disabled'] = 'disabled';
		$disabled = true;
	}
	?> 
    {{ Form::label($disabled ? uniqid() : "{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
        @if ($disabled)
        {{ Form::hidden("{$field->code}", $value) }}
        @endif
        {{ Form::checkbox("{$field->code}", 1, $value, $domAttr) }}
        <span class="lbl"></span>
    </div> 
</div>

<div class="form-group">
	{{ Form::label("start_at", $controller->LL('start_at'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-3">
		<div class="input-group datetime-picker" data-date-format="DD-MM-YYYY HH:mm:ss">
			<span class="input-group-addon">
				<i class="fa fa-calendar bigger-110"></i>
			</span>
			<input type="text" name="start_at" value="{{{$model->start_at->setTimezone(\Config::get('app.timezone'))->format("d-m-Y H:i:s")}}}" class="form-control" /	>
		</div>
	</div>
</div>

<div class="form-group">
	{{ Form::label("end_at", $controller->LL('end_at'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-3">
		<div class="input-group datetime-picker" data-date-format="DD-MM-YYYY HH:mm:ss">
			<span class="input-group-addon">
				<i class="fa fa-calendar bigger-110"></i>
			</span>
			<input type="text" name="end_at" value="{{{$model->end_at->setTimezone(\Config::get('app.timezone'))->format("d-m-Y H:i:s")}}}" class="form-control" />
		</div>
	</div>
</div>

