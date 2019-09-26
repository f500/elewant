<?php /** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Breed;

use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;
use ReflectionClass;
use ReflectionException;

final class Breed
{
    public const BLACK_AMSTERDAMPHP_REGULAR = 'BLACK_AMSTERDAMPHP_REGULAR';
    public const BLACK_PHPSTORM_REGULAR = 'BLACK_PHPSTORM_REGULAR';
    public const BLACK_SYMFONY_10_YEARS_REGULAR = 'BLACK_SYMFONY_10_YEARS_REGULAR';
    public const BLACK_SYMFONY_REGULAR = 'BLACK_SYMFONY_REGULAR';
    public const BLUE_AFUP_REGULAR = 'BLUE_AFUP_REGULAR';
    public const BLUE_APIGILITY_REGULAR = 'BLUE_APIGILITY_REGULAR';
    public const BLUE_CAKE_DC_REGULAR = 'BLUE_CAKE_DC_REGULAR';
    public const BLUE_OPENGOODIES_REGULAR = 'BLUE_OPENGOODIES_REGULAR';
    public const BLUE_ORACLE_REGULAR = 'BLUE_ORACLE_REGULAR';
    public const BLUE_ORIGINAL_REGULAR = 'BLUE_ORIGINAL_REGULAR';
    public const BLUE_PHPCLASSES_REGULAR = 'BLUE_PHPCLASSES_REGULAR';
    public const BLUE_PHPYORKSHIRE_REGULAR = 'BLUE_PHPYORKSHIRE_REGULAR';
    public const BLUE_SHOPWARE_REGULAR = 'BLUE_SHOPWARE_REGULAR';
    public const BLUE_ZEND_REGULAR = 'BLUE_ZEND_REGULAR';
    public const BLUE_ZEND_ZOE_REGULAR = 'BLUE_ZEND_ZOE_REGULAR';
    public const BLUE_ZRAY_REGULAR = 'BLUE_ZRAY_REGULAR';
    public const BLUE_PHPCE_REGULAR = 'BLUE_PHPCE_REGULAR';
    public const BROWN_TRUENORTHPHP_REGULAR = 'BROWN_TRUENORTHPHP_REGULAR';
    public const CREAM_CAKE_SF_REGULAR = 'CREAM_CAKE_SF_REGULAR';
    public const DENIM_ZEND_REGULAR = 'DENIM_ZEND_REGULAR';
    public const GOLD_OPENGOODIES_REGULAR = 'GOLD_OPENGOODIES_REGULAR';
    public const GRAY_HACK_REGULAR = 'GRAY_HACK_REGULAR';
    public const GRAY_MAGENTO_REGULAR = 'GRAY_MAGENTO_REGULAR';
    public const GRAY_ROAVE_REGULAR = 'GRAY_ROAVE_REGULAR';
    public const GREEN_ZF2_REGULAR = 'GREEN_ZF2_REGULAR';
    public const MULTICOLORED_HAPHPY_REGULAR = 'MULTICOLORED_HAPHPY_REGULAR';
    public const MULTICOLORED_PHPDIVERSITY_REGULAR = 'MULTICOLORED_PHPDIVERSITY_REGULAR';
    public const ORANGE_PHPARCH_REGULAR = 'ORANGE_PHPARCH_REGULAR';
    public const PINK_OPENGOODIES_REGULAR = 'PINK_OPENGOODIES_REGULAR';
    public const PINK_ORIGINAL_REGULAR = 'PINK_ORIGINAL_REGULAR';
    public const PINK_SHOPWARE_REGULAR = 'PINK_SHOPWARE_REGULAR';
    public const PURPLE_HEROKU_REGULAR = 'PURPLE_HEROKU_REGULAR';
    public const PURPLE_PHPROUNDTABLE_REGULAR = 'PURPLE_PHPROUNDTABLE_REGULAR';
    public const PURPLE_PHPWOMEN_REGULAR = 'PURPLE_PHPWOMEN_REGULAR';
    public const RED_CAKE_PHP_REGULAR = 'RED_CAKE_PHP_REGULAR';
    public const RED_CHILI_REGULAR = 'RED_CHILI_REGULAR';
    public const RED_LARAVEL_REGULAR = 'RED_LARAVEL_REGULAR';
    public const TEAL_ZEND_REGULAR = 'TEAL_ZEND_REGULAR';
    public const TURQUOISE_CAKE_FEST_REGULAR = 'TURQUOISE_CAKE_FEST_REGULAR';
    public const WHITE_BENELUX_REGULAR = 'WHITE_BENELUX_REGULAR';
    public const WHITE_CONFOO_REGULAR = 'WHITE_CONFOO_REGULAR';
    public const WHITE_DPC_REGULAR = 'WHITE_DPC_REGULAR';
    public const WHITE_GLOBALIS_REGULAR = 'WHITE_GLOBALIS_REGULAR';
    public const WHITE_ZEND_REGULAR = 'WHITE_ZEND_REGULAR';
    public const YELLOW_DARKMIRA_REGULAR = 'YELLOW_DARKMIRA_REGULAR';
    public const YELLOW_SUNSHINEPHP_NEXMO_REGULAR = 'YELLOW_SUNSHINEPHP_NEXMO_REGULAR';
    public const YELLOW_SUNSHINEPHP_REGULAR = 'YELLOW_SUNSHINEPHP_REGULAR';
    public const BLUE_CHECK24_REGULAR = 'BLUE_CHECK24_REGULAR';
    public const BLUE_LINUXFORPHP_REGULAR = 'BLUE_LINUXFORPHP_REGULAR';

    public const BLACK_AMSTERDAMPHP_LARGE = 'BLACK_AMSTERDAMPHP_LARGE';
    public const BLACK_SYMFONY_10_YEARS_LARGE = 'BLACK_SYMFONY_10_YEARS_LARGE';
    public const BLACK_SYMFONY_LARGE = 'BLACK_SYMFONY_LARGE';
    public const BLUE_OPENGOODIES_LARGE = 'BLUE_OPENGOODIES_LARGE';
    public const BLUE_ORACLE_LARGE = 'BLUE_ORACLE_LARGE';
    public const BLUE_ORIGINAL_LARGE = 'BLUE_ORIGINAL_LARGE';
    public const BLUE_SHOPWARE_LARGE = 'BLUE_SHOPWARE_LARGE';
    public const BLUE_ZEND_LARGE = 'BLUE_ZEND_LARGE';
    public const BLUE_ZEND_ZOE_LARGE = 'BLUE_ZEND_ZOE_LARGE';
    public const BLUE_ZRAY_LARGE = 'BLUE_ZRAY_LARGE';
    public const BROWN_TRUENORTHPHP_LARGE = 'BROWN_TRUENORTHPHP_LARGE';
    public const DENIM_ZEND_LARGE = 'DENIM_ZEND_LARGE';
    public const GREEN_ZF2_LARGE = 'GREEN_ZF2_LARGE';
    public const MULTICOLORED_PHPDIVERSITY_LARGE = 'MULTICOLORED_PHPDIVERSITY_LARGE';
    public const ORANGE_PHPARCH_LARGE = 'ORANGE_PHPARCH_LARGE';
    public const PINK_OPENGOODIES_LARGE = 'PINK_OPENGOODIES_LARGE';
    public const PINK_ORIGINAL_LARGE = 'PINK_ORIGINAL_LARGE';
    public const PURPLE_PHPWOMEN_LARGE = 'PURPLE_PHPWOMEN_LARGE';
    public const RED_CHILI_LARGE = 'RED_CHILI_LARGE';
    public const RED_LARAVEL_LARGE = 'RED_LARAVEL_LARGE';
    public const TEAL_ZEND_LARGE = 'TEAL_ZEND_LARGE';
    public const WHITE_BENELUX_LARGE = 'WHITE_BENELUX_LARGE';
    public const WHITE_CONFOO_LARGE = 'WHITE_CONFOO_LARGE';
    public const WHITE_DPC_LARGE = 'WHITE_DPC_LARGE';
    public const WHITE_GLOBALIS_LARGE = 'WHITE_GLOBALIS_LARGE';
    public const WHITE_ZEND_LARGE = 'WHITE_ZEND_LARGE';
    public const YELLOW_SUNSHINEPHP_NEXMO_LARGE = 'YELLOW_SUNSHINEPHP_NEXMO_LARGE';

    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string[]
     */
    public static function availableTypes(): array
    {
        try {
            $reflected = new ReflectionClass(self::class);
        } catch (ReflectionException $exception) {
            return [];
        }

        return $reflected->getConstants();
    }

    /**
     * @param string $type
     * @return Breed
     * @throws SorryThatIsAnInvalid
     */
    public static function fromString(string $type): self
    {
        if (!in_array($type, self::availableTypes(), true)) {
            throw SorryThatIsAnInvalid::breed($type);
        }

        return new self($type);
    }

    /**
     * @param string $name
     * @param mixed[] $arguments
     * @return Breed
     * @throws SorryThatIsAnInvalid
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        $name = strtoupper((string) preg_replace('/\B([A-Z])/', '_$1', $name));

        return self::fromString($name);
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other): bool
    {
        return $other instanceof static && $other->type === $this->type;
    }

    public function toString(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
