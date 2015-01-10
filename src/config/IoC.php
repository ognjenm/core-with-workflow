<?php

app()->resolvingCallback(function($object, $app)
{
    if ($object instanceof \Telenok\Core\Interfaces\IRequest)
    {
        $object->setRequest($app['request']);
    }
}); 