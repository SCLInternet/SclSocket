<?php
/**
 * SclSocket Module (https://github.com/SCLInternet/SclSocket)
 *
 * @link https://github.com/SCLInternet/SclSocket for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace SclSocket;

/**
 * The socket class which provides read and write access to a socket.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
class Socket implements SocketInterface
{
    /**
     * The time in seconds for the connection to timeout
     *
     * @var int
     */
    private $connectionTimeout = 10;

    /**
     * The time in seconds for a read action to timeout
     *
     * @var int
     */
    private $readTimeout = 20;

    /**
     * Sets whether the socket blocks
     *
     * @var boolean
     */
    private $blocking = true;

    /**
     * Connection error number
     *
     * @var int
     */
    private $connectionErrorNo = 0;

    /**
     * Connection error message
     *
     * @var string
     */
    private $connectionError = '';

    /**
     * The socket resource.
     *
     * @var resource
     */
    private $socket = null;

    /**
     * {@inheritDoc}
     *
     * @param int $seconds
     *
     * @return SocketInterface
     */
    public function setConnectTimeout($seconds)
    {
        $this->connectionTimeout = (int) $seconds;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param int $seconds
     *
     * @return SocketInterface
     */
    public function setReadTimeout($seconds)
    {
        $this->readTimeout = (int) $seconds;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param boolean $blocking
     *
     * @return SocketInterface
     */
    public function setBlocking($blocking = true)
    {
        $this->blocking = (boolean) $blocking;
    }

    /**
     * {@inheritDoc}
     *
     * @param string  $host
     * @param string  $port
     * @param boolean $secure
     *
     * @return boolean
     */
    public function connect($host, $port, $secure = false)
    {
        $this->connectionError = '';
        $this->connectionErrorNo = 0;

        $host = $secure ? 'ssl://' . $host : $host;

        $this->socket = fsockopen(
            $host,
            $port,
            $this->connectionErrorNo,
            $this->connectionError,
            $this->connectionTimeout
        );

        if (!$this->socket) {
            return false;
        }

        socket_set_blocking($this->socket, $this->blocking);
        stream_set_timeout($this->socket, $this->readTimeout);

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $data
     *
     * @return int|false Number of bytes written or false.
     */
    public function write($data)
    {
        return fputs($this->socket, (string) $data);
    }

    /**
     * {@inheritDoc}
     *
     * @param int $length
     *
     * @return string|false
     */
    public function read($length = 1024)
    {
        return fgets($this->socket, $length);
    }

    /**
     * {@inheritDoc}
     *
     * @return boolean
     */
    public function closed()
    {
        return feof($this->socket);
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function disconnect()
    {
        if (null === $this->socket) {
            return;
        }

        fclose($this->socket);
        $this->socket = null;
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function connectionErrorNo()
    {
        return $this->connectionErrorNo;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function connectionError()
    {
        return $this->connectionError;
    }
}
