<?php

namespace Telenok\Core\Workflow\Point\Start;

class BeforeSave extends \Telenok\Core\Interfaces\Workflow\Point {
 
    protected $minIn = 0;
    protected $minOut = 1;
 
    protected $maxIn = 0;
    protected $maxOut = 1;
    
    protected $total = 1;
    
    protected $key = 'point-start-before-save';

    protected $stencilCardinalityRules = [
        [
            'role' => 'sequence_start',
            'minimumOccurrence' => 1,
            'maximumOccurrence' => 1,
            'incomingEdges' => 
            [
                [
                    'role' => 'SequenceFlow',
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
                        'id' => 'point-start-before-save',
                        'title' => $this->LL('title'),
                        'groups' => [$this->LL('title.groups')],
                        'description' => $this->LL('description'),
                        'view' => "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?> 
                                        <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:oryx=\"http://www.b3mn.org/oryx\" width=\"40\" height=\"40\" version=\"1.0\">
                                            <defs></defs>   
                                            <oryx:magnets>    
                                                <oryx:magnet oryx:cx=\"16\" oryx:cy=\"16\" oryx:default=\"yes\" />   
                                            </oryx:magnets>   
                                            <g pointer-events=\"fill\">        
                                                <defs>   
                                                    <radialGradient id=\"background\" cx=\"10%\" cy=\"10%\" r=\"100%\" fx=\"10%\" fy=\"10%\">    
                                                        <stop offset=\"0%\" stop-color=\"#ffffff\" stop-opacity=\"1\"/>    
                                                        <stop id=\"fill_el\" offset=\"100%\" stop-color=\"#ffffff\" stop-opacity=\"1\"/>   
                                                    </radialGradient>  
                                                </defs>       
                                                <circle id=\"bg_frame\" cx=\"16\" cy=\"16\" r=\"15\" stroke=\"black\" fill=\"url(#background) white\" stroke-width=\"1\" style=\"stroke-dasharray: 5.5, 3\" />          <circle id=\"frame\" cx=\"16\" cy=\"16\" r=\"15\" stroke=\"black\" fill=\"none\" stroke-width=\"1\"/>      <rect x=\"8\" y=\"8\" width=\"16\" height=\"16\" stroke=\"black\" stroke-width=\"1\" fill=\"none\" />     <path d=\" M 10 10 L 22 10       M 10 14 L 22 14       M 10 18 L 22 18       M 10 22 L 22 22\" fill=\"none\" stroke=\"black\" />  
                                                <text font-size=\"11\"    id=\"title\"    x=\"16\" y=\"33\"    oryx:align=\"top center\"    stroke=\"black\"  ></text>   
                                            </g> 
                                        </svg> ",
                        'icon' => \Config::get('app.url')."/packages/telenok/core/js/oryx/scripts/stencilsets/telenok/icons/startevent/" . $this->getKey() . ".png",
                        'defaultAlign' => "south",
                        'roles' => ["edge", "sequence_start", "point"],
                        'properties' => [
                            [
                                "id" => "name",
                                "type" => "String",
                                "title" => $this->LL('property.title.title'),
                                "value" => $this->LL('property.title.value'),
                                "description" => "",
                                "readonly" => false,
                                "optional" => true,
                                "popular" => true,
                                "refToView" => "title",
                                "length" => "",
                                "wrapLines" => true
                            ],
                            [
                                "id" => "bgcolor",
                                "type" => "Color",
                                "title" => $this->LL('property.bgcolor.title'),
                                "value" => "#ffffff",
                                "description" => "",
                                "popular" => true,
                                "readonly" => false,
                                "optional" => false,
                                "refToView" => "fill_el",
                                "fill" => true,
                                "stroke" => false
                            ],
                            [
                                "id" => "reference",
                                "type" => "script",
                                "title" => "Refence",
                                "value" => "",
                                "description" => "",
                                "popular" => true,
                                "readonly" => false,
                                "optional" => false,
                                "script" => "property.telenok.eventlist",
                            ],
                        ],

                    ];
        }
        
        
        
        
        return $this->stencilConfig;
    }

    
}

?>