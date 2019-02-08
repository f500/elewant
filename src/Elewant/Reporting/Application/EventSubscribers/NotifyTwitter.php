<?php

declare(strict_types=1);

namespace Elewant\Reporting\Application\EventSubscribers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Elewant\Reporting\DomainModel\HerdingStatisticsGenerated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class NotifyTwitter implements EventSubscriberInterface
{
    /**
     * @var TwitterOAuth
     */
    private $twitterClient;

    /**
     * @var bool
     */
    private $tweetsAreActive;

    public function __construct(TwitterOAuth $twitterClient, bool $activateTweets = false)
    {
        $this->twitterClient = $twitterClient;
        $this->tweetsAreActive = $activateTweets;
    }

    /**
     * @return mixed[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            HerdingStatisticsGenerated::NAME => 'sendHerdingStatisticsWhen',
        ];
    }

    public function sendHerdingStatisticsWhen(HerdingStatisticsGenerated $event): void
    {
        if (!$this->tweetsAreActive) {
            return;
        }

        $statistics = $event->statistics();

        $status = sprintf(
            'From %s to %s, %s new Shepherds have registered a Herd, resulting in %s new ElePHPants!',
            $statistics->from()->format('F jS'),
            $statistics->to()->format('F jS'),
            $statistics->numberOfNewHerds(),
            $statistics->numberOfNewElePHPants()
        );

        $this->twitterClient->post('statuses/update', ['status' => $status]);
    }
}
