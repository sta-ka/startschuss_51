<?php

function set_active($path, $class = true, $active = 'active')
{
    $attribute = $class ? "class = $active" : $active;

    return call_user_func_array('Request::is', (array)$path) ? $attribute : '';
}