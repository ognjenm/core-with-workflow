<?php 
    $domAttr = ['class' => $field->css_class?: ''];
    $disabled = false;
    
    if ( (!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update) )
    {
        $domAttr['disabled'] = 'disabled';
        $disabled = true; 
    }

?>

<div class="form-group">
	{{ Form::label("{$field->code}", $field->translate('title'), array('class'=>'control-label')) }}
	<div class="controls">
		@if ($field->icon_class)
		<span class="input-group-addon">
			<i class="{{$field->icon_class}}"></i>
		</span>
		@endif
		
		<?php
			$domAttr['placeholder'] = $field->string_default;
		?>

		{{ Form::file($field->code, $domAttr ) }}
		<br><br>
		@if (!empty($model->{$field->code . '_path'}) && $controller->isImage($field, $model))
		<img src="{{URL::asset($model->{$field->code . '_path'})}}" alt="" width="140" />
		@endif

		@if ($field->translate('description'))
		<span title="" data-content="{{{ $field->translate('description') }}}" data-placement="right" data-trigger="hover" data-rel="popover" 
			  class="help-button" data-original-title="{{{\Lang::get('core::default.tooltip.description')}}}">?</span>
		@endif

	</div>
</div>


