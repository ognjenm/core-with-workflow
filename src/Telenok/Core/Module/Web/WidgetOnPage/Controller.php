<?php

namespace Telenok\Core\Module\Web\WidgetOnPage;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller {

	protected $key = 'web-page-wop';
    protected $presentation = 'tree-tab-object';
	protected $typeList = 'widget_on_page';
    protected $presentationFormFieldListView = 'core::module.web-page-wop.form-field-list';
    protected $presentationModuleKey = 'web-page-widget-web-page-constructor';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->typeList}";
    }  
	
    public function postProcess($model, $type, $input)
    { 
        if ($input->get('key'))
        {
            \File::makeDirectory(app_path("views/widget/"), 0777, true, true);

            $templateFile = app_path("views/widget/") . $model->getKey() . '.blade.php';

            if ($t = trim($input->get('template_content')))
            {
                $templateContent = $t;
            }
            else
            {
                $templateContent = \App::make('telenok.config')->getWidget()->get($input->get('key'))->getTemplateContent();
            }
             
            \File::put($templateFile, $templateContent);
        }
        
        return parent::postProcess($model, $type, $input);
    }
    
}

?>