<?php

use App\Support\Arr;

function array_get($array, $key, $default = null) {
    return Arr::get($array, $key, $default);
}

function array_first($array, callable $callback = null, $default = null) {
    return Arr::first($array, $callback, $default);
}

function array_last($array, callable $callback = null, $default = null) {
    return Arr::last($array, $callback, $default);
}

function array_has($array, $key) {
    return Arr::has($array, $key);
}

function array_where($array, callable $callback = null) {
    return Arr::where($array, $callback);
}

function array_only($array, $key) {
    return Arr::only($array, $key);
}

function array_forget(&$array, $keys) {
    return Arr::forget($array, $keys);
}
