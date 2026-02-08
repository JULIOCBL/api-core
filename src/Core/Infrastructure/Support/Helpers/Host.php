<?php

if (!function_exists('getFullDomain')) {
    function getFullDomain()
    {
        return request()->getScheme() . '://' . request()->getHost();
    }
}
