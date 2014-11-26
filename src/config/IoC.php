<?php

app()->resolvingAny(function($object, $app)
{
    if (
            $object instanceof \Telenok\Core\Interfaces\Controller\Backend
            || $object instanceof \Telenok\Core\Interfaces\Module\Controller
    )
    {
        $object->setRequest($app['\Illuminate\Http\Request']);
    }
});
