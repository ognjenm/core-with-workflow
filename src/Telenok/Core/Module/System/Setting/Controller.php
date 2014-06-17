<?php

namespace Telenok\Core\Module\System\Setting;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller {

	protected $key = 'system-setting';
    protected $presentation = 'tree-tab-object';
    protected $presentationFormFieldListView = 'core::module.setting.form-field-list'; 
	protected $typeList = 'setting';

}

?>