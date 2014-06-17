
    <?php 
        $domAttr = ['class'=>'ace ace-switch ace-switch-3'];
        $disabled = false;
        
        if (!$model->exists) 
        {
            $value = $field->checkbox_default;
        }
        else
        {
            $value = $model->{$field->code};
        }

        if ( (!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update) )
        {
            $domAttr['disabled'] = 'disabled';
            $disabled = true;
        }
    ?> 

<div class="form-group">
    {{ Form::label($disabled ? uniqid() : "{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
        @if (!$disabled)
        {{ Form::hidden("{$field->code}", 0) }}
        @endif
        {{ Form::checkbox("{$field->code}", 1, $value, $domAttr) }}
        <span class="lbl"></span>
	</div>
</div>