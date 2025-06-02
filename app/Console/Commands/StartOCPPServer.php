<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Services\OCPPWebSocketServer;

class StartOCPPServer extends Command
{
    protected $signature = 'ocpp:server {--port=8080}';
    protected $description = 'Start the OCPP WebSocket server';

    public function handle()
    {
        $port = $this->option('port');

        $this->info("Starting OCPP WebSocket server on port {$port}...");

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new OCPPWebSocketServer()
                )
            ),
            $port
        );


        $this->info("OCPP Server is running on ws://localhost:{$port}");
        $this->info("Press Ctrl+C to stop the server");

        $server->run();
    }
}
