<?php

namespace Telenok\Core\Support;


class Collection extends \Illuminate\Support\Collection {

    public function getDot($key, $default = null)
    {
        return \Illuminate\Support\Arr::get($this->items, $key, $default);
    }
    
}
