<?php
require_once __DIR__ . '/../database.php';
ensure_session_started();
session_unset();
session_destroy();
redirect('/index.php');
