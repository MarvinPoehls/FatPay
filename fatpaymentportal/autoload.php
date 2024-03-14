<?php

function fatredirect_autoload($class_name)
{
    $folders = ['controllers'];

    foreach ($folders as $folder) {
        $file = __DIR__.'/'.$folder.'/'.ucfirst($class_name.'.php');
        if(file_exists($file))
        {
            require_once($file);
        }
    }
}
spl_autoload_register('fatredirect_autoload');