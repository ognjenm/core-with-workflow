<?php

namespace Telenok\Core\Module\Workflow\Thread;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller { 

    protected $key = 'workflow-thread';
    protected $parent = 'workflow';
    protected $typeList = 'workflow_thread';
    
    protected $presentation = 'tree-tab-object';
    protected $presentationView = 'core::module.workflow-thread.presentation';

}