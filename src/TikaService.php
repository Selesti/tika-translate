<?php

namespace Selesti\TikaTranslate;

use Exception;
use Vaites\ApacheTika\Client;

class TikaService
{
    private $client;

    public function __construct($host = 'localhost', $port = 9998, array $options = [])
    {
        try {
            $this->client = Client::make($host, $port);
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
     * @return string
     * @throws Exception
     */
    public function text($filePath, $callback = null)
    {
        return $this->client()->getText($filePath, $callback = null);
    }

    /**
     * @param $filePath
     * @return \Vaites\ApacheTika\Metadata\Metadata
     * @throws Exception
     */
    public function meta($filePath, $callback = null)
    {
        return $this->client()->getMetadata($filePath, $callback = null);
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
