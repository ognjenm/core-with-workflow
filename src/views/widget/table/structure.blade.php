<div class="form-group">
	{{ Form::label("structure[col]", $controller->LL('title.col'), array('class'=>'control-label')) }}
	<div class="controls">
		{{ Form::text("structure[col]", array_get($model->structure->all(), 'col')) }}
	</div>
</div>


<div class="form-group">
	{{ Form::label("structure[row]", $controller->LL('title.row'), array('class'=>'control-label')) }}
	<div class="controls">
		{{ Form::text("structure[row]", array_get($model->structure->all(), 'row')) }}
	</div>
</div>


<hr>