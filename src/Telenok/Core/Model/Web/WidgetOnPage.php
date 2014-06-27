<?php

namespace Telenok\Core\Model\Web;

class WidgetOnPage extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'widget_on_page';

	public function isWidgetLink()
	{
		return !!$this->widget_link_widget_on_page;
	}


    public function widgetPage()
    {
        return $this->belongsTo('\Telenok\Web\Page', 'widget_page');
    }


    public function widgetLink()
    {
        return $this->hasMany('\Telenok\Web\WidgetOnPage', 'widget_link_widget_on_page');
    }


    public function widgetLinkWidgetOnPage()
    {
        return $this->belongsTo('\Telenok\Web\WidgetOnPage', 'widget_link_widget_on_page');
    }


    public function widgetLanguageLanguage()
    {
        return $this->belongsTo('\Telenok\System\Language', 'widget_language_language');
    }

}
?>