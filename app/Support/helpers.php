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
