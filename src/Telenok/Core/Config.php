<?php

namespace Telenok\Core;

class Config {

	public function getAclResourceFilter($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$collection = \Illuminate\Support\Collection::make([]);

				\Event::fire('telenok.acl.filter.resource.add', $collection);

				$list = new \Illuminate\Support\Collection([]);

				foreach ($collection as $class)
				{
					$object = \App::build($class);

					$list->put($object->getKey(), $object);
				}
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get acl resource filter. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getWorkflowEvent($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make([]);

				\Event::fire('telenok.workflow.event.add', $list);
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get workflow event. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getWorkflowElement($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$collection = \Illuminate\Support\Collection::make([]);

				\Event::fire('telenok.workflow.action.add', $collection);

				$list = new \Illuminate\Support\Collection([]);

				foreach ($collection as $class)
				{
					$object = \App::build($class);

					$list->put($object->getKey(), $object);
				}
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get workflow element. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getSetting($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$collection = \Illuminate\Support\Collection::make([]);

				\Event::fire('telenok.setting.add', $collection);

				$list = new \Illuminate\Support\Collection([]);

				foreach ($collection as $class)
				{
					$object = \App::build($class);

					$list->put($object->getKey(), $object);
				}
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get setting. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getObjectFieldController($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$collection = \Illuminate\Support\Collection::make([]);

				\Event::fire('telenok.objects-field.add', $collection);

				$list = new \Illuminate\Support\Collection([]);

				foreach ($collection as $class)
				{
					$object = \App::build($class);

					$list->put($object->getKey(), $object);
				}
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get field. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getModuleGroup($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make([]);

				\Telenok\Core\Model\Web\ModuleGroup::active()->get()->each(function($item) use ($list)
				{
					$object = \App::build($item->controller_class);
					$object->setModuleGroupModel($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get module-group. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getModule($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make([]);

				\Telenok\Core\Model\Web\Module::active()->get()->each(function($item) use ($list)
				{
					$object = \App::build($item->controller_class);
					$object->setModuleModel($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get module. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getController($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make([]);

				\Telenok\Core\Model\Web\PageController::active()->get()->each(function($item) use ($list)
				{
					$object = \App::build($item->controller_class);
					$object->setControllerModel($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get controller. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getWidgetGroup($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make([]);

				\Telenok\Core\Model\Web\WidgetGroup::active()->get()->each(function($item) use ($list)
				{
					$object = \App::build($item->controller_class);
					$object->setWidgetGroupModel($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get widget. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function getWidget($flush = false)
	{
		static $list = null;

		if ($list === null || $flush)
		{
			try
			{
				$list = \Illuminate\Support\Collection::make([]);

				\Telenok\Core\Model\Web\Widget::active()->get()->each(function($item) use ($list)
				{
					$object = \App::build($item->controller_class);
					$object->setWidgetModel($item);
					$list->put($object->getKey(), $object);
				});
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException('Failed to get widget. Error: ' . $e->getMessage());
			}
		}

		return $list;
	}

	public function compileRouter()
	{
		$path = storage_path() . '/route';

		$file = 'route.php';

		if (!\File::exists($path))
		{
			\File::makeDirectory($path, 0777, true);
		}

		$content = [];

		$controller = \App::make('telenok.config')->getController();

		foreach (\Telenok\Core\Model\Web\Page::with('pagePageController')->active()->get() as $key => $page)
		{
			if (!method_exists($page->pagePageController->controller_class, $page->pagePageController->controller_method))
			{
				throw new \Exception('Method "' . $page->pagePageController->controller_method . '" not exists in class "' . $page->pagePageController->controller_class . '"');
			}

			$content[] = '<?php '
					. ' Route::get("' . implode("/", array_map("rawurlencode", explode("/", $page->getAttribute('url_pattern')))) . '", array("as" => "page_' . $page->getKey() . '",'
					. ' "uses" => "' . addcslashes($page->pagePageController->controller_class, '"') . '@' . $page->pagePageController->controller_method . '"));'
					. ' ?>';
		}

		\File::put($path . '/' . $file, implode(chr(13), $content));
	}

	public function compileSetting()
	{
		if (\DB::table('setting')->where('active', 1)->count())
		{
			foreach (\Telenok\Core\Model\System\Setting::remember(10)->get() as $setting)
			{
				\Config::set($setting->code, $setting->value instanceof \Illuminate\Support\Collection ? $setting->value->toArray() : $setting->value);
			}
		}
	}

	public function runWorkflowListener()
	{
		foreach ($this->getWorkflowEvent() as $eventCode)
		{
			\Event::listen($eventCode, function($event) use ($eventCode)
			{
				if ($event instanceof \Telenok\Core\Interfaces\Workflow\Event)
				{
					$event->setEventCode($eventCode);

					return (new \Telenok\Core\Workflow\Runtime())->setEvent($event)->fire();
				}
			});
		}
	}

}

?>