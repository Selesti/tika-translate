<?php

namespace Selesti\TikaTranslate;

use Exception;
use Vaites\ApacheTika\Client;

class TikaService
{
    /**
     * @var \Vaites\ApacheTika\Clients\CLIClient|\Vaites\ApacheTika\Clients\WebClient
     */
    private $client;

    /**
     * The default hostname for the Tika client
     * @var string
     */
    public const DEFAULT_HOST = 'localhost';

    /**
     * The default port for the Tika client
     * @var integer
     */
    public const DEFAULT_PORT = 9998;

    /**
     * The default options for the Tika client
     * @var array
     */
    public const DEFAULT_OPTIONS = [];

    /**
     * TikaService constructor.
     * @param null $host
     * @param null $port
     * @param array $options
     * @throws Exception
     */
    public function __construct($host = null, $port = null, array $options = [])
    {
        try {
            $this->client = Client::make(
                $host ?: self::DEFAULT_HOST,
                $port ?: self::DEFAULT_PORT,
                $options ?: self::DEFAULT_OPTIONS
            );
        } catch (Exception $e) {
            throw new Exception('Could not connect to Apache Tika - reason :: ' . $e->getMessage());
        }
    }

    /**
     * @return \Vaites\ApacheTika\Clients\CLIClient|\Vaites\ApacheTika\Clients\WebClient
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @param $filePath
     * @param null $callback
     * @return string
     * @throws Exception
     */
    public function text($filePath, $callback = null)
    {
        return $this->client()->getText($filePath, $callback);
    }

    /**
     * @param $filePath
     * @param null $callback
     * @return \Vaites\ApacheTika\Metadata\Metadata
     * @throws Exception
     */
    public function meta($filePath, $callback = null)
    {
        return $this->client()->getMetadata($filePath, $callback);
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->client(), $method], $args);
    }
}
