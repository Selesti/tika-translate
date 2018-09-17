<?php

use Selesti\TikaTranslate\TikaService;
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

        dd($translation);

        // assert
        $this->assertEquals('Hallo', $translation);

    }


}
