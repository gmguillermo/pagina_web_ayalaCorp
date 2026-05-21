<?php

function get_db(): PDO
{
    $db = new PDO('sqlite:' . dirname(__DIR__) . '/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
}
