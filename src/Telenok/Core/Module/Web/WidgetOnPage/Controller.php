<?php

namespace Telenok\Core\Module\Web\WidgetOnPage;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller {

	protected $key = 'web-page-wop';
    protected $presentation = 'tree-tab-object';
	protected $typeList = 'widget_on_page';
    protected $presentationFormFieldListView = 'core::module.web-page-wop.form-field-list';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->typeList}";
    }  
	
}

?>