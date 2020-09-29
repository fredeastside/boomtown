<?php declare(strict_types=1);

namespace Boomtown;

use Github\Client;
use Github\Exception\RuntimeException;
use HttpSoft\Emitter\SapiEmitter;
use HttpSoft\Response\HtmlResponse;

class App
{
    public function run()
    {
        $client = new Client();
        try {
            $organization = $client->api('organizations');
            $hooks = $organization->repositories()->all('boomtownroi');
        } catch (RuntimeException $exception) {

        }
        //$repositories = $client->api('organizations')->show('boomtownroi');

        $this->emitHTML();
    }

    private function emitHTML()
    {
        $response = new HtmlResponse('<p>Hi</p>');

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
}