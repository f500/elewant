<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\Herd;
use Elewant\Herding\DomainModel\Herd\HerdCollection;
use Elewant\Herding\DomainModel\ShepherdId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class FormHerdHandlerSpec extends ObjectBehavior
{
    /**
     * @var HerdCollection
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    private $herdCollection;

    public function let(HerdCollection $herdCollection): void
    {
        $this->herdCollection = $herdCollection;
        $this->beConstructedWith($herdCollection);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(FormHerdHandler::class);
    }

    public function it_handles_form_herd(): void
    {
        $command = FormHerd::forShepherd('00000000-0000-0000-0000-000000000000', 'Herd is the word');

        $this->__invoke($command);

        $this->herdCollection->save(
            Argument::that(
                static function (Herd $herd): bool {
                    return $herd->shepherdId()->equals(ShepherdId::fromString('00000000-0000-0000-0000-000000000000'))
                        && $herd->name() === 'Herd is the word';
                }
            )
        )->shouldHaveBeenCalled();
    }
}
