<?php

namespace Telenok\Core\Interfaces\Validator;

class Setting {

    protected $ruleList = [];
    protected $input = [];
    protected $validator;
    protected $message = [];
    protected $customAttribute = [];
    
    public function setInput(\Illuminate\Support\Collection $param = null)
    {
        $this->input = $param;

        return $this;
    }
    
    public function getInput()
    {
        return $this->input;
    }
    
    public function setMessage(array $param = null)
    {
        $this->message = array_merge(\Lang::get('core::default.error'), (array)$param);

        return $this;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function setRuleList(array $param = null)
    {
        $this->ruleList = $this->processRule($param);

        return $this;
    }
    
    public function getRuleList()
    {
        if (empty($this->ruleList))
        {
            $this->ruleList = $this->processRule($this->getModel()->getRule());
        }
        
        return $this->ruleList;
    } 
    
    public function setCustomAttribute(array $param = null)
    {
        $this->customAttribute = $param;

        return $this;
    }
    
    public function getCustomAttribute()
    {
        return $this->customAttribute;
    }

    protected function processRule($rule)
    {
        array_walk_recursive($rule, function(&$el, $key, $this_) {
            $el = str_replace('{{id}}', $this_->getInput()->get('id'), $el);
        }, $this);
        
        return $rule;
    }

    public function passes()
    {
        $this->validator = \Validator::make($this->getInput()->all(), $this->getRuleList(), $this->getMessage());

        if ($this->validator->passes()) return true;
        
        return false;
    }
}