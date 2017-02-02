<?php

if (function_exists('__autoload'))
{
    // Register any existing autoloader function with SPL, so we don't get any clashes
    spl_autoload_register('__autoload');
}

// Register ourselves with SPL
spl_autoload_register('phprequest_autoload', true, true);

function phprequest_autoload($class_name)
{
    if (class_exists($class_name, false) || strpos($class_name, 'phprequest') !== 0)
    {
        // Either already loaded, or not a PHP Request class
        return false;
    }

    $path = __DIR__.'/'.str_replace('\\', '/', str_replace('phprequest\\', 'src/', $class_name)).'.php';
    
    if (!is_readable($path))
    {
        // Can't load
        return false;
    }

    include_once $path;
}
