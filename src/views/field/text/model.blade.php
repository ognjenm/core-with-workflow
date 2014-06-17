 

@if ($field->multilanguage)

<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4 class="row">
			<span class="col-sm-12">
				<i class="ace-icon fa fa-list-ul"></i>
				{{{ $field->translate('title_list') }}}
			</span>
		</h4>
	</div>
	<div class="widget-body"> 
		<div class="widget-main form-group field-list">
			<ul class="nav nav-tabs" >
				<?php
				$localeDefault = \Config::get('app.localeDefault');

				$languages = \Telenok\Core\Model\System\Language::whereIn('locale', (array) \Config::get('app.locales'))
								->get()->sortBy(function($item) use ($localeDefault)
				{
					return $item->locale == $localeDefault ? 0 : 1;
				});
				?>

				@foreach($languages as $language)
				<li class="<?php if ($language->locale == $localeDefault) echo "active"; ?>">
					<a data-toggle="tab" href="#{{$uniqueId}}-language-{{$language->locale}}-{{{$field->code}}}">
						{{{$language->translate('title')}}}
					</a>
				</li>
				@endforeach

			</ul>
			<div class="tab-content">
				@foreach($languages as $language)
				<div id="{{$uniqueId}}-language-{{$language->locale}}-{{{$field->code}}}" class="tab-pane in <?php if ($language->locale == $localeDefault) echo "active"; ?>">
					{{ Form::textarea("{$field->code}[{$language->locale}]", $model->translate($field->code, $language->locale), ['class' => 'form-control'] ) }}
				</div>
				@endforeach
			</div> 

		</div>

	</div>
</div>
@else
<div class="form-group">
	{{ Form::label("{$field->code}", $field->translate('title'), array('class'=>'control-label')) }}
	<div class="controls">
		{{ Form::textarea($field->code, $model->translate($field->code), ['class' => 'span'] ) }}
	</div>
</div>
@endif