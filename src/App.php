<?php declare(strict_types=1);

namespace Boomtown;

use Github\Client;
use HttpSoft\Emitter\SapiEmitter;
use HttpSoft\Response\HtmlResponse;

class App
{
    public function run()
    {

        $this->emitHTML();
    }

    private function emitHTML()
    {
        $response = new HtmlResponse('<p>Hi</p>');

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
}