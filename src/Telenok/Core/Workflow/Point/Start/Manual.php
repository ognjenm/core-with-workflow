<?php

namespace Telenok\Core\Workflow\Point\Start;

class Manual extends \Telenok\Core\Interfaces\Workflow\Point {

    protected $minIn = 0;
    protected $minOut = 1;

    protected $maxIn = 0;
    protected $maxOut = 1;

    protected $total = 1;
    protected $manualStart = true;

    protected $key = 'point-manual';
    protected $propertyView = 'core::workflow.point-manual.property';
    protected $routerPropertyContent = 'cmf.workflow.point-manual.property';

	public function isEventForMe(\Telenok\Core\Workflow\Event $event)
    { 
        return in_array($event->getEventCode(), ['workflow.manual.start'], true);
    }
    
    public function getStartEventObject($id, $permanentId, $property, $process)
    {
        return [$permanentId];
    }
	
    protected $stencilCardinalityRules = [
        [
            'role' => 'sequence_start',
            'minimumOccurrence' => 1,
            'maximumOccurrence' => 1,
            'incomingEdges' => 
            [
                [
                    'role' => 'controlflow',
                    'maximum' => 0
                ]
            ]
        ]
    ];
	
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
                        'urlPropertyContent' => $this->getRouterPropertyContent(),
                        'urlStoreProperty' => $this->getRouterStoreProperty(),
                        'view' => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:oryx="http://www.b3mn.org/oryx"
                                        width="40"
                                        height="40"
                                        version="1.0">
                                        <defs></defs>
                                        <oryx:magnets>
                                            <oryx:magnet oryx:cx="16" oryx:cy="16" oryx:default="yes" />
                                        </oryx:magnets>
                                        <oryx:docker oryx:cx="16" oryx:cy="16" />
                                        <g pointer-events="fill">

                                        <defs>
                                            <radialGradient id="background" cx="10%" cy="10%" r="100%" fx="10%" fy="10%">
                                                <stop offset="0%" stop-color="#ffffff" stop-opacity="1"/>
                                                <stop id="fill_el" offset="100%" stop-color="#ffffff" stop-opacity="1"/>
                                            </radialGradient>
                                        </defs>

                                        <circle id="bg_frame" cx="16" cy="16" r="15" stroke="black" fill="url(#background) white" stroke-width="1"/>
                                        <circle cx="16" cy="16" r="12" stroke="black" fill="none" stroke-width="1"/>

                                        <polygon stroke="black" fill="none" stroke-width="1.4" points="9,13 19,13 19,10 25,16 19,22 19,19 9,19" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" />

                                        <a id="diagramLink" target="_blank">
                                            <g class="diagramLink">
                                               <polygon stroke="none" class="link" fill="none" stroke-width="0" points="10,14 19,14 19,11 24,16 19,21 19,18 9,18" fill-opacity="0.5"/>
                                           </g>
                                        </a>

                                        <text font-size="11" 
                                            id="title" 
                                            x="16" y="33" 
                                            oryx:align="top center" 
                                            stroke="black"
                                        ></text>
                                      </g>
                                    </svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/pointstart/" . $this->getKey() . ".png",
                        'defaultAlign' => "east",
                        'roles' => ["sequence_start", "point"],
						'propertyPackages' => ["bgcolor", "bordercolor"],
                        'properties' => [
                            [
                                "id" => "title",
                                "type" => "string",
                                "value" => $this->LL('title'),
                                "refToView" => "title",
                            ],
                        ],
                    ];
        }
        
        return $this->stencilConfig;
    }
}