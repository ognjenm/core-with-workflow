<div class="form-group">
    {{ Form::label('text_width', $controller->LL('property.width'), array('class'=>'control-label')) }}
    <div class="controls">
        {{ Form::text('text_width') }}
    </div>
</div>
<div class="form-group">
    {{ Form::label('text_height', $controller->LL('property.height'), array('class'=>'control-label')) }}
    <div class="controls">
        {{ Form::text('text_height') }}
    </div>
</div>
<div class="form-group">
    {{ Form::label("text_default", $controller->LL('property.default'), array('class'=>'control-label')) }}
    <div class="controls">
        {{ Form::textarea("text_default", null, ['class'=>'col-md-4', 'style' => 'height:60px;']) }}
    </div>
</div>

