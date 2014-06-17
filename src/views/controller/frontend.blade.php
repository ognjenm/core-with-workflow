<!doctype html>
<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<title>{{{$page->translate('title_ceo')}}}</title>
		<title>{{{$page->translate('description_ceo')}}}</title>
	</head>
	<body> 
		<table class="table table-bordered table-striped " style="padding: 0; margin: 0;">

			<tbody>
				<tr>
					<td style="vertical-align: top; margin: 0 2px 0 0;">
						<div class="frontend-container span" style="padding: 0; margin: 0; min-width:150px; min-height: 150px;" data-container-id="center">
							@if (isset($content['center']))
								@foreach($content['center'] as $widget)
									{{$widget}}
								@endforeach
							@endif
						</div>
					</td>
				</tr>
			</tbody>

		</table>

	</body>
</html>