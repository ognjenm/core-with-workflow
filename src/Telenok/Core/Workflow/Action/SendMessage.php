<?php

namespace Telenok\Core\Workflow\Action;

class SendMessage extends \Telenok\Core\Interfaces\Workflow\Action {
    
    protected $minIn = 1;
    protected $minOut = 1;
 
    protected $maxIn = 1;
    protected $maxOut = 1;
    
    protected $key = 'send-message';

    public function process()
    {
        \Log::info('Business Process: Event:'.$this->getProcess()->getEvent()->getEventCode().'. Business Process: Process action with code "send-message"');
        
        //$paramProcess = $process->getParam();
        
        return $this;
    }
}

?>