<?php
/**
 * BasicSocket Module (https://github.com/tomphp/BasicSocket)
 *
 * @link https://github.com/tomphp/BasicSocket for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace BasicSocket;

/**
 * The interface to the socket class.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
interface SocketInterface
{
    /**
     * Sets the socket connection timeout.
     *
     * @param int $seconds
     *
     * @return SocketInterface
     */
    public function setConnectTimeout($seconds);

    /**
     * Sets the socket read timeout.
     *
     * @param int $seconds
     *
     * @return SocketInterface
     */
    public function setReadTimeout($seconds);

    /**
     * Sets if the socket blocks.
     *
     * @param boolean $blocking
     *
     * @return SocketInterface
     */
    public function setBlocking($blocking = true);

    /**
     * Opens a socket connection.
     *
     * @param string  $host
     * @param string  $port
     * @param boolean $secure
     *
     * @return boolean
     */
    public function connect($host, $port, $secure = false);

    /**
     * Sends some data down the socket.
     *
     * @param string $data
     *
     * @return int|false Number of bytes written or false.
     */
    public function write($data);

    /**
     * Read data from the socket.
     *
     * @param string $data
     * @param int    $length
     *
     * @return string|false
     */
    public function read($length = 1024);

    /**
     * Checks if the socket has been closed.
     *
     * @return boolean
     */
    public function closed();

    /**
     * Close the socket down.
     *
     * @return void
     */
    public function disconnect();
    
    /**
     * Returns the connection error number.
     *
     * @return int
     */
    public function connectionErrorNo();

    /**
     * Returns teh connection error message.
     *
     * @return string
     */
    public function connectionError();
}
