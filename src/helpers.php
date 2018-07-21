<?php 

if (!function_exists('setting_exists')) {
    function setting_exists($name) {
        return app('setting')->exists($name);
    }
}

if (!function_exists('setting')) {
    function setting($name, $value = null) {
        if ($value === null) {
            return app('setting')->get($name);
        }
        app('setting')->set($name, $value);
    }
}

if (!function_exists('setting_bool')) {
    function setting_bool($name, $value = null) {
        return app('setting')->bool($name, $value);
    }
}

if (!function_exists('setting_int')) {
    function setting_int($name, $value = null) {
        return app('setting')->integer($name, $value);
    }
}

if (!function_exists('setting_collection')) {
    function setting_collection($name, $array = null) {
        return app('setting')->collection($name, $array);
    }
}

if (!function_exists('setting_json')) {
    function setting_json($name, $array = null) {
        return app('setting')->json($name, $array);
    }
}

if (!function_exists('setting_secret')) {
    function setting_secret($name, $value = null) {
        return app('setting')->secret($name, $value);
    }
}
