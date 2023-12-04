<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class ChatService implements MessageComponentInterface
{
    /**
     * @var SplObjectStorage<ConnectionInterface> $clients
     */
    protected SplObjectStorage $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // ! Vulnerability in this twin line code
        // ! Reason : User can inject any value in the query string
        $query = $conn->httpRequest->getUri()->getQuery();
        $username = explode('=', $query)[1];

        $conn->username = $username;

        // Admin or Non-admin divider simulator
        if (($username == 'gensartx') || ($username == 'gensartx2')) {
            $conn->role = 'client';
        } else {
            $conn->role = 'admin';
        }

        // Store the new connection to send messages to later
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // If the message come from non-admin
        if ($from->role == 'client') {

            // Load message from persistence
            $chats  = json_decode(file_get_contents(WRITEPATH . '\chats.json'), true) ?? [];
            $time   = date('Y-m-d H:i:s');

            // If user conversation already exist, then just add the message
            if (array_key_exists($from->username, $chats)) {
                $chats[$from->username]['messages'][] = [
                    'message'   => $msg,
                    'from'      => $from->username,
                    'time'      => $time,
                ];
            } else {
                // If not, then create conversation and add the message
                $chats[$from->username] = [
                    // Any additional conversation data here
                    'messages'  => [
                        [
                            'message'   => $msg,
                            'from'      => $from->username,
                            'time'      => $time,
                        ]
                    ]
                ];
            }

            // Save message to persistence
            file_put_contents(WRITEPATH . '\chats.json', json_encode($chats, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            // Broadcast the message to all admins, and to the sender itself
            foreach ($this->clients as $client) {
                if (($client->role == 'admin') || ($client->username == $from->username)) {
                    $client->send(json_encode([
                        'message'   => $msg,
                        'username'  => $from->username,
                        'time'      => date('H:i, j M Y', strtotime($time)),
                    ]));
                }
            }
        } else {
            // If the message come from admin
            $messageData = json_decode($msg, true);

            $receiver   = $messageData['receiver'];
            $chats      = json_decode(file_get_contents(WRITEPATH . '\chats.json'), true) ?? [];
            $time       = date('Y-m-d H:i:s');

            if (array_key_exists($receiver, $chats)) {
                $chats[$receiver]['messages'][] = [
                    'message'   => $messageData['message'],
                    'from'      => $messageData['sender'],
                    'time'      => $time,
                ];
            } else {
                $chats[$receiver] = [
                    'messages'  => [
                        [
                            'message'   => $messageData['message'],
                            'from'      => $messageData['sender'],
                            'time'      => $time,
                        ]
                    ]
                ];
            }

            // Save message to persistence
            file_put_contents(WRITEPATH . '\chats.json', json_encode($chats, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            // Broadcast the message to all admins, and to the sender itself
            foreach ($this->clients as $client) {
                if ($client->username == $receiver) {
                    $client->send(json_encode([
                        'message'   => $msg,
                        'username'  => $from->username,
                        'time'      => date('H:i, j M Y', strtotime($time)),
                    ]));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
