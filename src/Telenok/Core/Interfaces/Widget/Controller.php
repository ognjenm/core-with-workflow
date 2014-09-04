<?php

namespace Telenok\Core\Interfaces\Widget;

use \Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

abstract class Controller {

	protected $key = '';
	protected $parent = '';
	protected $group = '';
	protected $icon = 'fa fa-desktop';
	protected $package;
	protected $widgetModel;
	protected $backendView = '';
	protected $structureView = '';

	public function getName()
	{
		return $this->LL('name');
	}

	public function getIcon()
	{
		return $this->icon;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function getPackage()
	{
		if ($this->package)
		{
			return $this->package;
		}

		$list = explode('\\', __NAMESPACE__);

		return strtolower(array_get($list, 1));
	}

	public function setWidgetModel($model)
	{
		$this->widgetModel = $model;

		return $this;
	}

	public function getWidgetModel()
	{
		return $this->widgetModel;
	}

	public function getContent(\Illuminate\Support\Collection $structure = null)
	{
		return '';
	}

	public function children()
	{
		return \App::make('telenok.config')->getWidget()->filter(function($item)
				{
					return $this->getKey() == $item->getParent();
				});
	}

	public function parent()
	{
		$list = \App::make('telenok.config')->getWidget()->toArray();
		$key = $this->getKey();

		return array_filter($list, function($item) use ($key)
		{
			return $key == $item->getParent();
		});
	}

	public function getBackendView()
	{
		return $this->backendView ? : "core::module.web-page-constructor.widget-backend";
	}

	public function getStructureView()
	{
		return $this->structureView ? : "core::widget.{$this->getKey()}.structure";
	}

	public function getInsertContent($id = '')
	{
		$widgetOnPage = \Telenok\Web\WidgetOnPage::findOrFail($id);

		return \View::make($this->getBackendView(), [
					'header' => $this->LL('header'),
					'title' => $widgetOnPage->title,
					'id' => $widgetOnPage->getKey(),
					'key' => $this->getKey(),
					'widgetOnPage' => $widgetOnPage,
				])->render();
	}
	
	public function insertFromBufferOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0, $bufferId = 0)
	{
		$widgetOnPage = null;
		
		\DB::transaction(function() use ($languageId, $pageId, $key, $id, $container, $order, &$widgetOnPage, $bufferId)
		{
			$widgetOnPage = \Telenok\Web\WidgetOnPage::findOrFail($id);
			$buffer = \Telenok\System\Buffer::findOrFail($bufferId);

			if ($buffer->key == 'cut')
			{
				$widgetOnPage->storeOrUpdate([
					"container" => $container,
					"order" => $order,
					"key" => $key,
				]);
				
				$bufferWidget = \Telenok\System\Buffer::find($bufferId);
				
				if ($bufferWidget)
				{
					$bufferWidget->forceDelete();
				}
			}
			else if ($buffer->key == 'copy')
			{
				$widgetOnPage = \Telenok\Web\WidgetOnPage::findOrFail($id)->replicate();
				$widgetOnPage->push();
				$widgetOnPage->storeOrUpdate([
						"container" => $container,
						"order" => $order,
					]);
			}
			else if ($buffer->key == 'copy-link')
			{
				$originalWidget = $this->findOriginalWidget($id);

				if ($originalWidget->isWidgetLink())
				{
					throw new \Exception($this->LL('error.widget.link.nonexistent'));
				}

				$widgetOnPage = $originalWidget->replicate();
				$widgetOnPage->push();
				$widgetOnPage->storeOrUpdate([
						"container" => $container,
						"order" => $order,
					]);

				$originalWidget->widgetLink()->save($widgetOnPage);
			}

			\Telenok\Web\WidgetOnPage::where("order", ">=", $order)
					->where("container", $container)->get()->each(function($item)
			{
				$item->storeOrUpdate(["order" => $item->order + 1]);
			});

			$widgetOnPage->widgetLanguageLanguage()->associate(\Telenok\System\Language::findOrFail($languageId));
			$widgetOnPage->widgetPage()->associate(\Telenok\Web\Page::findOrFail($pageId));
			$widgetOnPage->save(); 
		});

		return $widgetOnPage;
	}

	public function insertOnPage($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $order = 0)
	{
		$widgetOnPage = null;
		
		try
		{
			\DB::transaction(function() use ($languageId, $pageId, $key, $id, $container, $order, &$widgetOnPage)
			{
				$widgetOnPage = \Telenok\Web\WidgetOnPage::findOrFail($id)
						->storeOrUpdate([
					"title" => $this->LL('header'),
					"container" => $container,
					"order" => $order,
					"key" => $key,
				]);

				\Telenok\Web\WidgetOnPage::where("order", ">=", $order)
						->where("container", $container)->get()->each(function($item)
				{
					$item->storeOrUpdate(["order" => $item->order + 1]);
				});

				$widgetOnPage->widgetLanguageLanguage()->associate(\Telenok\System\Language::findOrFail($languageId));
				$widgetOnPage->widgetPage()->associate(\Telenok\Web\Page::findOrFail($pageId));
				$widgetOnPage->save();
			});
		}
		catch (\Exception $e)
		{
			\DB::transaction(function() use ($languageId, $pageId, $key, $container, $order, &$widgetOnPage)
			{
				$widgetOnPage = (new \Telenok\Web\WidgetOnPage())
						->storeOrUpdate([
					"title" => $this->LL('header'),
					"container" => $container,
					"order" => $order,
					"key" => $key,
				]); 

				\Telenok\Web\WidgetOnPage::where("order", ">=", $order)
						->where("container", $container)->get()->each(function($item)
				{
					$item->storeOrUpdate(["order" => $item->order + 1]);
				});

				$widgetOnPage->widgetLanguageLanguage()->associate(\Telenok\System\Language::findOrFail($languageId));
				$widgetOnPage->widgetPage()->associate(\Telenok\Web\Page::findOrFail($pageId));
				$widgetOnPage->save();
			});
		}

		return $widgetOnPage;
	}

	public function removeFromPage($id = 0)
	{
		\Telenok\Web\WidgetOnPage::destroy($id);
	}

	public function getStructureContent($model = null, $uniqueId = null)
	{
		return \View::make($this->getStructureView(), [
					'controller' => $this,
					'model' => $model,
					'uniqueId' => $uniqueId,
				])->render();
	}

	public function findOriginalWidget($id = 0)
	{
		$widget = \Telenok\Web\WidgetOnPage::findOrFail($id);
		
		$widgetLink = $widget->widgetLinkWidgetOnPage()->first();
		
		if ($widgetLink)
		{
			return $this->findOriginalWidget($widgetLink->getKey());
		}
		else
		{
			return $widget;
		}
	} 
	
	public function LL($key = '', $param = [])
	{
		$key_ = "{$this->getPackage()}::widget/{$this->getKey()}.$key";
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