<?php

namespace Elewant\AppBundle\Service;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;

class GithubContributorList implements ContributorList
{
    protected $client;
    protected $username;
    protected $repository;

    /**
     * GithubService constructor.
     *
     * @param $username
     * @param $repository
     */
    public function __construct($username, $repository)
    {
        $this->username   = $username;
        $this->repository = $repository;

        $this->client = Client::createWithConfig(['timeout' => 2]);
    }

    public function allContributors(): array
    {
        $request = new Request(
            'GET',
            'https://api.github.com/repos/' . $this->username . '/' . $this->repository . '/contributors'
        );

        $response = $this->client->sendRequest($request);
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $contributors = json_decode($response->getBody(), true);
        }

        return $contributors ?? [];
    }
}
