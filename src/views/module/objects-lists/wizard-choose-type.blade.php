<?php

    $jsUnique = str_random();

?>

<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>Please, choose type of new object</h4>
		</div>

		<div class="modal-body" style="height: 400px; overflow-y: auto; padding: 15px; position: relative;">
			<div class="widget-main">

				<select class="chosen-type{{$jsUnique}}" data-placeholder="{{$controller->LL('notice.choose')}}" id="input{{$jsUnique}}" name="id">

					<?php

						$model = new \App\Model\Telenok\Object\Type();

						$query = $model::withPermission();
						
						if (!empty($typeId))
						{
							$query->whereIn($model->getTable() . '.id', $typeId);
						}
						
						$query->active()->groupBy($model->getTable() . '.id')->get()->each(function($item) use (&$option)
						{
							$option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
						});

					?>

					{!! implode('', $option) !!}

				</select> 
				
			</div>
		</div>
		
		<div class="modal-footer">

			<div class="center no-margin">
				<button class="btn btn-success" onclick="createWizard{{$jsUnique}}(this, '{!! $controller->getRouterCreate(['id' => '_id_', 'saveBtn' => \Input::get('saveBtn'), 'chooseBtn' => \Input::get('chooseBtn'), 'chooseSequence' => 1]) !!}');">
					{{ $controller->LL('btn.continue') }}
				</button>
			</div>
		</div>

	</div>
</div>


<script type="text/javascript">
	jQuery("#input{{$jsUnique}}").chosen({ 
		keepTypingMsg: "{{$controller->LL('notice.typing')}}",
		lookingForMsg: "{{$controller->LL('notice.looking-for')}}",
		type: "GET",
		dataType: "json",
		inherit_select_classes: 1,
		minTermLength: 1,
		width: "200px",
		no_results_text: "{{$controller->LL('notice.not-found')}}" 
	});
	
	
	function createWizard{{$jsUnique}}(obj, url) 
	{
		var $block = jQuery(obj).closest('div.modal-dialog');
		var $modal = jQuery(obj).closest('div.modal'); 

		jQuery.ajax({
			url: url.replace('_id_', jQuery('#input{{$jsUnique}}').val()),
			method: 'get',
			dataType: 'json'
		}).done(function(data) 
		{
			$modal.html(data.tabContent);
		});
	}
</script>

