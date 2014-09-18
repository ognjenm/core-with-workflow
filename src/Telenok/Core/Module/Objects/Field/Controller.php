<?php

namespace Telenok\Core\Module\Objects\Field;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller { 

    protected $key = 'objects-field';
    protected $parent = 'objects';

    protected $typeList = 'object_field';
    protected $typeTree = 'object_type';

    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.objects-field.form-field-list';
	
    public function getListItem($model)
    {
        $query = $model::select($model->getTable() . '.*')->withPermission()->where(function($query) use ($model)
        {
            if (!\Input::get('filter_want_search', false) && ($treePid = \Input::get('treePid', 0)))
            { 
                $query->where($model->getTable().'.field_object_type', $treePid);
            }
        });

        $this->getFilterQuery($model, $query); 

        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip(\Input::get('iDisplayStart', 0))->take($this->displayLength + 1);
    }

    public function validate($model = null, $input = null, $message = [])
    {
        \App::make('telenok.config')->getObjectFieldController()->get($input->get('key'))->validate();

        return $this;
    } 
    
    public function preProcess($model, $type, $input)
    { 
        if (!$type)
        {
            $type = $this->getTypeList();
        } 

		if ($model->exists)
		{
			$id = $model->getOriginal('field_object_type');
			$key = $model->getOriginal('key');

			if ($id > 0 && $input->get('field_object_type') > 0 && $id != $input->get('field_object_type'))
			{
				throw new \Exception($this->LL('error.change.field.linked.type'));
			}

			if ($key && $input->get('key') && $key != $input->get('key'))
			{
				throw new \Exception($this->LL('error.change.field.key'));
			}
		}
		else
		{
			$modelType = \Telenok\Object\Type::where('code', $input->get('field_object_type'))->orWhere('id', $input->get('field_object_type'))->firstOrFail();
			
			$input->put('field_object_type', $modelType->getKey());
		} 

		// preprocessing at field controller
		if (!\App::make('telenok.config')->getObjectFieldController()->has($input->get('key')))
		{
			throw new \Exception('There are not field with key "' . $input->get('key') . '"');
		}
		else
		{
			\App::make('telenok.config')->getObjectFieldController()->get($input->get('key'))->preProcess($model, $type, $input);
		}
		
        return parent::preProcess($model, $type, $input);
    }

    public function postProcess($model, $type, $input)
    {   
        $field = \App::make('telenok.config')->getObjectFieldController()->get($input->get('key'));

        $field->postProcess($model, $type, $input);  

        return parent::postProcess($model, $type, $input);
    }
}