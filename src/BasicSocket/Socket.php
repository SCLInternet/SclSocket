<?php
/**
 * BasicSocket Module (https://github.com/tomphp/BasicSocket)
 *
 * @link https://github.com/tomphp/BasicSocket for the canonical source repository
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 */
namespace BasicSocket;

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
     */
    public function setConnectTimeout($seconds)
    {
        $this->connectionTimeout = (int) $seconds;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setReadTimeout($seconds)
    {
        $this->readTimeout = (int) $seconds;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setBlocking($blocking = true)
    {
        $this->blocking = (boolean) $blocking;
    }

    /**
     * {@inheritDoc}
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
     */
    public function write($data)
    {
        return fputs($this->socket, (string) $data);
    }

    /**
     * {@inheritDoc}
     */
    public function read($length = 1024)
    {
        return fgets($this->socket, $length);
    }

    /**
     * {@inheritDoc}
     */
    public function closed()
    {
        return feof($this->socket);
    }

    /**
     * {@inheritDoc}
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
     */
    public function connectionErrorNo()
    {
        return $this->connectionErrorNo;
    }

    /**
     * {@inheritDoc}
     */
    public function connectionError()
    {
        return $this->connectionError;
    }
}
