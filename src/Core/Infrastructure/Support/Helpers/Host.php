<?php


if (!function_exists('getFullDomain')) {
    function getFullDomain(): string
    {
        return request()->getScheme() . '://' . request()->getHost();
    }
}
