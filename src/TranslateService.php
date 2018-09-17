<?php

namespace Selesti\TikaTranslate;

use Exception;
use Google\Cloud\Translate\TranslateClient;

class TranslateService
{

    /**
     * @var TranslateClient
     */
    private $client;

    /**
     * TranslateService constructor.
     * @param array $config
     * @throws Exception
     */
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

    /**
     * @param string $string
     * @param array $options
     * @param bool $verbose
     * @return string|null
     */
    public function translate(string $string, array $options, bool $verbose = false)
    {
        if (is_null($string)) {
            return null;
        }

        $string = is_array($string) ? $string : [$string];

        return current(
            $this->translateBatch($string, $options, $verbose)
        );
    }

    /**
     * @param $sources
     * @param $options
     * @param bool $verbose
     * @return array
     */
    public function translateBatch(array $sources, $options, bool $verbose = false)
    {
        $mergedOptions = array_merge([
            'target' => 'en'
        ], $options);

        $results = $this->client()->translateBatch($sources, $mergedOptions);

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
