<?php

namespace Telenok\Core\Interfaces\Eloquent;

interface Controller {

    public function save($input = [], $type = null);
    public function validate($model = null, $input = null, $message = []);
}

?>