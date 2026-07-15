<?php
session_start();

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/App.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/core/Auth.php';
require_once __DIR__ . '/app/core/Validator.php';
require_once __DIR__ . '/app/core/Mail.php';

require_once __DIR__ . '/app/helpers/functions.php';

$app = new App();
$app->run();
