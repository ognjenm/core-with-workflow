<?php

namespace Telenok\Core\Interfaces\Field;

abstract class Controller extends \Illuminate\Routing\Controller {

    protected $ruleList = [];
    protected $specialField = [];
    protected $allowMultilanguage = true; 

    protected $key = '';
    protected $package = '';
    protected $displayLength = 5;
	
    protected $viewModel = "";
    protected $viewField = "";
	
    protected $routeListTable = "";
    protected $routeListTitle = "";
    protected $routeWizardCreate = "";
    protected $routeWizardEdit = "";
    protected $routeWizardChoose = "";

    public function getName()
    {
        return $this->LL('name');
    }

    public function getKey()
    {
        return $this->key;
    } 

    public function getViewModel()
    {
        return $this->viewModel ?: "core::field.{$this->getKey()}.model";
    }

    public function getViewField()
    {
        return $this->viewField ?: "core::field.{$this->getKey()}.field";
    } 	

	public function getRouteListTable()
	{
		return $this->routeListTable ?: "cmf.field.{$this->getKey()}.list.table";
	}

	public function getRouteListTitle()
	{
		return $this->routeListTitle ?: "cmf.field.{$this->getKey()}.list.title";
	}

	public function getRouteWizardCreate()
	{
		return $this->routeWizardCreate ?: 'cmf.module.objects-lists.wizard.create';
	}

	public function getRouteWizardEdit()
	{
		return $this->routeWizardEdit ?: 'cmf.module.objects-lists.wizard.edit';
	}

	public function getRouteWizardChoose()
	{
		return $this->routeWizardChoose ?: 'cmf.module.objects-lists.wizard.choose';
	}	
	
    public function getSpecialField()
    {
        return $this->specialField;
    }

    public function getModelField($model, $field)
    {
		return [$field->code];
    } 

    public function getDateField($model, $field)
    {
		return [];
    } 
    
    public function getRule($field = null)
    {
        return $this->ruleList;
    }

    public function getModelAttribute($model, $key, $value, $field)
    {
        try
        {
			return $model->getAttribute($key);
        }
        catch (\Exception $e)
        {
            return null;
        }
    }
	
    public function setModelAttribute($model, $key, $value, $field)
    {
		$model->setAttribute($key, $value);
    }
	
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
            return $model->getAttribute($key);
        }
        catch (\Exception $e)
        {
            return null;
        }
    }
    
    public function setModelSpecialAttribute($model, $key, $value)
    {
        $model->setAttribute($key, $value);
        
        return true;
    }
	
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    { 
        return \View::make($this->getViewModel(), array(
                'parentController' => $controller,
                'controller' => $this,
                'model' => $model,
                'field' => $field,
                'displayLength' => $this->displayLength,
                'uniqueId' => $uniqueId,
            ))->render();
    }

	public function getLinkedModelType($field) {}

    public function getTableList($id = null, $fieldId = null, $uniqueId = null) 
    {
        $term = trim(\Input::get('sSearch'));
        $iDisplayStart = intval(\Input::get('iDisplayStart', 0));
        $iDisplayLength = intval(\Input::get('iDisplayLength', 10));
        $sEcho = \Input::get('sEcho');
        $content = [];

        try 
        {
            $model = \Telenok\Core\Model\Object\Sequence::getModel($id);
            $type = \Telenok\Core\Model\Object\Sequence::find($id)->sequencesObjectType;
            $field = \Telenok\Core\Model\Object\Sequence::getModel($fieldId);

			$query = $model->{camel_case($field->code)}();

            if ($term)
            {
                $query->where('title', 'like', "%{$term}%");
            }

            $query->skip($iDisplayStart)->take($this->displayLength + 1);

            $items = $query->get();

			$objectField = $this->getLinkedModelType($field)->field()->where('show_in_list', 1)->get();

			$config = \App::make('telenok.config')->getObjectFieldController();

            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
				$c = [];
				
				foreach($objectField as $f)
				{
					$c[$f->code] = $config->get($f->key)->getListFieldContent($f, $item, $type);
				}

				$c['tableManageItem'] = $this->getListButtonExtended($item, $field, $type, $uniqueId);
						
                $content[] = $c;
            }

            return [
                'sEcho' => $sEcho,
                'iTotalRecords' => ($iDisplayStart + $items->count()),
                'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
                'aaData' => $content
            ]; 
        }
        catch (\Exception $e) 
        {
            return [
                'sEcho' => $sEcho,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => [],
                'exception' => $e->getMessage(),
            ];
        } 
    } 
	
    public function getFormModelTableColumn($field, $model, $jsUnique)
    {
		$fields = []; 
		
		$objectField = $this->getLinkedModelType($field)->field()->where('show_in_list', 1)->get();
		
		foreach($objectField as $key => $field)
		{
			$fields[$field->code] = [
				"mData" => $field->code, 
				"sTitle" => e($field->translate('title_list')), 
				"mDataProp" => null, 
				"bSortable" => $field->allow_sort ? true : false,
			];
			
			if ( ($key==1 && $objectField->count() > 1) || $objectField->count() == 1)
			{
				$fields['tableManageItem'] = [
					"mData" => 'tableManageItem', 
					"sTitle" => e($this->LL('action')), 
					"mDataProp" => null, 
					"bSortable" => false,
				];
			}
		}
		
		return $fields;
    }

    public function getFormFieldContent($model = null, $uniqueId = null)
    {
        return \View::make($this->getViewField(), array(
                'controller' => $this,
                'model' => $model,
                'uniqueId' => $uniqueId,
            ))->render();
    }

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if ($value !== null && trim($value))
		{
			$query->where($model->getTable().'.'.$name, 'like', '%'.trim($value).'%');
		}
    }

    public function getFilterContent($field = null)
    {
        return '<input type="text" name="filter['.$field->code.']" value="" />';
    }

    public function getListFieldContent($field, $item, $type = null)
    { 
        return \Str::limit($item->translate((string)$field->code), 20);
    }

    public function validate($input = null, $messages = [])
    {
        $validator = $this->validator($this, $input, $this->LL('error'), [], $input);
        
        if ($validator->fails()) 
        {
            throw $this->validateException()->setMessageError($validator->messages());
        }
        
        return $this;
    } 
	
    public function validateMethodExists($object, $method)
    {
        $reflector = new \ReflectionClass($object);
        $file = $reflector->getFileName();

        try 
        {
            return preg_match("/function\s+{$method}\s*\(/", \File::get($file));
        }
        catch (\Exception $e) {}
        
        return false;
    }    
    
    public function fill($field, $model, $input)
    {
        return $this;
    }

    public function saveModelField($field, $model, $input)
    { 
        return $model;
    }    

    public function updateModelFile($model, $param, $stubFile, $dir)
    {
        $reflector = new \ReflectionClass($model);
        $file = $reflector->getFileName();

        try 
        {
            $stub = \File::get($dir . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . "$stubFile.stub");

            foreach($param as $k => $v)
            {
                $stub = str_replace('{{'.$k.'}}', $v, $stub);
            }

            $res = preg_replace('/\}\s*(\?\>)?$/', $stub, \File::get($file)).PHP_EOL.PHP_EOL.'}'.PHP_EOL.'?>';

            \File::put($file, $res); 
        } 
        catch (\Exception $e) 
        {
            throw new \Exception($this->LL('error.file.update', array('file' => $file)));
        }
    }

    public function processScheme($table, $typeOrClosure, $name = '', $default = null)
    {
        try
        {
            if (\Schema::hasTable($table)) 
            {
                if (!\Schema::hasColumn($table, $name) && !\Schema::hasColumn($table, "`{$name}`"))
                {
                    if ($typeOrClosure instanceof \Closure)
                    {
                        \Schema::table($table, $typeOrClosure($table));
                    }
                    else 
					{
                        \Schema::table($table, function($table) use ($typeOrClosure, $name, $default)
                        {
                            $field = $table->$typeOrClosure($name);

                            if ($default !== null)
                            {
                                $field->default($default);
                            }
                            else
                            {
                                $field->nullable();
                            }
                        });
                    }
                }

                if (!\Schema::hasColumn($table, $name) && !\Schema::hasColumn($table, "`{$name}`"))
                {
                    throw $this->validateException()->setMessageError($this->LL('error.field.create', array('table' => $table, 'field' => $name)));
                }
            }
            else
            {
                throw $this->validateException()->setMessageError($this->LL('error.table.nonexists', array('table' => $table)));
            }
        }
        catch (\Exception $e)
        {
            throw $this->validateException()->setMessageError($this->LL('error.field.create', array('table' => $table, 'field' => $name)));
        }
    }

    public function upSchema($model)
    { 
        return $this;
    }  

    public function validator($model = null, $input = null, $messages = [])
    {
        return new \Telenok\Core\Interfaces\Validator\Model($model, $input, $messages);
    }

    public function validateException()
    {
        return new \Telenok\Core\Interfaces\Exception\Validate();
    }
    
    public function preProcess($model, $type, $input)
    {  
        return $this;
    }

    public function postProcess($model, $type, $input)
    {  
        return $this;
    } 
    
    public function allowMultilanguage()
    {
        return $this->allowMultilanguage;
    }
    
    public function getMultilanguage($model, $field)
    {
		if ($field->multilanguage)
		{
			return [$field->code];
		}
    }

    public function getPackage()
    {
        if ($this->package) return $this->package;
        
        $list = explode('\\', __NAMESPACE__);
        
        return strtolower(array_get($list, 1));
    }

    public function LL($key = '', $param = [])
    {
        $key_ = "{$this->getPackage()}::field/{$this->getKey()}.$key";
        $key_default_ = "{$this->getPackage()}::default.$key";
        
        $word = \Lang::get($key_, $param);
        
        // not found in current wordspace
        if ($key_ === $word)
        {
            $word = \Lang::get($key_default_, $param);
            
            // not found in default wordspace
            if ($key_default_ === $word)
            {
                return $key_;
            }
        } 

        return $word;
    }
}

?>