<?php

namespace Telenok\Core\Module\Objects\Type;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller {

    protected $key = 'objects-type';
    protected $parent = 'objects';
    protected $typeList = 'object_type';

    protected $nsDefaultPathModel = '\app\models';
    protected $nsDefaultPathForm = '\app\controllers';
    protected $nsDefault = '\\';

    protected $presentation = 'tree-tab-object';
    protected $presentationFormModelView = 'core::module.objects-type.form';
    protected $presentationFormFieldListView = 'core::module.objects-type.form-field-list';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->getModelList()->getTable()}";
    }  
	
    public function postProcess($model, $type, $input)
    { 
        parent::postProcess($model, $type, $input); 
		
        $nsPathClassModel = trim($input->get('namespace_path_class_model'), '.');
        $nsPathClassForm = trim($input->get('namespace_path_class_form'), '.'); 
        $multilanguage = intval($input->get('multilanguage', 1));

		$resCode = 'object_type.'.$model->code;

		$resource = \Telenok\Security\Resource::where('code', $resCode)->first();

		if (!$resource)
		{
			$title = $model->title->toArray();
			$toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];

			foreach($title as $language => $value)
			{
				$title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value;
			}

			\Telenok\Security\Resource::create([
				'title' => $title,
				'code' => $resCode,
				'active' => 1
			]);
		}

		$resCodeOwn = 'object_type.'.$model->code.'.own';
		$resource = \Telenok\Security\Resource::where('code', $resCodeOwn)->first();

		if (!$resource)
		{
			$title = $model->title->toArray();
			$toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];
			$toAddAfter = ['ru' => 'Собственные записи', 'en' => 'Own records'];

			foreach($title as $language => $value)
			{
				$title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value . '. ' . array_get($toAddAfter, $language, 'Own records');
			}

			(new \Telenok\Security\Resource())->storeOrUpdate([
				'title' => $title,
				'code' => $resCodeOwn,
				'active' => 1
			]);
		}

        try
        {
            if (!class_exists($model->class_model))
            {
                $nsClassModel = $this->getNamespaceByPath($nsPathClassModel, $this->nsDefault, $this->nsDefaultPathModel);

                $model->class_model = ($nsClassModel=='\\' ? "" : '\\').str_finish($nsClassModel, '\\').studly_case($model->code);
                $model->save();

                $this->createModelFile($nsPathClassModel, $model); 
            }

            $this->createModelTable($model);
            $this->createObjectField($model, $multilanguage);
 
            if ($model->class_controller && !class_exists($model->class_controller))
            {
                $nsClassForm = $this->getNamespaceByPath($nsPathClassForm, $this->nsDefault, $this->nsDefaultPathForm);

                $model->class_controller = ($nsClassForm=='\\' ? "" : '\\').str_finish($nsClassForm, '\\') . 'Controller';
                $model->save();

                $this->createFormFile($nsPathClassForm, $model); 
            }
        }
        catch (\Exception $e)
        {
            throw $e;
        }

        return $this;
    }

    public function createFormFile($path, $model)
    {
        if (!trim($path))
        {
            throw $this->validateException()->setMessageError($this->LL('error.class_controller.define'));
        }

        $class = class_basename($model->class_controller);
        $dir = base_path() . DIRECTORY_SEPARATOR . trim($path, '\\') . DIRECTORY_SEPARATOR;
        $file = $dir . $class . '.php';

        if (!\File::exists($file)) 
        {
            try 
            {
                $ns = trim(preg_replace('/\\\\'.$class.'$/', '', $model->class_controller), '\\');

                $param = [
                    'namespace' => ($ns ? "namespace $ns;" : ''),
                    'class' => $class,
                    'key' => "objects-{$model->code}",
                    'parent' => 'objects',
                    'presentation' => 'tree-tab-object',
                    'typeList' => "{$model->code}",
                    'typeTree' => "",
                ];

                $stub = \File::get(__DIR__.'/stubs/controller.stub');

                foreach($param as $k => $v)
                {
                    $stub = str_replace('{{'.$k.'}}', $v, $stub);
                }

                \File::put($file, $stub);
            } 
            catch (\Exception $e) 
            {
                throw new \Exception($this->LL('error.file.create', array('dir' => $dir)));
            }
        }
    }

    public function createModelFile($path, $model)
    {
        if (!trim($path))
        {
            $path = $this->nsDefaultPathModel;
        }

        $class = class_basename($model->class_model);
		$dir = base_path() . DIRECTORY_SEPARATOR . trim(preg_replace('@[/\\\]@', DIRECTORY_SEPARATOR, $path), '/\\') . DIRECTORY_SEPARATOR;
		$file = $dir . $class . '.php';

        if (!\File::exists($file)) 
        {
            try 
            {
                $ns = trim(preg_replace('/\\\\'.$class.'$/', '', $model->class_model), '\\');

                $param = [
                    'namespace' => ($ns?"namespace $ns;":''),
                    'class' => $class,
                    'class_controller' => $model->class_controller,
                    'table' => $model->code,
                    'materializedPath' => ' ',
                ];

                $stub = \File::get(__DIR__.'/stubs/model.stub');

                foreach($param as $k => $v)
                {
                    $stub = str_replace('{{'.$k.'}}', $v, $stub);
                }

                \File::put($file, $stub);
            } 
            catch (\Exception $e) 
            {
                throw new \Exception($this->LL('error.file.create', array('dir' => $dir)));
            }
        }
    }

    public function createModelTable($model)
    {  
        $table = $model->code;

        try
        {
            if (!\Schema::hasTable($table)) 
            {
                \Schema::create($table, function(\Illuminate\Database\Schema\Blueprint $table) use ($model)
                {
                    $table->increments('id');
                    $table->timestamps();
                    $table->softDeletes();
                    $table->text('title')->nullable();
                });
            }
        }
        catch (\Exception $e)
        {
            throw $e;
        }
    }

    public function createObjectField($model, $multilanguage = 1)
    {
		$tabMain = \Telenok\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'main')->first();
		$tabVisible = \Telenok\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'visibility')->first();
		$tabAdditionally = \Telenok\Object\Tab::where('tab_object_type', $model->getKey())->where('code', 'additionally')->first();
		
		$translationSeed = $this->translationSeed();
		
		if (!$tabMain)
		{
			$tabMain = (new \Telenok\Object\Tab())->storeOrUpdate(
					[
						'title' => array_get($translationSeed, 'tab.main'),
						'code' => 'main',
						'active' => 1,
						'tab_object_type' => $model->getKey(),
						'tab_order' => 1
					]
			);
		}

		if (!$tabVisible)
		{
			$tabVisible = (new \Telenok\Object\Tab())->storeOrUpdate(
					[
						'title' => array_get($translationSeed, 'tab.visibility'),
						'code' => 'visibility',
						'active' => 1,
						'tab_object_type' => $model->getKey(),
						'tab_order' => 2
					]
			);
		}

		if (!$tabAdditionally)
		{
			$tabAdditionally = (new \Telenok\Object\Tab())->storeOrUpdate(
					[
						'title' => array_get($translationSeed, 'tab.additionally'),
						'code' => 'additionally',
						'active' => 1,
						'tab_object_type' => $model->getKey(),
						'tab_order' => 3
					]
			);
		}

		if (!\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('code', 'id')->count())
		{
			(new \Telenok\Object\Field())->storeOrUpdate([
				'title' => array_get($translationSeed, 'model.№'),
				'title_list' => array_get($translationSeed, 'model.№'),
				'key' => 'integer-unsigned',
				'code' => 'id',
				'active' => 1,
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabMain->getKey(),
				'show_in_list' => 1,
				'show_in_form' => 1,
				'allow_search' => 1,
				'multilanguage' => 0,
				'allow_create' => 0,
				'allow_update' => 0,
				'field_order' => 1,
			]);
		}

		if (!\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('code', 'title')->count())
		{
			(new \Telenok\Object\Field())->storeOrUpdate([
				'title' => array_get($translationSeed, 'model.title'),
				'title_list' => array_get($translationSeed, 'model.title'),
				'key' => 'string',
				'code' => 'title',
				'active' => 1,
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabMain->getKey(),
				'multilanguage' => $multilanguage,
				'show_in_list' => 1,
				'show_in_form' => 1,
				'allow_search' => 1,
				'allow_create' => 1,
				'allow_update' => 1,
				'field_order' => 2,
				'string_list_size' => 50,
			]);
		}

		if (!\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'created-by')->count())
		{
			(new \Telenok\Object\Field())->storeOrUpdate([
				'key' => 'created-by',
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabAdditionally->getKey(),
				'field_order' => 1,
			]);
		}
		
		if (!\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'updated-by')->count())
		{
			(new \Telenok\Object\Field())->storeOrUpdate([
				'key' => 'updated-by',
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabAdditionally->getKey(),
				'field_order' => 2,
			]);
		}
		
		if ($model->treeable)
		{
			if (!\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'tree')->count())
			{
				(new \Telenok\Object\Field())->storeOrUpdate([
					'key' => 'tree',
					'field_object_type' => $model->getKey(),
					'field_object_tab' => $tabMain->getKey(),
					'field_order' => 10,
				]);
			}
		}
		else
		{
			\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'tree')->delete();
		}

		if (!\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'active')->count())
		{
			(new \Telenok\Object\Field())->storeOrUpdate([
				'key' => 'active',
				'field_object_type' => $model->getKey(),
				'field_object_tab' => $tabVisible->getKey(),
				'field_order' => 3,
			]); 
		}

		if (!\Telenok\Object\Field::where('field_object_type', $model->getKey())->where('key', 'permission')->count())
		{
			(new \Telenok\Object\Field())->storeOrUpdate([
				'key' => 'permission',
				'field_object_type' => $model->getKey(),
				//'field_object_tab' => $tabAdditionally->getKey(),
			]); 
		}
    }    

    public function namespaceExist($ns)
    {
        static $declared = [];
        
        if (empty($declared))
        {
            $declared = get_declared_classes();
        }
        
        foreach ($declared as $name)
		{
            if (strpos($name, $ns.'\\') === 0)
			{
                return true;
			}
		}
		
        return false;
    }

    public function getNamespaceModelContent()
    {
        $path = trim(\Input::get('path'));
        $code = trim(\Input::get('code'));

        try
        {
            if (!$code)
            {
                throw new \Exception($this->LL('error.code_empty'));
            }
            
            $ns = $this->getNamespaceByPath($path, $this->nsDefault, $this->nsDefaultPathModel);

            $class = ($ns=='\\' ? "" : '\\').str_finish($ns, '\\').studly_case($code);

            if (class_exists($class))
            {
                throw new \Exception($this->LL('error.class_model_exists'));
            }

            return ['class' => $class];
        }
        catch (\Exception $e)
        {
            return ['error' => (array)$e->getMessage()]; 
        }
    }

    public function getNamespaceFormContent()
    {
        $path = trim(\Input::get('path'));
        $code = trim(\Input::get('code'));

        try
        {
            if (!$code)
            {
                throw new \Exception($this->LL('error.code_empty'));
            }

            $ns = $this->getNamespaceByPath($path, $this->nsDefault, $this->nsDefaultPathForm);
            $class = ($ns=='\\' ? "" : '\\').str_finish($ns, '\\') . 'Controller';

            if (class_exists($class))
            {
                throw new \Exception($this->LL('error.class_form_exists'));
            }
            
            return ['class' => $class];
        }
        catch (\Exception $e)
        {
            return ['error' => (array)$e->getMessage()]; 
        }
    }
    
    public function getNamespaceByPath($path, $nsDefault, $nsDefaultPath)
    {
        if (!trim($path))
        {
            $ns = $nsDefault;
            $path = $nsDefaultPath;
        }
        else
        {
            $ns = '';

            foreach (explode('\\', trim($path, '\\') ) as $name)
            {
                if ($this->namespaceExist($ns . $name))
                {
                    $ns .= $name . '\\';
                }
            }

            $ns = trim($ns, '\\');

            if ($ns)
            {
                $ns = mb_substr($path, mb_strpos($path, $ns));
            }
            else
            {
                $ns = '\\';
            }
        }

        $class = 'A'.str_random();

        $className = ($ns=='\\' ? "" : '\\').str_finish($ns, '\\').$class;
		
		$path = base_path() . DIRECTORY_SEPARATOR . trim(preg_replace('@[/\\\]@', DIRECTORY_SEPARATOR, $path), '/\\') . DIRECTORY_SEPARATOR;

        $file =  $path . $class . '.php'; 

        try
        {
            \File::put($file, '<?php ' . ($ns=='\\' ? '' : "namespace $ns;") . " class $class {} ?>");
            
            if (!class_exists($className))
            {
                throw new \Exception($this->LL('error.namespace.search', array('path' => $path)));
            }

            \File::delete($file);
        }
        catch (\Exception $e) 
        {
            \File::delete($file);

            throw $e;
        }

        return $ns;
    }

	public function translationSeed()
	{
		return [
			'field' => [
				'id' => [
					'ru' => "№",
					'en' => "№",
				],
				'title' => [
					'ru' => "Заголовок",
					'en' => "Title",
				],
				'title_list' => [
					'ru' => "Заголовок списка",
					'en' => "Title of list",
				],
			],
			'tab' => [
				'main' => ['en' => 'Main', 'ru' => 'Основное'],
				'visibility' => ['en' => 'Visibility', 'ru' => 'Видимость'],
				'additionally' => ['en' => 'Additionally', 'ru' => 'Дополнительно'],
			],
			'model' => [
				'№' => ['en' => '№', 'ru' => '№'],
				'title' => ['en' => 'Title', 'ru' => 'Заголовок'],
				'№' => ['en' => '№', 'ru' => '№'],
				'№' => ['en' => '№', 'ru' => '№'],
				'№' => ['en' => '№', 'ru' => '№'],
				'№' => ['en' => '№', 'ru' => '№'],
				'№' => ['en' => '№', 'ru' => '№'],
				'№' => ['en' => '№', 'ru' => '№'],
			],
		];
	}

}

?>