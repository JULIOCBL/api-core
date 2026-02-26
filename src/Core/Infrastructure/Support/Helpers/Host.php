<?php


if (!function_exists('getFullDomain')) {
    /**
     * Devuelve el dominio completo (esquema + host) de la petición actual.
     *
     * @return string
     */
    function getFullDomain(): string
    {
        return request()->getScheme() . '://' . request()->getHost();
    }
}
