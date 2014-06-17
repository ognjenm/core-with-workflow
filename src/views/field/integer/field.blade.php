
<div class="form-group">
    {{ Form::label("integer_default", $controller->LL('property.default'), array('class'=>'control-label')) }}
    <div class="controls">
        {{ Form::text("integer_default") }}
    </div>
</div>

<div class="widget-box">
    <div class="widget-header">
        <h4>
            <i class="fa fa-list-ol"></i>
            {{{ $controller->LL('field.rule') }}}
        </h4>
    </div> 

    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group">
                {{ Form::label("required", $controller->LL('property.required'), array('class'=>'control-label')) }}
                <div class="controls">
					{{ Form::hidden("required", 0) }}
                    {{ Form::checkbox("required", 1, $model->required, array('class'=>'ace ace-switch ace-switch-3')) }}
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('integer_min', $controller->LL('property.integer_min'), array('class'=>'control-label')) }}
                <div class="controls">
                    {{ Form::text('integer_min') }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('integer_max', $controller->LL('property.integer_max'), array('class'=>'control-label')) }}
                <div class="controls">
                    {{ Form::text('integer_max') }}
                </div>
            </div>
        </div>
    </div>
</div>