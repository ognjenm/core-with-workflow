<div class="form-group">
    {!! Form::label("checkbox_default", $controller->LL('property.default'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
    <div class="col-sm-9">
        {!! Form::hidden("checkbox_default", 0) !!}
        {!! Form::checkbox("checkbox_default", 1, $model->checkbox_default, ['class'=>'ace ace-switch ace-switch-3']) !!}
        <span class="lbl"></span>
    </div>
</div>