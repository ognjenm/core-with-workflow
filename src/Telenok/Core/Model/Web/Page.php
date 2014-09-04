<?php

namespace Telenok\Core\Model\Web;

class Page extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'page';

	public function setUrlPatternAttribute($value)
	{
		$value = trim($value);

		$this->attributes['url_pattern'] = trim($value) ? $value : '/';
	}

	public function pagePageController()
	{
		return $this->belongsTo('\Telenok\Web\PageController', 'page_page_controller');
	}

	public function widget()
	{
		return $this->hasMany('\Telenok\Web\WidgetOnPage', 'widget_page');
	}
	
    public function pageDomain()
    {
        return $this->belongsTo('\Telenok\Web\Domain', 'page_domain');
    }
}

?>