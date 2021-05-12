<?php


namespace spkm\isams\Controllers;

use Psr\Http\Message\ResponseInterface;
use spkm\isams\Endpoint;

/**
 * Class RoughAndReadyController
 * @package spkm\isams\Controllers
 */
class RoughAndReadyController extends Endpoint
{
    /**
     * @param  string  $method
     * @param  string  $endpoint
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, string $endpoint)
    {
        $endpoint = $this->endpoint . $endpoint;
        $response = $this->guzzle->request($method, $endpoint, ['headers' => $this->getHeaders()]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @throws \Exception
     */
    protected function setEndpoint()
    {
        $this->endpoint = $this->getDomain() . '/api/';
    }
}
