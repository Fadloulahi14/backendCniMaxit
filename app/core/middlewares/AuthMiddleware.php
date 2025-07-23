<?php
namespace App\core\middlewares;

use App\core\Session;
use App\core\App;

class AuthMiddleware {
    

    public function __invoke() {
        $session = App::getDependency('session');
        if (!$session->get('Client') ) {
            header('Location: /');
            exit();
        }
    }

    

}

// && !$session->get('')

