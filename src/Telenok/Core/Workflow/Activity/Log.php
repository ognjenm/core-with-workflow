<?php

namespace Telenok\Core\Workflow\Activity;

class Log extends \Telenok\Core\Interfaces\Workflow\Activity {
    
    protected $minIn = 1;
    protected $minOut = 1;
 
    protected $maxIn = 1;
    protected $maxOut = 1;
    
    protected $key = 'activity-log';

    public function process($log = [])
    {
        \Log::info('Business Process: Event:'.$this->getProcess()->getEvent()->getEventCode().'. Process action with code "action-log"');
        
        //$paramProcess = $process->getParam();
        
        return $this;
    }
	
    public function getStencilConfig()
    {
        if (empty($this->stencilConfig))
        {
            $this->stencilConfig = [
						'type' => 'node',
						'id' => $this->getKey(),
                        'title' => $this->LL('title'),
                        'groups' => [$this->LL('title.groups')],
                        'description' => $this->LL('description'),
						"view" => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:oryx="http://www.b3mn.org/oryx"
   version="1.0">
  <oryx:magnets>
        <oryx:magnet oryx:cx="75" oryx:cy="0" oryx:default="yes"/>
        <oryx:magnet oryx:cx="75" oryx:cy="75"/>
        <oryx:magnet oryx:cx="0" oryx:cy="36"/>
        <oryx:magnet oryx:cx="150" oryx:cy="36"/>
  </oryx:magnets>
  <g>
    <path
       id="receiver"
       oryx:anchor="top bottom left right"
       oryx:resize="vertical horizontal"
       d="M60 0 L150 0 L150 50 L60 50 L75 25 z"
       stroke="black" fill="none" stroke-width="2" />
    <path
       id="sender"
       oryx:anchor="top bottom left right"
       oryx:resize="vertical horizontal"
       d="M0 0 L60 0 L75 25 L60 50 L0 50 z"
       stroke="black" fill="none" stroke-width="2" />
    <rect
       id="descriptionline"
       oryx:anchor="top bottom left right"
       oryx:resize="vertical horizontal"
       width="150" height="25"
       x="0" y="50"
       stroke="black" fill="none"/>
        <text id="messageType" x="75" y="60" oryx:align="middle center"></text>         
        <text id="senderText" x="4" y="28" oryx:algin="middle center"></text>
        <text id="receiverText" x="80" y="28" oryx:algin="middle center"></text>        
  </g>
</svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/scripts/stencilsets/telenok/icons/activity/" . $this->getKey() . ".png",
						'defaultAlign' => "east",
						'roles' => ["activity"],
                        'properties' => [
                            [
                                "id" => "name",
                                "type" => "String",
                                "title" => $this->LL('property.title.title'),
                                "value" => $this->LL('property.title.value'),
                                "description" => "",
                                "readonly" => false,
                                "optional" => false,
                                "popular" => false,
                                "refToView" => "title",
                                "length" => "",
                                "wrapLines" => true
                            ],
                        ],

                    ];
        }
        
        return $this->stencilConfig;
    }

	
}

?>