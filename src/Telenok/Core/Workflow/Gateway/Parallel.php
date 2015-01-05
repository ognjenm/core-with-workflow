<?php

namespace Telenok\Core\Workflow\Gateway;

class Parallel extends \Telenok\Core\Interfaces\Workflow\Activity {

    protected $key = 'gateway-parallel';

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

    public function process($log = [])
    {
        if ($this->getLinkIn()->count() == 1)
        {
            return parent::process($log);
        }
        else if ($this->getLinkIn()->count() > 1)
        {
            $token = $this->getToken();

            $logLast = $this->getThread()->getLogResourceId($this->getId())->last();

            // first time here or after erasing
            if (!$logLast || array_get($logLast, 'data.erased'))
            {
                $log['data']['processedIds'] = [$token->getSourceElementId()];
                $log['data']['erased'] = 0;
            }
            else
            {
                $processedIds = $log['data']['processedIds'];

                if (!in_array($token->getSourceElementId(), $processedIds))
                {
                    $log['data']['processedIds'][] = $token->getSourceElementId();
                }

                if (count($log['data']['processedIds']) == $this->getLinkIn()->count())
                {
                    $log['data']['erased'] = 1;

                    return parent::process($log);
                }
                else
                {
                    $this->setLog($log);
                }
            }
        }

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
                                            <oryx:magnets>
                                                <oryx:magnet
                                                    oryx:default="yes"
                                                    oryx:cy="16"
                                                    oryx:cx="16" />
                                            </oryx:magnets>
                                            <g>
                                                <defs>
                                                    <radialGradient id="background" cx="10%" cy="10%" r="100%" fx="10%" fy="10%">
                                                        <stop offset="0%" stop-color="#ffffff" stop-opacity="1"/>
                                                        <stop id="fill_el" offset="100%" stop-color="#ffffff" stop-opacity="1"/>
                                                    </radialGradient>
                                                </defs>
                                                <path
                                                   d="M -4.5,16 L 16,-4.5 L 35.5,16 L 16,35.5z"
                                                   id="bg_frame"
                                                   fill="url(#background) white"
                                                   stroke="black"
                                                   style="stroke-width:1" />
                                                <path
                                                   d="M 6.75,16 L 25.75,16 M 16,6.75 L 16,25.75"
                                                   id="path9"
                                                   stroke="black"
                                                   style="fill:none;stroke-width:3" />
                                                <text id="title" x="26" y="26" oryx:align="left top"/>
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