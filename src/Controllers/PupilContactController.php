<?php

namespace spkm\isams\Controllers;

use spkm\isams\Endpoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use spkm\isams\Wrappers\PupilContact;

/**
 * IMPORTANT NOTE:
 *
 * As of July 2018, iSAMS have notified us that the contacts API endpoints are temporary & will be changed with the rollout of
 * the upgraded pupil contact module in 2019/2020
 */
class PupilContactController extends Endpoint
{
    /**
     * Set the URL the request is made to
     *
     * @return void
     * @throws \Exception
     */
    protected function setEndpoint(): void
    {
        $this->endpoint = $this->getDomain().'/api/students';
    }

    /**
     * Create a new resource.
     *
     * @param string $schoolId
     * @param array $attributes
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @deprecated
     */
    public function store(string $schoolId, array $attributes): JsonResponse
    {
        $this->validate([
            'relationship',
            'title',
            'forename',
            'surname',
            'address1',
            'postcode',
            'country',
        ], $attributes);

        $this->endpoint = $this->endpoint.'/'.$schoolId.'/tempcontacts';

        $response = $this->guzzle->request('POST', $this->endpoint, [
            'headers' => $this->getHeaders(),
            'json' => $attributes,
        ]);

        return $this->response(201, $response, 'The contact has been created.');
    }

    /**
     * Get all contacts for the specified pupil
     *
     * @param string $schoolId
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @deprecated
     */
    public function show(string $schoolId): Collection
    {
        $this->endpoint = $this->endpoint.'/'.$schoolId.'/tempcontacts';

        $response = $this->guzzle->request('GET', $this->endpoint, ['headers' => $this->getHeaders()]);

        $decoded = json_decode($response->getBody()->getContents());

        $contacts = collect($decoded->contacts)->map(function ($item) {
            return new PupilContact($item);
        });

        return $contacts;
    }

    /**
     * Get a specific pupil contact.
     *
     * @param string $schoolId
     * @param int $contactId
     * @return \spkm\isams\Wrappers\PupilContact
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @deprecated
     */
    public function showContact(string $schoolId, int $contactId): PupilContact
    {
        $this->endpoint = $this->endpoint.'/'.$schoolId.'/tempcontacts/'.$contactId;

        $response = $this->guzzle->request('GET', $this->endpoint, ['headers' => $this->getHeaders()]);

        $decoded = json_decode($response->getBody()->getContents());

        return new PupilContact($decoded);
    }

    /**
     * Update the specified resource.
     *
     * @param string $schoolId
     * @param int $contactId
     * @param array $attributes
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @deprecated
     */
    public function update(string $schoolId, int $contactId, array $attributes): JsonResponse
    {
        $this->validate([
            'relationship',
            'title',
            'forename',
            'surname',
            'address1',
            'postcode',
            'country',
        ], $attributes);

        $this->endpoint = $this->endpoint.'/'.$schoolId.'/tempcontacts/'.$contactId;

        $response = $this->guzzle->request('PUT', $this->endpoint, [
            'headers' => $this->getHeaders(),
            'json' => $attributes,
        ]);

        return $this->response(200, $response, 'The contact has been updated.');
    }
}
