<?php

namespace Telenok\Core\Workflow\Action;

class Log extends \Telenok\Core\Interfaces\Workflow\Action {
    
    protected $minIn = 1;
    protected $minOut = 1;
 
    protected $maxIn = 1;
    protected $maxOut = 1;
    
    protected $key = 'action-log';

    public function process()
    {
        \Log::info('Business Process: Event:'.$this->getProcess()->getEvent()->getEventCode().'. Process action with code "action-log"');
        
        //$paramProcess = $process->getParam();
        
        return $this;
    }
}

?>