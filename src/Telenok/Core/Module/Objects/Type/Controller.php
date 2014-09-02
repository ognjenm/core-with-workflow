<?php

namespace Telenok\Core\Module\Objects\Type;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller {

    protected $key = 'objects-type';
    protected $parent = 'objects';
    protected $typeList = 'object_type';

    protected $pathModel = '\models';
    protected $pathController = '\controllers';
    protected $nsDefault = '\\';

    protected $presentation = 'tree-tab-object';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->getModelList()->getTable()}";
    }  
	
	public function createResource($model)
	{
		$resCode = 'object_type.'.$model->code;

		$title = $model->title->toArray();
		$toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];

		foreach($title as $language => $value)
		{
			$title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value;
		}

		try
		{
			\Telenok\Security\Resource::create([
				'title' => $title,
				'code' => $resCode,
				'active' => 1
			]);
		} 
		catch (\Exception $ex) {}

		$resCodeOwn = 'object_type.'.$model->code.'.own';
		
		$title = $model->title->toArray();
		$toAdd = ['ru' => 'Тип объекта', 'en' => 'Type of object'];
		$toAddAfter = ['ru' => 'Собственные записи', 'en' => 'Own records'];

		foreach($title as $language => $value)
		{
			$title[$language] = array_get($toAdd, $language, 'Type of object') . ': ' . $value . '. ' . array_get($toAddAfter, $language, 'Own records');
		}

		try
		{
			(new \Telenok\Security\Resource())->storeOrUpdate([
				'title' => $title,
				'code' => $resCodeOwn,
				'active' => 1
			]);
		}
		catch (\Exception $ex) {}
	}

    public function validateClassModel($model, $type, $input)
    { 
		$input->put('class_model', strtolower(trim($input->get('class_model'), '\\ ')));

		$classNameCollection = \Illuminate\Support\Collection::make(explode('\\', $input->get('class_model')))->each(function($item)
		{
			if (!preg_match('/^[a-z][\w]*$/i', $item))
			{
				throw new \Exception($this->LL('error.class_model.name'));
			}
		})
		->transform(function($item)
		{
			return ucfirst($item);
		});

		$input->put('class_model', '\\' . implode($classNameCollection->toArray(), '\\'));

		if (class_exists($input->get('class_model')))
		{
			\Session::flash('warning.class_model_exists', $this->LL('error.class_model_exists'));
		}

		if ($model->exists)
		{
			$input->forget('class_model');
		}
	}

    public function validateClassController($model, $type, $input)
    {
		if (!$input->get('class_controller'))
		{
			return;
		}

		$input->put('class_controller', trim($input->get('class_controller'), '\\ '));

		$classNameCollection = \Illuminate\Support\Collection::make(explode('\\', $input->get('class_controller')))->each(function($item)
		{
			if (!preg_match('/^[a-z][\w]*$/i', $item))
			{
				throw new \Exception($this->LL('error.class_controller.name'));
			}
		})
		->transform(function($item)
		{
			return ucfirst($item);
		});

		$input->put('class_controller', '\\' . implode($classNameCollection->toArray(), '\\'));

		if (class_exists($input->get('class_controller')))
		{
			\Session::flash('warning.class_controller_exists', $this->LL($this->LL('error.class_controller_exists')));
		}
	}

    public function preProcess($model, $type, $input)
    { 
		$input->put('code', trim($input->get('code')));

        return parent::preProcess($model, $type, $input); 
	}

    public function postProcess($model, $type, $input)
    { 
		$this->validateClassModel($model, $type, $input);
		$this->validateClassController($model, $type, $input);
		
        parent::postProcess($model, $type, $input); 

		$this->createResource($model);
		$this->createModelFile($model); 
		$this->createModelTable($model);
		$this->createControllerFile($model);
		$this->createObjectField($model);

		if ($input->get('class_controller'))
		{
			$this->createControllerFile($model); 
		}
		
		return $this;
	}

    public function createModelFile($model)
    {
        $class = class_basename($model->class_model);

		$ns = trim(preg_replace('/\\\\'.$class.'$/', '', $model->class_model), '\\');

		$file = str_replace('\\', DIRECTORY_SEPARATOR, app_path() . $this->pathModel . $model->class_model . '.php');
		$dir = str_replace('\\', DIRECTORY_SEPARATOR, app_path() . $this->pathModel . DIRECTORY_SEPARATOR . $ns);

        if (!\File::exists($file)) 
        {
            try 
            {
				\File::makeDirectory($dir, 0775, true, true);

                $param = [
                    'namespace' => ($ns ? "namespace $ns;" : ""),
                    'class' => $class,
                    'table' => $model->code,
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
				\Exception($this->LL('error.file.create', array('path' => $file)));
            }
        } 
    }

    public function createControllerFile($model)
    {
        $class = class_basename($model->class_controller);

		$ns = trim(preg_replace('/\\\\'.$class.'$/', '', $model->class_controller), '\\');

		$file = str_replace('\\', DIRECTORY_SEPARATOR, app_path() . $this->pathController . $model->class_controller . '.php');
		$dir = str_replace('\\', DIRECTORY_SEPARATOR, app_path() . $this->pathController . DIRECTORY_SEPARATOR . $ns);

        if (!\File::exists($file)) 
        {
            try 
            {
				\File::makeDirectory($dir, 0775, true, true);

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
				\Exception($this->LL('error.file.create', array('path' => $file)));
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

    public function createObjectField($model)
    {
		$multilanguage = $model->multilanguage;
		
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
			]); 
		}
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