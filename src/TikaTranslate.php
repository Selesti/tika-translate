<?php

namespace Selesti\TikaTranslate;

use Exception;

class TikaTranslate
{

    /*
     * @var Selesti\TikaTranslate\TikaService
     */
    protected $tika;

    /*
     * @var Selesti\TikaTranslate\TranslateService
     */
    protected $translator;

    /**
     * TikaTranslate constructor.
     * @param array $google
     * @param array $tika
     * @throws Exception
     */
    public function __construct(array $google = [], array $tika = [])
    {
        $this->initTika($tika);
        $this->initGoogle($google);
    }

    /**
     * @param array $config
     * @throws Exception
     */
    private function initTika(array $config = [])
    {
        $host = $config['host'] ?? null;
        $port = $config['port'] ?? null;
        $options = $config['options'] ?? [];

        $this->tika = new TikaService($host, $port, $options);
    }

    /**
     * @param array $config
     * @throws Exception
     */
    private function initGoogle(array $config = [])
    {
        $authMethodFound = isset($config['keyFile']);
        $authMethodFound = isset($config['keyFilePath']) ? true : $authMethodFound;
        $authMethodFound = isset($config['credentials']) ? true : $authMethodFound;

        if (!$authMethodFound) {
            throw new Exception('Could not find your credentials, please refer to https://github.com/GoogleCloudPlatform/google-cloud-php/blob/master/AUTHENTICATION.md for help.');
        }

        $this->translator = new TranslateService($config);
    }

    /**
     * @param string $input
     * @param array $config
     * @return mixed
     */
    public function translate(string $input, array $config = [])
    {
        if (file_exists($input)) {
            $input = $this->tika->getText($input);
            $input = $this->wrapFormatting($input);
        }

        return $this->unwrapFormatting(
            $this->translator->translate($input, $config)
        );
    }

    /**
     * @param $text
     * @return string
     */
    private function wrapFormatting($text)
    {
        return preg_replace('/\v+|\\\r\\\n/', '<br>', $text);
    }

    /**
     * @param $text
     * @return mixed
     */
    private function unwrapFormatting($text)
    {
        return trim(
            str_replace('<br>', "\n", $text)
        );
    }

}
