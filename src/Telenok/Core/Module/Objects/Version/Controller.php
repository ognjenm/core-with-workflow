<?php

namespace Telenok\Core\Module\Objects\Version;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller { 

    protected $key = 'objects-version';
    protected $parent = 'objects';

    protected $typeList = 'object_version';

    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.objects-version.form-field-list';
    protected $presentationModelView = 'core::module.objects-version.model';

	public function save($input = [], $type = null)
	{
		if ($input === null)
		{
			$input = \Input::all();
		}

		$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array) $input);
  
		$model = \Telenok\Core\Model\Object\Version::findOrFail($input->get('id'));
		
		try
		{
			return \Telenok\Core\Model\Object\Version::toRestore($model);
		} 
		catch (\Telenok\Core\Interfaces\Exception\ObjectTypeNotFound $ex) 
		{
			throw new \Exception($this->LL('error.restore.type.first', ['id' => $model->object_type_id]));
		}
	}

}