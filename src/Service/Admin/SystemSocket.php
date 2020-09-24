<?php

namespace App\Service\Admin;

use App\Entity\AppException;

/**
 * Class to read/write data thru TCP socket
 *
 * @author Mateusz Mirosławski
 */
class SystemSocket
{
    /**
     * Server app address
     */
    public const ADDRESS = 'localhost';
    
    /**
     * Max bytes to send
     */
    public const MAX_BYTES = 10000;
    
    /**
     * Socket resource
     */
    private $socket;
    
    public function __construct(int $port)
    {
        try {
            // Create socket
            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            if ($this->socket === false) {
                throw new AppException(
                    "Failed: " . socket_strerror(socket_last_error($this->socket)),
                    AppException::SOCKET_CREATE
                );
            }

            // Connect to the server
            $result = socket_connect(
                $this->socket,
                self::ADDRESS,
                $port
            );

            if ($result === false) {
                throw new AppException(
                    "Failed: " . socket_strerror(socket_last_error($this->socket)),
                    AppException::SOCKET_CONNECT
                );
            }
        } catch (\ErrorException $ex) {
            throw new AppException(
                $ex->getMessage(),
                AppException::SOCKET_ERROR
            );
        }
    }
    
    public function __destruct()
    {
        // Close socket
        socket_close($this->socket);
    }
    
    /**
     * Send message to the server
     *
     * @param string $msg Message to send
     * @return string Reply from server
     * @throws AppException
     */
    public function send(string $msg): string
    {
        // Check message length
        if (strlen($msg) > self::MAX_BYTES) {
            throw new AppException(
                "Socket message is too long - allowed " . self::MAX_BYTES . " chars",
                AppException::SOCKET_SEND
            );
        }
        
        // Send message to server
        $sendRes = socket_send($this->socket, $msg, strlen($msg), MSG_WAITALL);
        
        if ($sendRes === false) {
            throw new AppException(
                "Failed: " . socket_strerror(socket_last_error($this->socket)),
                AppException::SOCKET_SEND
            );
        }
        
        $reply = '';
        // Read reply from server
        while ($reply = socket_read($this->socket, self::MAX_BYTES)) {
            break;
        }
        
        if ($reply === false) {
            throw new AppException(
                "Failed: " . socket_strerror(socket_last_error($this->socket)),
                AppException::SOCKET_READ
            );
        }
        
        return $reply;
    }
}
