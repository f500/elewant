<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Github;

use Elewant\Webapp\DomainModel\Contributor\ContributorList;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;

final class GithubContributorList implements ContributorList
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var array
     */
    protected $blacklist;

    /**
     * @var MessageFactory
     */
    private $requestFactory;

    /**
     * @var HttpClient
     */
    protected $client;

    public function __construct(
        string $username,
        string $repository,
        MessageFactory $requestFactory,
        HttpClient $client,
        array $blacklist = []
    ) {
        $this->username   = $username;
        $this->repository = $repository;

        $this->requestFactory = $requestFactory;
        $this->client         = $client;
        $this->blacklist      = $blacklist;
    }

    public function allContributors(): array
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            'https://api.github.com/repos/' . $this->username . '/' . $this->repository . '/contributors'
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $responseData = json_decode((string) $response->getBody(), true);
            foreach ($responseData as $contributorData) {
                if (in_array($contributorData['login'], $this->blacklist)) {
                    continue;
                }
                $contributors[] = GithubContributor::fromGithubApiCall($contributorData);
            }
        }

        return $contributors ?? [];
    }
}
