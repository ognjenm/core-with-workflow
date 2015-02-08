<?php

namespace Telenok\Core\Module\Workflow\Variable;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

    protected $key = 'workflow-process-variable';
    protected $parent = 'workflow';
    protected $modelListClass = '\App\Model\Telenok\Workflow\Variable';

    protected $presentation = 'tree-tab-object';

    protected $presentationFormFieldListView = 'core::module.workflow-process-variable.form-field-list'; 
}