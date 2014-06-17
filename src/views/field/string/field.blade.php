<div class="form-group">
    {{ Form::label("string_regex", \Lang::get("core::field/string.property.string_regex"), array('class'=>'control-label')) }}
    <div class="controls">
        {{ Form::text("string_regex", $model->translate("string_regex") ) }}
    </div>
</div>

@if ($model->multilanguage)
	@foreach((array)Config::get('app.locales') as $locale)
		<div class="form-group">
			{{ Form::label("string_default[{$locale}]", \Lang::get("core::field/string.property.string_default") . " [{$locale}]", array('class'=>'control-label')) }}
			<div class="controls">
				{{ Form::text("string_default[{$locale}]", $model->translate("string_default", $locale) ) }}
			</div>
		</div>
	@endforeach
@else
	<div class="form-group">
		{{ Form::label("string_default", \Lang::get("core::field/string.property.string_default"), array('class'=>'control-label')) }}
		<div class="controls">
			{{ Form::text("string_default", $model->string_default) }}
		</div>
	</div>
@endif

<div class="form-group">
	{{ Form::label("string_password", $controller->LL('property.string_password'), array('class'=>'control-label')) }}
	<div class="controls">
		{{ Form::hidden("string_password", 0) }}
		{{ Form::checkbox("string_password", 1, $model->required, array('class'=>'ace ace-switch ace-switch-3')) }}
		<span class="lbl"></span>
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
                {{ Form::label('string_min', $controller->LL('property.string_min'), array('class'=>'control-label')) }}
                <div class="controls">
                    {{ Form::text('string_min') }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('string_max', $controller->LL('property.string_max'), array('class'=>'control-label')) }}
                <div class="controls">
                    {{ Form::text('string_max') }}
                </div>
            </div>
        </div>
    </div>
</div>