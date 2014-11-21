<?php 
    
    $domAttr = ['class' => $field->css_class?: ''];
    $disabled = false;

    if (!$model->exists) 
    {
        $value = $field->translate('string_default');
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

    $localeDefault = \Config::get('app.localeDefault');
    $locale = \Config::get('app.locale');

    $title = $field->select_many_data->get('title', []);
    $keys = $field->select_many_data->get('key', []);
    $default = $field->select_many_data->get('default', []);
    $titleLocale = array_get($title, $locale, []);

    if (empty($titleLocale))
    {
        $titleLocale = array_get($title, $localeDefault, []);
    }

    $values = array_combine($keys, $titleLocale);
?>

<div class="form-group">
	{{ Form::label("{$field->code}", $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
        <div>
            <div class="btn-group btn-overlap" data-toggle="buttons">
                @foreach($values as $k => $v)
                <?php
                    $checked = ($model->exists && $model->{$field->code}->search($k) !== false) || (!$model->exists && in_array($k, $default)) ? 1 : 0;
                ?>
                <label class="btn btn-white btn-sm btn-primary @if ($checked) active @endif" @if ($disabled) disabled="disabled" @endif>
                    <input type="checkbox" @if ($checked) checked="checked" @endif name="{{{$field->code}}}[]" value="{{{$k}}}" @if ($disabled) disabled="disabled" @endif /> {{{$v}}}
                </label>
                @endforeach
            </div>
            @if ($field->translate('description'))
            <span title="" data-content="{{{ $field->translate('description') }}}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{{\Lang::get('core::default.tooltip.description')}}}">?</span>
            @endif
        </div>
    </div>
</div>
