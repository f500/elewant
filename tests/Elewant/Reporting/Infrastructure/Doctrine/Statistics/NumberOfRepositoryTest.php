<?php

declare(strict_types=1);

namespace Elewant\Reporting\Infrastructure\Doctrine\Statistics;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Elewant\Reporting\DomainModel\Statistics\NumberOf;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class NumberOfRepositoryTest extends KernelTestCase
{
    /**
     * @var NumberOf
     */
    private $numberOfRepository;

    /**
     * @var EntityManagerInterface
     */
    protected static $entityManager;

    public function setUp(): void
    {
        $this->numberOfRepository = new NumberOfRepository(self::$entityManager);
    }

    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        $container = self::$container;
        self::$entityManager = $container->get('doctrine.orm.entity_manager');

        self::$entityManager->getConnection()->executeQuery(
            <<<'EOQ'
INSERT INTO `herd` (`herd_id`, `shepherd_id`, `name`, `formed_on`)
VALUES
    ('1e71ed82-3861-4c6b-a4e2-9d6e63f7d4be', '2639c1e6-cf64-44bf-a351-ae212ef5c81c', 'MyHerdName', '2018-12-31 10:00:00'),
    ('27c9776b-b615-4490-ae2c-e6030d2b30d7', '3bd790e4-50c7-4181-8011-cd3b7994b8c6', 'MyHerdName', '2018-12-31 10:00:00'),
    ('a5834bbd-b26b-41c9-b964-aadf1ba538c0', '25286e08-726b-46bb-9193-ddecd6a24751', 'MyHerdName', '2019-01-09 10:00:00'),
    ('be4abc13-ea28-40bd-876f-e3a8082137d3', '1ac7be17-d862-4e5f-ac38-5fad7ab8f1b9', 'MyHerdName', '2019-01-09 10:00:00'),
    ('cc547f41-56e7-4dd7-ae39-58dc969e8e95', '02901da8-bab3-4b05-bb4e-4767b2178e01', 'MyHerdName', '2019-01-09 10:00:00'),
    ('f12162e5-a501-4ce7-a53c-0eabd0cfa8ca', '02901da8-bab3-4b05-bb4e-4767b2178e01', 'MyHerdName', '2019-01-11 10:00:00'),
    ('5a4a73a3-20de-4f63-b256-be2d4b2bcdc0', '02901da8-bab3-4b05-bb4e-4767b2178e01', 'MyHerdName', '2019-01-11 10:00:00')
;
EOQ
        );

        self::$entityManager->getConnection()->executeQuery(
            <<<'EOQ'
INSERT INTO `elephpant` (`elephpant_id`, `herd_id`, `breed`, `adopted_on`)
VALUES
    ('6bfadf59-87c9-41be-b2ee-311b6a393ba5', '1e71ed82-3861-4c6b-a4e2-9d6e63f7d4be', 'BLACK_AMSTERDAMPHP_REGULAR', '2018-12-31 10:00:00'),
    ('03e13c49-0226-4f83-89ee-5ba09c8f9d75', '1e71ed82-3861-4c6b-a4e2-9d6e63f7d4be', 'BLACK_AMSTERDAMPHP_REGULAR', '2018-12-31 10:00:00'),
    ('b3ba61e4-ddeb-4900-b915-91cf2dd335d7', '1e71ed82-3861-4c6b-a4e2-9d6e63f7d4be', 'BLACK_AMSTERDAMPHP_REGULAR', '2019-01-09 10:00:00'),
    ('145b5b23-8157-4c74-9dc7-94d370869ca4', '27c9776b-b615-4490-ae2c-e6030d2b30d7', 'BLACK_AMSTERDAMPHP_REGULAR', '2019-01-09 10:00:00'),
    ('2e5fad18-eae2-4fde-9fca-e076e2e8b4af', 'a5834bbd-b26b-41c9-b964-aadf1ba538c0', 'BLACK_AMSTERDAMPHP_REGULAR', '2019-01-11 10:00:00')
;
EOQ
        );
    }

    /**
     * @test
     */
    public function returnsNewHerdsFormedBetween(): void
    {
        KernelTestCase::assertEquals(
            3,
            $this->numberOfRepository->newHerdsFormedBetween(
                new DateTimeImmutable('2019-01-01'),
                new DateTimeImmutable('2019-01-09')
            )
        );
    }

    /**
     * @test
     */
    public function returnsTotalNumberOfHerds(): void
    {
        KernelTestCase::assertEquals(7, $this->numberOfRepository->herdsEverFormed());
    }

    /**
     * @test
     */
    public function returnsNewElePHPantsAdoptedSince(): void
    {
        KernelTestCase::assertEquals(
            2,
            $this->numberOfRepository->newElePHPantsAdoptedBetween(
                new DateTimeImmutable('2019-01-01'),
                new DateTimeImmutable('2019-01-09')
            )
        );
    }
}
