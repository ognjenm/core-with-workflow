<?php

namespace Telenok\Core\Workflow\Activity;

class SendMessage extends \Telenok\Core\Interfaces\Workflow\Activity {
    
    protected $minIn = 1;
    protected $minOut = 1;
 
    protected $maxIn = 1;
    protected $maxOut = 1;
    
    protected $key = 'activity-send-message';

    public function process($log = [])
    {
        \Log::info('Business Process: Event:'.$this->getProcess()->getEvent()->getEventCode().'. Business Process: Process action with code "send-message"');
        
        //$paramProcess = $process->getParam();
        
        return $this;
    }
}

?>