
<?php
$domAttr = ['class' => 'ace ace-switch ace-switch-3'];
$disabled = false;

$value = $model->{$field->code};

if ((!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update))
{
	$domAttr['disabled'] = 'disabled';
	$disabled = true;
}
?> 

<div class="form-group">
    {{ Form::label($disabled ? uniqid() : "{$field->code}", $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		
		@if ((is_string($value) && mb_strlen($value) < 250) || is_numeric($value))
			{{ Form::text("{$field->code}", $value, ['class' => $field->css_class?: 'col-xs-5 col-sm-5'] ) }}
		@elseif (is_string($value) && mb_strlen($value) >= 250)
			{{ Form::textarea("{$field->code}", $value, ['class' => 'form-control'] ) }}
		@elseif ($value instanceof \Illuminate\Support\Collection)

		<?php

			$option = ["<option value='0' disabled='disabled'>...</option>"];

			$value->each(function($item) use (&$option)
			{
				$option[] = "<option value='{$item}' selected='selected'>{$item}</option>";
			});

		?>
			<select class="chosen" multiple data-placeholder="{{{$controller->LL('notice.choose')}}}" id="input{{{$uniqueId}}}" name="{{$field->code}}[]">
				{{ implode('', $option) }} 
            </select>
            <script>
                jQuery("#input{{{$uniqueId}}}").chosen({ 
                    keepTypingMsg: "{{$controller->LL('notice.typing')}}",
                    lookingForMsg: "{{$controller->LL('notice.looking-for')}}",
                    minTermLength: 1,
                    width: "200px",
                    no_results_text: "{{ $controller->LL('notice.not-found') }}",
					create_option: function(term) {
						this.append_option({
							value: term,
							text: term
						});
						$(this.form_field).change();
					}
                });
            </script>
			
		@endif
		
	</div>
</div>