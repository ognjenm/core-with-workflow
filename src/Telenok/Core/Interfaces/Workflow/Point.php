<?php

namespace Telenok\Core\Interfaces\Workflow;

class Point extends \Telenok\Core\Interfaces\Workflow\Element {

	public function isEventForMe(\Telenok\Core\Workflow\Event $param)
    {
        return false;
    }
  
    public function getStartEventObject($id, $permanentId, $property, $process)
    {
        return false;
    }
    
    public function fire()
    {
        return $this->getThread()->createToken($this->getId(), $this->getId());
    }

}