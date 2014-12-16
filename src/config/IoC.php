<?php

app()->resolvingAny(function($object, $app)
{
    if (
            $object instanceof \Telenok\Core\Interfaces\Controller\Backend\Controller
            || $object instanceof \Telenok\Core\Interfaces\Module\IModule
            || $object instanceof \Telenok\Core\Interfaces\Field\IField
    )
    {
        $object->setRequest($app['request']);
    }
}); 