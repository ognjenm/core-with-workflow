<?php

namespace Telenok\Core\Filter\Router;

class Controller {

    public function csrf()
    {
        if (Session::token() !== Input::get('_token')) 
        {
            throw new Illuminate\Session\TokenMismatchException;
        }
    }

}

?>