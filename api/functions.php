<?php

function escapeInput(mixed $input = ''): mixed
{
    if (is_array($input)) {
        return array_map('escapeInput', $input);
    }

    if (is_string($input)) {
        return htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
    return $input;
}

function getAccessToken(string $authorization = ''): string
{
    return !empty($authorization) ? escapeInput(trim(str_replace('Bearer', '', $authorization))) : '';
}

spl_autoload_register(function ($class) use($base_path) {
    $file = $base_path . str_replace(["_", "/", "\\"], DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }/*  else {
        throw new Exception("Class file not found: $file");
    } */
});