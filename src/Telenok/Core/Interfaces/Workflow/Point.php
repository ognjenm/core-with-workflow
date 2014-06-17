<?php

namespace Telenok\Core\Interfaces\Workflow;

class Point extends \Telenok\Core\Interfaces\Workflow\Element {
  
    public function active(\Telenok\Core\Workflow\Event $param)
    {
        return false;
    }
}

?>