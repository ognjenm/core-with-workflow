<?php

namespace Telenok\Core\Workflow\Edge;

class SequenceFlow extends \Telenok\Core\Interfaces\Workflow\Edge {
 
    protected $minIn = 1;
    protected $minOut = 0;
 
    protected $maxIn = 2000000000;
    protected $maxOut = 0;
   
    protected $total = 1;

    protected $key = 'sequence-flow';
    
    protected $stencilConfig = [
            'type' => 'edge',
            'id' => 'sequence-flow',
            'title' => 'Sequence Flow',
            'groups' => ["Connecting Objects"],
            'description' => 'Sequence Flow defines the execution order of activities',
            "view" => "<svg  xmlns=\"http://www.w3.org/2000/svg\"  xmlns:oryx=\"http://www.b3mn.org/oryx\"  version=\"1.0\"  oryx:edge=\"edge\" >  <defs>     <marker id=\"start\" refX=\"1\" refY=\"5\" markerUnits=\"userSpaceOnUse\" markerWidth=\"17\" markerHeight=\"11\" orient=\"auto\">      <path id=\"conditional\" d=\"M 0 5 L 8 0 L 16 5 L 8 10 L 0 5\" fill=\"white\" stroke=\"black\" stroke-width=\"1\" />    <path id=\"default\" d=\"M 5 0 L 11 10\" fill=\"white\" stroke=\"black\" stroke-width=\"1\" />     </marker>     <marker id=\"end\" refX=\"15\" refY=\"6\" markerUnits=\"userSpaceOnUse\" markerWidth=\"15\" markerHeight=\"12\" orient=\"auto\">      <path d=\"M 0 1 L 15 6 L 0 11z\" fill=\"black\" stroke=\"black\" stroke-linejoin=\"round\" stroke-width=\"2\" />     </marker>  </defs>  <g id=\"edge\">   <path d=\"M10 50 L210 50\" stroke=\"black\" fill=\"none\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" marker-start=\"url(#start)\" marker-end=\"url(#end)\" />   <text id=\"condition\" x=\"0\" y=\"0\" oryx:edgePosition=\"startTop\"/>  </g> </svg> ",
            'icon' => "//laravelnew.ru/packages/telenok/core/js/oryx/stencilsets/epc/icons/new_flow.png",
            'defaultAlign' => "south",
            'roles' => ["edge"],
            'properties' => [],
    ];
    
    protected $stencilConnectionRules = [
            [
                'role' => 'edge',
                'connects' => [
                    [
                        'from' => 'sequence_start',
                        'to' => ['sequence_end', 'activity']
                    ],
                    [
                        'from' => 'activity',
                        'to' => ['sequence_end', 'activity']
                    ],
                    [
                        'from' => 'activity',
                        'to' => ['activity']
                    ]
                ]
            ]
    ];

}

?>