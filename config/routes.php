<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->get('/', App\Action\Home\HomeAction::class);
};
