<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ChatService;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class ChatServer extends BaseController
{
    public function startServer()
    {
        if (!$this->request->isCLI()) {
            die('This script can only be run from the command line');
        }

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new ChatService()
                )
            ),
            81
        );
        $server->run();
    }
}
