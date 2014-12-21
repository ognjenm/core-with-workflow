<?php

namespace Telenok\Core\Module\Workflow\Parameter;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

    protected $key = 'workflow-process-parameter';
    protected $parent = 'workflow';
    protected $modelListClass = '\App\Model\Telenok\Workflow\Parameter';

    protected $presentation = 'tree-tab-object';

    protected $presentationFormFieldListView = 'core::module.workflow-process-parameter.form-field-list'; 
}