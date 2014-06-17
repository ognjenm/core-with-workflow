<?php

namespace Telenok\Core\Interfaces\Workflow;

class Element {

    protected $linkIn = [];
    protected $linkOut = [];

    protected $minIn = 0;
    protected $minOut = 0;
 
    protected $maxIn = 0;
    protected $maxOut = 0;

    protected $total = 2000000000;

    protected $id = '';
    protected $key = '';
    protected $package = '';

    protected $process;
    protected $action;
    protected $param = [];

    protected $stencilConfig = [];
    protected $stencilContainmentRules = [];
    protected $stencilCardinalityRules = [];
    protected $stencilConnectionRules = [];
    protected $stencilMorphingRules = [];
    protected $stencilLayoutRules = [];

    public function make()
    {
        return new static;
    }

    public function getStencilContainmentRules()
    {
        return $this->stencilContainmentRules;
    }

    public function getStencilCardinalityRules()
    {
        return $this->stencilCardinalityRules;
    }

    public function getStencilConnectionRules()
    {
        return $this->stencilConnectionRules;
    }

    public function getStencilMorphingRules()
    {
        return $this->stencilMorphingRules;
    }

    public function getStencilLayoutRules()
    {
        return $this->stencilLayoutRules;
    }

    public function getStencilConfig()
    {
        return $this->stencilConfig;
    }

    public function setStencilSetConfig(array $param = [])
    {
        $this->stencilConfig = array_merge($this->stencilConfig, $param);

        return $this;
    }













    public function setAction($param = [])
    {
        $this->action = $param;

        $this->setId(array_get($param, 'id'))
            ->setLinkIn((array)array_get($param, 'link-in'))
            ->setLinkOut((array)array_get($param, 'link-out'))
            ->setParam((array)array_get($param, 'param'));
        
        return $this;
    }

    public function setProcess(\Telenok\Core\Interfaces\Workflow\Process $param)
    {
        $this->process = $param;
        
        return $this;
    }

    public function getProcess()
    {
        return $this->process;
    }

    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function process()
    {
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }
    
    public function getLinkIn()
    {
        return $this->linkIn;
    }
    
    public function setLinkIn($link)
    {
        $this->linkIn = $link;

        return $this;
    }

    public function getLinkOut()
    {
        return $this->linkOut;
    }

    public function setLinkOut($link)
    {
        $this->linkOut = $link;

        return $this;
    }

    public function getMinIn()
    {
        return $this->minIn;
    }

    public function setMinIn($min)
    {
        $this->minIn = $min;

        return $this;
    }

    public function getMinOut()
    {
        return $this->minOut;
    }

    public function setMinOut($min)
    {
        $this->minOut = $min;

        return $this;
    }

    public function getMaxIn()
    {
        return $this->maxIn;
    }

    public function setMaxIn($min)
    {
        $this->maxIn = $max;

        return $this;
    }

    public function getMaxOut()
    {
        return $this->maxOut;
    }

    public function setMaxOut($max)
    {
        $this->maxOut = $max;

        return $this;
    }
    
    

    public function getPackage()
    {
        if ($this->package) return $this->package;
        
        $list = explode('\\', __NAMESPACE__);
        
        return strtolower(array_get($list, 1));
    }

    public function LL($key='', $param=[])
    {
        $key_ = "{$this->getPackage()}::workflow-element/{$this->getKey()}.$key";
        $key_default_ = "{$this->getPackage()}::default.$key";
        
        $word = \Lang::get($key_, $param);
        
        // not found in current wordspace
        if ($key_ === $word)
        {
            $word = \Lang::get($key_default_, $param);
            
            // not found in default wordspace
            if ($key_default_ === $word)
            {
                return $key_;
            }
        } 

        return $word;
    }
}

?>