<?php

namespace Telenok\Core\Workflow\Point;

class End extends \Telenok\Core\Interfaces\Workflow\Point {
 
    protected $minIn = 1;
    protected $minOut = 0;
 
    protected $maxIn = 2000000000;
    protected $maxOut = 0;
   
    protected $total = 1;

    protected $key = 'point-end';
    
    protected $stencilConfig = [
            'type' => 'node',
            'id' => 'point-end',
            'title' => 'Point End',
            'groups' => ["Points"],
            'description' => 'Triggering the immediate termination of a process instance. All steps still in execution in parallel branches are terminated',
            "view" => "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?> <svg    xmlns=\"http://www.w3.org/2000/svg\"    xmlns:oryx=\"http://www.b3mn.org/oryx\"    width=\"40\"    height=\"40\"    version=\"1.0\">   <defs></defs>   <oryx:magnets>    <oryx:magnet oryx:cx=\"16\" oryx:cy=\"16\" oryx:default=\"yes\" />   </oryx:magnets>   <g pointer-events=\"fill\">       <defs>   <radialGradient id=\"background\" cx=\"10%\" cy=\"10%\" r=\"100%\" fx=\"10%\" fy=\"10%\">    <stop offset=\"0%\" stop-color=\"#ffffff\" stop-opacity=\"1\"/>    <stop id=\"fill_el\" offset=\"100%\" stop-color=\"#ffffff\" stop-opacity=\"1\"/>   </radialGradient>  </defs>       <circle id=\"bg_frame\" cx=\"16\" cy=\"16\" r=\"14\" stroke=\"black\" fill=\"url(#background) white\" stroke-width=\"3\"/>          <circle cx=\"16\" cy=\"16\" r=\"9\" stroke=\"black\" fill=\"black\" stroke-width=\"1\"/>  <text font-size=\"11\"    id=\"text_name\"    x=\"16\" y=\"32\"    oryx:align=\"top center\"    stroke=\"black\"  ></text>   </g> </svg> ",
            'icon' => "packages/telenok/core/js/oryx/stencilsets/epc/icons/new_xor.png",
            'defaultAlign' => "south",
            'roles' => ["edge", "sequence_end", "point"],
    ];

    protected $stencilCardinalityRules = [
            [
                'role' => 'sequence_end',
                'minimumOccurrence' => 1,
                'maximumOccurrence' => 10000,
                'outgoingEdges' => [
                    [
                        'role' => 'SequenceFlow',
                        'minimum' => 0
                    ]
                ]
            ]
    ];

}

?>