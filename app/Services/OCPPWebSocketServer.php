<?php

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\Log;

class OCPPWebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $chargingStations;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->chargingStations = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        Log::info("New connection! ({$conn->resourceId})");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        Log::info("Received message: {$msg}");

        try {
            $data = json_decode($msg, true);

            if (!$data || !isset($data[2])) {
                $this->sendError($from, "Invalid OCPP message format");
                return;
            }

            $messageType = $data[0];
            $messageId = $data[1];
            $action = $data[2];
            $payload = $data[3] ?? [];

            switch ($messageType) {
                case 2: // Call
                    $this->handleCall($from, $messageId, $action, $payload);
                    break;
                case 3: // CallResult
                    $this->handleCallResult($from, $messageId, $payload);
                    break;
                case 4: // CallError
                    $this->handleCallError($from, $messageId, $payload);
                    break;
                default:
                    $this->sendError($from, "Unknown message type");
            }
        } catch (\Exception $e) {
            Log::error("Error processing message: " . $e->getMessage());
            $this->sendError($from, "Internal server error");
        }
    }

    protected function handleCall(ConnectionInterface $conn, $messageId, $action, $payload)
    {
        switch ($action) {
            case 'BootNotification':
                $this->handleBootNotification($conn, $messageId, $payload);
                break;
            case 'Heartbeat':
                $this->handleHeartbeat($conn, $messageId);
                break;
            case 'StatusNotification':
                $this->handleStatusNotification($conn, $messageId, $payload);
                break;
            case 'StartTransaction':
                $this->handleStartTransaction($conn, $messageId, $payload);
                break;
            case 'StopTransaction':
                $this->handleStopTransaction($conn, $messageId, $payload);
                break;
            case 'MeterValues':
                $this->handleMeterValues($conn, $messageId, $payload);
                break;
            case 'Authorize':
                $this->handleAuthorize($conn, $messageId, $payload);
                break;
            default:
                $this->sendCallResult($conn, $messageId, []);
        }
    }

    protected function handleBootNotification(ConnectionInterface $conn, $messageId, $payload)
    {
        $stationId = $payload['chargePointVendor'] . '_' . $payload['chargePointSerialNumber'];
        $this->chargingStations[$conn->resourceId] = [
            'id' => $stationId,
            'vendor' => $payload['chargePointVendor'],
            'model' => $payload['chargePointModel'],
            'serialNumber' => $payload['chargePointSerialNumber'],
            'firmwareVersion' => $payload['firmwareVersion'] ?? 'Unknown',
            'lastSeen' => now()
        ];

        $response = [
            'currentTime' => now()->toISOString(),
            'interval' => 300,
            'status' => 'Accepted'
        ];

        $this->sendCallResult($conn, $messageId, $response);
        Log::info("Boot notification accepted for station: {$stationId}");
    }

    protected function handleHeartbeat(ConnectionInterface $conn, $messageId)
    {
        $response = [
            'currentTime' => now()->toISOString()
        ];
        $this->sendCallResult($conn, $messageId, $response);
    }

    protected function handleStatusNotification(ConnectionInterface $conn, $messageId, $payload)
    {
        Log::info("Status notification: Connector {$payload['connectorId']} is {$payload['status']}");
        $this->sendCallResult($conn, $messageId, []);
    }

    protected function handleStartTransaction(ConnectionInterface $conn, $messageId, $payload)
    {
        $transactionId = rand(1000, 9999);

        $response = [
            'transactionId' => $transactionId,
            'idTagInfo' => [
                'status' => 'Accepted'
            ]
        ];

        $this->sendCallResult($conn, $messageId, $response);
        Log::info("Transaction started: {$transactionId}");
    }

    protected function handleStopTransaction(ConnectionInterface $conn, $messageId, $payload)
    {
        $response = [
            'idTagInfo' => [
                'status' => 'Accepted'
            ]
        ];

        $this->sendCallResult($conn, $messageId, $response);
        Log::info("Transaction stopped: {$payload['transactionId']}");
    }

    protected function handleMeterValues(ConnectionInterface $conn, $messageId, $payload)
    {
        Log::info("Meter values received for connector: {$payload['connectorId']}");
        $this->sendCallResult($conn, $messageId, []);
    }

    protected function handleAuthorize(ConnectionInterface $conn, $messageId, $payload)
    {
        $response = [
            'idTagInfo' => [
                'status' => 'Accepted'
            ]
        ];

        $this->sendCallResult($conn, $messageId, $response);
        Log::info("Authorization request for ID: {$payload['idTag']}");
    }

    protected function handleCallResult(ConnectionInterface $conn, $messageId, $payload)
    {
        Log::info("Received call result for message: {$messageId}");
    }

    protected function handleCallError(ConnectionInterface $conn, $messageId, $payload)
    {
        Log::error("Received call error for message: {$messageId}");
    }

    protected function sendCallResult(ConnectionInterface $conn, $messageId, $payload)
    {
        $message = [3, $messageId, $payload];
        $conn->send(json_encode($message));
    }

    protected function sendError(ConnectionInterface $conn, $error)
    {
        $message = [4, uniqid(), 'InternalError', $error, []];
        $conn->send(json_encode($message));
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        unset($this->chargingStations[$conn->resourceId]);
        Log::info("Connection {$conn->resourceId} has disconnected");
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Log::error("An error has occurred: {$e->getMessage()}");
        $conn->close();
    }

    public function getConnectedStations()
    {
        return $this->chargingStations;
    }
}
