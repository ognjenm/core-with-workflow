<?php

namespace Telenok\Core\Interfaces\Controller\Frontend;

abstract class Controller extends \Illuminate\Routing\Controller {

	protected $key = '';
	protected $package = '';
	protected $controllerModel;
	protected $container = [];
	protected $jsFilePath = array();
	protected $cssFilePath = array();
	protected $cssCode = array();
	protected $jsCode = array();
	
	protected $containerView = 'core::controller.frontend';
	protected $containerSkeleton = 'core::controller.frontend-container';

	public function getContainerContent($pageId = 0, $languageId = 0)
	{
		$content = ['controller' => $this];

		$wOP = \Telenok\Web\WidgetOnPage::where('widget_page', $pageId)->whereHas('widgetLanguageLanguage', function($query) use ($languageId)
			{
				$query->where('id', $languageId);
			})
			->orderBy('order')->get();

		$widgetConfig = \App::make('telenok.config')->getWidget();

		$wOP->each(function($w) use (&$content, $widgetConfig)
		{
			$content[$w->container][] = $widgetConfig->get($w->key)->getInsertContent($w->id);
		});

		return \View::make($this->containerSkeleton, $content)->render();
	}

	public function getContent()
	{ 
		$content = [];

		$listWidget = \App::make('telenok.config')->getWidget();
		$pageId = intval(str_replace('page_', '', \Route::currentRouteName()));
		
		try
		{
			$page = \Telenok\Web\Page::findOrFail($pageId);
			
			foreach($this->container as $containerId)
			{
				$page->widget()->active()->get()->each(function($item) use (&$content, $containerId, $listWidget)
				{
					$content[$containerId][] = $listWidget->get($item->key)->setWidgetModel($item)->getContent();
				});
			}
		}
		catch (\Exception $e)
		{
			\App::abort(404);
		}

		return \View::make($this->containerView, [
			'page' => $page,
			'controller' => $this,
			'content' => $content,
		]);
	}

	public function addCssFile($filePath)
	{
		$this->cssFilePath[] = $filePath;

		return $this;
	}

	public function addCssCode($code)
	{
		$this->cssCode[] = $code;

		return $this;
	}

	public function addJsFile($filePath)
	{
		$this->jsFilePath[] = $filePath;

		return $this;
	}

	public function addJsCode($code)
	{
		$this->jsCode[] = $code;

		return $this;
	}

	public function getHeader()
	{
		$header = '';

		foreach ($this->cssFilePath as $file)
		{
			$header .= \HTML::style($file);
		}

		foreach ($this->cssCode as $code)
		{
			$header .= "<style>{$code}</style>";
		}

		foreach ($this->jsFilePath as $file)
		{
			$header .= \HTML::script($file);
		}

		foreach ($this->jsCode as $code)
		{
			$header .= "<script>{$code}</script>";
		}

		return $header;
	}

	public function getAdminArea()
	{
		if (\Auth::can('read', 'control_panel'))
		{
			$this->addCssFile('packages/telenok/core/css/backend-for-frontend.css');

			return \View::make('core::controller.backend-frontend-iframe');
		}
	}

	public function getName()
	{
		return $this->LL('name');
	}

	public function setControllerModel($model)
	{
		$this->controllerModel = $model;

		return $this;
	}

	public function getControllerModel()
	{
		return $this->controllerModel;
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

	public function getKey()
	{
		return '';
	}

	public function LL($key = '', $param = [])
	{
		$key_ = "{$this->getPackage()}::controller/{$this->getKey()}.$key";
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