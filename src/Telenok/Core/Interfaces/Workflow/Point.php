<?php

namespace Telenok\Core\Interfaces\Workflow;

class Point extends \Telenok\Core\Interfaces\Workflow\Element {

    protected $manualStart = false;

    public function manualStart()
    {
        return $this->manualStart;
    }

	public function isEventForMe(\Telenok\Core\Workflow\Event $param)
    {
        return false;
    }
  
    public function getStartEventObject($id, $permanentId, $property, $process)
    {
        return false;
    } 
}