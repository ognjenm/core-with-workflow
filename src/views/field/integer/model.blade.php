<div class="form-group input-group">
    <?php 
        $domAttr = ['placeholder'=>$field->integer_default];
        $disabled = false;

        if (!$model->exists) 
        {
            $value = $field->integer_default;
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
    {{ Form::label("{$field->code}", $field->translate('title'), array('class'=>'control-label')) }}
    <div class="controls">
        @if ($field->icon_class)
        <span class="input-group-addon">
            <i class="{{{ $field->icon_class }}}"></i>
        </span>
        @endif
        @if ($disabled)
        {{ Form::hidden("{$field->code}", $value) }}
        @endif
        {{ Form::text($disabled ? uniqid() : "{$field->code}", $value, $domAttr) }}
        @if ($field->translate('description'))
        <span title="" data-content="{{{ $field->translate('description') }}}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{{\Lang::get('core::default.tooltip.description')}}}">?</span>
        @endif 
    </div>
</div>