<?php

namespace Telenok\Core\Module\Web\Page;

class Controller extends \Telenok\Core\Module\Objects\Lists\Controller {

	protected $key = 'web-page';
	protected $parent = 'web';
	protected $presentation = 'web-page-widget';
	protected $presentationView = 'core::module.web-page.presentation';
	protected $presentationContentView = 'core::module.web-page.content';

	public function getActionParam()
	{
		return json_encode([
			'presentation' => $this->getPresentation(),
			'presentationContent' => $this->getPresentationContent(),
			'key' => $this->getKey(),
			'breadcrumbs' => $this->getBreadcrumbs(),
			'pageHeader' => $this->getPageHeader(),
			'uniqueId' => uniqid(),
		]);
	}

	public function getPresentationContent()
	{
		return \View::make($this->getPresentationView(), [
					'presentation' => $this->getPresentation(),
					'controller' => $this,
					'iDisplayLength' => $this->displayLength,
					'uniqueId' => uniqid()
				])->render();
	}

	public function viewPageContainer($id = 0, $languageId = 0)
	{
		try
		{
			$page = \Telenok\Web\Page::findOrFail($id);
			$controllerClass = \App::build($page->pagePageController->controller_class);

			return [
				'pageId' => $id,
				'tabKey' => $this->getTabKey() . '-widget-page-' . $id,
				'tabLabel' => "#{$page->getKey()} " . $page->translate('title'),
				'tabContent' => $controllerClass->getContainerContent($id, $languageId)
			];
		}
		catch (\Exception $ex)
		{
			return [];
		}
	}

	public function getListPage()
	{
		$return = \Illuminate\Support\Collection::make([]);

		$query = \Telenok\Web\Page::query();

		if (\Input::get('term'))
		{
			$query->where('title', 'like', '%' . trim(\Input::get('term')) . '%');
		}

		$query->get()->each(function($item) use ($return)
		{
			$return->push(['id' => $item->id, 'title' => $item->translate('title') . " [{$item->url_pattern}]"]);
		});

		return $return;
	}

	public function insertWidget($languageId = 0, $pageId = 0, $key = '', $id = 0, $container = '', $bufferId = 0, $order = 0)
	{
		if (!intval($pageId) || !trim($key) || !trim($container))
		{
			return \Response::json('Empty page id or widget key', 403);
		}
		
		$widget = \App::make('telenok.config')->getWidget()->get($key); 
		
		if (intval($bufferId))
		{
			$w = $widget->insertFromBufferOnPage($languageId, $pageId, $key, $id, $container, $order, $bufferId);
		}
		else
		{
			$w = $widget->insertOnPage($languageId, $pageId, $key, $id, $container, $order);
		}

		return $widget->getInsertContent($w->getKey());
	}

	public function removeWidget($id = 0)
	{
		try
		{
			$widget = \Telenok\Web\WidgetOnPage::findOrFail($id);

			\App::make('telenok.config')->getWidget()->get($widget->key)->removeFromPage($id);

			return ['success' => 1];
		}
		catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
		{
			return \Response::json($this->LL('notice.error.undefined'), 403);
		}
		catch (\Exception $e)
		{
			return \Response::json($e->getMessage(), 403);
		}
	}

	public function addBufferWidget($id = 0, $key = 'copy')
	{
		$widget = \Telenok\Web\WidgetOnPage::findOrFail($id);

		$buffer = \Telenok\System\Buffer::addBuffer(\Auth::user()->getKey(), $widget->getKey(), 'web-page', $key);

		return ['widget' => $widget->toArray(), 'buffer' => $buffer->toArray()];
	}

	public function deleteBufferWidget($id = 0)
	{
		$w = \Telenok\System\Buffer::find($id);

		if ($w)
		{
			$w->forceDelete();
		}
	}

}

?>