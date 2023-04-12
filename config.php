<?php

function dpPrint($str)
{
    echo "<pre";
        print_r($str);
    echo "<pre";
}

function dpPrintBreak($str)
{
    echo "<pre";
        print_r($str);
    echo "<pre";
    exit();
}

const APP_BASE_PATH = 'my_cms';
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = 'mysql';
const DB_NAME = 'my_site';
const DB_CHARSET = 'utf8';

