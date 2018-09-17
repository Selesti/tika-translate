<?php

namespace Selesti\TikaTranslate;

use Exception;
use Google\Cloud\Translate\TranslateClient;

class TranslateService
{
    private $client;

    public function __construct(array $config = [])
    {
        try {
            $this->client = new TranslateClient($config);
        } catch (Exception $e) {
            throw new Exception('Could not connect to Apache Tika - reason :: ' . $e->getMessage());
        }
    }

    /**
     * @return TranslateClient
     */
    public function client()
    {
        return $this->client;
    }

    public function translate(string $string, array $options, bool $verbose = false)
    {
        return current(
            $this->translateBatch([$string], $options)
        );
    }

    public function translateBatch($sources, $options, bool $verbose = false)
    {
        $results = $this->client()->translateBatch($sources, $options);

        if ($verbose) {
            return $results;
        }

        $strings = array_map(function ($result) {
            return $result['text'];
        }, $results);

        return $strings;
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
