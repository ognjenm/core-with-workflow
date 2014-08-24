<div class="form-group">
    {{ Form::label("active_default", $controller->LL('property.default'), array('class' => 'col-sm-3 control-label no-padding-right')) }}
    <div class="col-sm-9">
        {{ Form::hidden("active_default", 0) }}
        {{ Form::checkbox("active_default", 1, $model->active_default, ['class' => 'ace ace-switch ace-switch-3']) }}
        <span class="lbl"></span>
    </div>
</div>