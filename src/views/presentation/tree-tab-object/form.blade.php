	<div class="row">
		<div class="col-xs-12"> 
			<div class="tabbable">
				<ul class="nav nav-tabs" id='form-nav-{{{$uniqueId}}}'>

					@foreach($type->tab()->get() as $tab) 

					<li>
						<a data-toggle="tab" href="#{{{$uniqueId}}}_{{{$tab->code}}}">
							@if ($tab->icon_class)
							<i class="{{{$tab->icon_class}}}"></i>
							@endif
							{{{$tab->translate('title')}}}
						</a>
					</li>

					@endforeach
				</ul>

				<script>
					@section('scriptForm')
				
					jQuery("ul#form-nav-{{{$uniqueId}}} li:first a").click();
				
					@show
				</script>

				<div class="tab-content">

					@foreach($type->tab()->get()->sortBy('tab_order') as $tab) 

					<div id="{{{$uniqueId}}}_{{{$tab->code}}}" class="tab-pane in">
						
						@foreach($tab->field()->get()->filter(function($item) use ($fields) { return $item->show_in_form == 1 && $fields->filter(function($i) use ($item) { return $i->getKey() == $item->getKey(); })->count(); })->sortBy('field_order') as $field) 

							@include($controller->getPresentationFormFieldListView())
							
						@endforeach 

					</div>

					@endforeach

				</div>
			</div>
		</div>	
	</div>
