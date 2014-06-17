<?php

namespace Telenok\Core\Filter\Router\Backend;

class Controller {

    public function auth($route, $request)
    { 
        $accessControlPanel = \Auth::can('read', 'control_panel');

        if (!$accessControlPanel)
        {
            if (\Request::ajax())
            {
                return \Response::json(['error' => 'Unauthorized'], 403 /* Denied */);
            }
            else if (\Auth::guest())
            {
                return \Redirect::route('cmf.login');
            }
            else
            {
                return \Redirect::route('error.access-denied');
            }
        }
        else if (!$request->is('cmf/login') && ($request->is('cmf', 'cmf/*')) && \Auth::guest())
        {
            return \Redirect::route('cmf.login');
        }
        else if ($request->is('cmf/login') && !\Auth::guest() && $accessControlPanel)
        {
            return \Redirect::route('cmf.content');
        } 
    }
}

?>