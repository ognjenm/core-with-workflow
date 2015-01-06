<?php

namespace Telenok\Core\Workflow\Flow;

class UncontrolledSequence extends \Telenok\Core\Interfaces\Workflow\Flow {

	protected $key = 'sequence-uncontrolled';

    public function getStencilConnectionRules()
	{
		if (empty($this->stencilConnectionRules))
		{
			$this->stencilConnectionRules = [
						[
							'role' => 'controlflow',
							'connects' => [
								[
									'from' => 'sequence_start',
									'to' => ['sequence_end', 'activity', 'gateway']
								],
								[
									'from' => 'activity',
									'to' => ['sequence_end', 'activity', 'gateway']
								],
								[
									'from' => 'gateway',
									'to' => ['sequence_end', 'activity', 'gateway']
								]
							]
						]
			];
		}

		return $this->stencilConnectionRules;
	}

	public function getStencilConfig()
	{
		if (empty($this->stencilConfig))
		{
			$this->stencilConfig = [
				'type' => 'edge',
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
                                version="1.0"
                                oryx:edge="edge" >
                                <defs>
                                    <marker id="end" refX="15" refY="6" markerUnits="userSpaceOnUse" markerWidth="15" markerHeight="12" orient="auto">
                                        <path d="M 0 1 L 15 6 L 0 11z" fill="black" stroke="black" stroke-linejoin="round" stroke-width="2" />
                                    </marker>
                                </defs>
                                <g id="edge">
                                    <path d="M10 50 L210 50" stroke="black" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" marker-start="url(#start)" marker-end="url(#end)" />
                                    <text id="title" x="0" y="0" oryx:edgePosition="startTop"/>
                                </g>
                            </svg>',
				'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/flow/" . $this->getKey() . ".png",
				'roles' => ["controlflow"],
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