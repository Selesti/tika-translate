<?php

use Selesti\TikaTranslate\TikaService;

class TikaTranslateTest extends BaseTest
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

}
