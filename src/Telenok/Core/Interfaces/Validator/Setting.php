<?php

namespace Telenok\Core\Interfaces\Validator;

class Setting {

    protected $ruleList;
    protected $input;
    protected $validator;
    protected $message;

    public function __construct($rule = [], $input = null, $message = [])
    {
        $input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make($input);

        $this->input = !$input->isEmpty() ? $input : \Illuminate\Support\Collection::make(\Input::all());
        $this->message = array_merge(\Lang::get('core::default.error'), (array)$message);
        $this->ruleList = $this->processRule($rule);
    }

    protected function processRule($rule)
    {
        array_walk_recursive($rule, function(&$el, $key, $this_) {
            $el = str_replace('{{id}}', array_get($this_->input, 'id'), $el);
        }, $this);
        
        return $rule;
    }

    public function passes()
    {
        $this->validator = \Validator::make($this->input->all(), $this->ruleList, $this->message);

        if ($this->validator->passes()) return true;
        
        return false;
    }

    public function fails()
    {
        return !$this->passes();
    }

    public function messages()
    {
        $messages = $this->validator->messages()->all();
        
        return empty($messages) ? ['undefined' => $this->message['undefined']] : $messages;
    }

    public function validator()
    {
        return $this->validator;
    }
}

?>