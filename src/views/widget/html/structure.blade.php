<div class="form-group">

	{{ Form::label("structure[html_code]", $controller->LL('title.html_code'), array('class'=>'control-label')) }}

	<div class="controls">

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
				<a data-toggle="tab" href="#{{$uniqueId}}-language-{{$language->locale}}-html_code">
					{{{$language->translate('title')}}}
				</a>
			</li>
			@endforeach

		</ul>
		<div class="tab-content">
			@foreach($languages as $language)
			<div id="{{$uniqueId}}-language-{{$language->locale}}-html_code" class="tab-pane in <?php if ($language->locale == $localeDefault) echo "active"; ?>">
				{{ Form::textarea("structure[html_code][{$language->locale}]", array_get($model->structure->all(), 'html_code.'.$language->locale), ['class' => 'col-md-8']) }}
			</div>
			@endforeach
		</div> 
	</div>
</div>
