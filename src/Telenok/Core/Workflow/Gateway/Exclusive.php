<?php

namespace Telenok\Core\Workflow\Gateway;

class Exclusive extends \Telenok\Core\Interfaces\Workflow\Activity {

    protected $key = 'gateway-exclusive';

    protected $stencilCardinalityRules = [
            [
                'role' => 'gateway',
                'minimumOccurrence' => 0,
                'maximumOccurrence' => 10000,
                'outgoingEdges' => [
                    [
                        'role' => 'controlflow',
                        'maximum' => 10000
                    ]
                ],
                'incomingEdges' => [
                    [
                        'role' => 'controlflow',
                        'maximum' => 10000
                    ]
                ]
            ]
    ];

    public function getPropertyValue($data = [])
    {
        $stencilData = $this->getStencilData($data);

		$commonProperty = parent::getPropertyValue($data); 

        $commonProperty->put('type', $stencilData->get('type', 'parallel'));

        return $commonProperty;
	}

    public function process11111111111111111111111111111111111111111111111111111111111111($log = [])
    {
        $token = $this->getToken();
        
        if ($this->getLinkOut()->count() == 1)
        {
            $this->setLog($log);
            $this->setNext();
        }
        else if ($this->getLinkOut()->count() > 1)
        {
            $log = $this->getThread()->getLogResourceId($this->getId());

            $tokenId = $token->getCurrentTokenId();
            $parentTokenId = $token->getParentTokenId();
            $totalToken = $token->getTotalToken();

            $dataParentToken = \Illuminate\Support\Collection::make();

            foreach($log->filter() as $l)
            {

            }

            $data = $log->get('data', []);
        }

        return $this;
    }

    protected function setNext()
    {
        $type = $this->getInput()->get('type');

        $token = $this->getToken();

        $sourceToken = $this->getThread()->getTokens()->get($token->getSourceTokenId());
        
        $log = $this->getThread()->getLogResourceId($this->getId());
        
        if ($this->getLinkIn()->count() > 1)
        {
            //$lastData = $log->filter(function($i){ return array_get($i, 'token.sourceElementId')});
        }
        
        
        if ($type == 'exclusive')
        {
            
        }        
        elseif ($type == 'inclusive')
        {
            
        }
        else // type is parallel or not defined
        {
            return parent::setNext();
        }

        $this->getThread()->removeActiveToken($token);
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
                        'urlPropertyContent' => $this->getRouterPropertyContent(),
                        'urlStoreProperty' => $this->getRouterStoreProperty(),
						"view" => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
                                        <svg
                                           xmlns:oryx="http://www.b3mn.org/oryx"
                                           xmlns:svg="http://www.w3.org/2000/svg"
                                           xmlns="http://www.w3.org/2000/svg"
                                           version="1.0"
                                           width="40"
                                           height="40">
                                          <defs
                                             id="defs4" />
                                          <oryx:magnets>
                                            <oryx:magnet
                                               oryx:default="yes"
                                               oryx:cy="16"
                                               oryx:cx="16" />
                                          </oryx:magnets>					
                                          <g>
                                            <path
                                               d="M -4.5,16 L 16,-4.5 L 35.5,16 L 16,35.5z"
                                               id="frame"
                                               fill="#ffffff"
                                               style="stroke:#000000;stroke-width:1" />
                                            <g
                                               id="cross">
                                              <path
                                                 d="M 8.75,7.55 L 12.75,7.55 L 23.15,24.45 L 19.25,24.45 z"
                                                 style="fill:#000000;stroke:#000000;stroke-width:1" />
                                              <path
                                                 d="M 8.75,24.45 L 19.25,7.55 L 23.15,7.55 L 12.75,24.45 z"
                                                 style="fill:#000000;stroke:#000000;stroke-width:1" />
                                            </g>

                                            <text id="text_name" x="26" y="26" oryx:align="left top"/>

                                          </g>
                                        </svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/gateway/" . $this->getKey() . ".png",
						'defaultAlign' => "east",
						'roles' => ["gateway"],
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