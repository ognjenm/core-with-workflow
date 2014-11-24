<?php

namespace Telenok\Core\Interfaces\Controller\Backend;

abstract class Controller extends \Illuminate\Routing\Controller {

    protected $key = '';
    protected $package = '';

    public function getName()
    {
        return $this->LL('name');
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getPackage()
    {
        if ($this->package) return $this->package;
        
        $list = explode('\\', __NAMESPACE__);
        
        return strtolower(array_get($list, 1));
    }

    public function getContent()
    {
        return '';
    }   

    public function LL($key='', $param = [])
    {
        $key_ = "{$this->getPackage()}::controller/{$this->getKey()}.$key";
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