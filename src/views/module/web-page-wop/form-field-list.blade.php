
							@if (!in_array($field->code, ['structure']))

								{{\App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId)}}

							@elseif ($field->code=='structure')

								<?php

								$w = \App::make('telenok.config')->getWidget()->get($model->key);

								?>

								@if ($w)

								{{ $w->getStructureContent($model, $uniqueId) }}

								@endif

							@endif