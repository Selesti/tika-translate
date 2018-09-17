<?php

use Selesti\TikaTranslate\TikaService;
use Selesti\TikaTranslate\TikaTranslate;
use Selesti\TikaTranslate\TranslateService;

class TikaTranslateTest extends BaseTestClass
{
    public function test_i_can_connect_to_tika()
    {
        // arrange
        $tika = new TikaService;

        // action
        $client = $tika->client();

        // assert
        $this->assertInstanceOf(Vaites\ApacheTika\Clients\WebClient::class, $client);
    }

    public function test_i_can_extract_a_text_file()
    {
        // arrange
        $tika = new TikaService;

        // action
        $text = $tika->text(
            __DIR__ . '/../examples/text-file.txt'
        );

        // assert
        $this->assertEquals('bonjour', $text);
    }

    public function test_i_can_translate_a_string()
    {
        // arrange
        $translator = new TranslateService();

        // action
        $translation = $translator->translate('bonjour', [
            'target' => 'de'
        ]);

        // assert
        $this->assertEquals('Hallo', $translation);
    }

    public function test_i_can_translate_multiple_strings()
    {
        // arrange
        $translator = new TranslateService();

        // action
        $translation = $translator->translateBatch(['hello', 'goodbye'], [
            'target' => 'fr'
        ]);

        // assert
        $this->assertEquals('Bonjour', $translation[0]);
        $this->assertEquals('Au revoir', $translation[1]);
    }

    public function test_i_can_translate_a_file()
    {
        // arrange
        $tt = new TikaTranslate($googleConfig = ['keyFilePath' => __DIR__ . '/../google.json'], $tikaConfig = []);

        // action
        $response = $tt->translate(__DIR__ . '/../examples/formatted-text.txt');

        // assert
        $this->assertEquals("Hello \n Goodbye", $response);
    }

    public function test_i_can_translate_text()
    {
        // arrange
        $tt = new TikaTranslate($googleConfig = ['keyFilePath' => __DIR__ . '/../google.json'], $tikaConfig = []);

        // action
        $response = $tt->translate('Hello', ['target' => 'de']);

        // assert
        $this->assertEquals("Hallo", $response);
    }



}
