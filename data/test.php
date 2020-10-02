<?php
include "./Env.php";

Env::loadFile('../.env');
echo Env::get('database.hostname');
