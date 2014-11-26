
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

        if ( (!$model->exists && (!$field->allow_create || !$permissionCreate)) || ($model->exists && (!$field->allow_update || !$permissionUpdate)) )
        {
            $domAttr['disabled'] = 'disabled';
            $disabled = true;
        }
    ?> 

<div class="form-group">
    {!! Form::label($disabled ? str_random() : "{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	<div class="col-sm-9">
        @if (!$disabled)
        {!! Form::hidden("{$field->code}", 0) !!}
        @endif
        {!! Form::checkbox("{$field->code}", 1, $value, $domAttr) !!}
        <span class="lbl"></span>
	</div>
</div>