<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'news.api',
    [
        'News\Api' => 'lib/api.php',
    ]
);