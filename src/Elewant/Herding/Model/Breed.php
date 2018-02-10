<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

use ReflectionClass;

class Breed
{
    const BLACK_AMSTERDAMPHP_REGULAR        = 'BLACK_AMSTERDAMPHP_REGULAR';
    const BLACK_SYMFONY_10_YEARS_REGULAR    = 'BLACK_SYMFONY_10_YEARS_REGULAR';
    const BLACK_SYMFONY_REGULAR             = 'BLACK_SYMFONY_REGULAR';
    const BLUE_APIGILITY_REGULAR            = 'BLUE_APIGILITY_REGULAR';
    const BLUE_OPENGOODIES_REGULAR          = 'BLUE_OPENGOODIES_REGULAR';
    const BLUE_ORACLE_REGULAR               = 'BLUE_ORACLE_REGULAR';
    const BLUE_ORIGINAL_REGULAR             = 'BLUE_ORIGINAL_REGULAR';
    const BLUE_SHOPWARE_REGULAR             = 'BLUE_SHOPWARE_REGULAR';
    const BLUE_ZEND_REGULAR                 = 'BLUE_ZEND_REGULAR';
    const BLUE_ZRAY_REGULAR                 = 'BLUE_ZRAY_REGULAR';
    const BROWN_TRUENORTHPHP_REGULAR        = 'BROWN_TRUENORTHPHP_REGULAR';
    const DENIM_ZEND_REGULAR                = "DENIM_ZEND_REGULAR";
    const GOLD_OPENGOODIES_REGULAR          = 'GOLD_OPENGOODIES_REGULAR';
    const GRAY_HACK_REGULAR                 = 'GRAY_HACK_REGULAR';
    const GRAY_ROAVE_REGULAR                = 'GRAY_ROAVE_REGULAR';
    const GREEN_ZF2_REGULAR                 = 'GREEN_ZF2_REGULAR';
    const MULTICOLORED_HAPHPY_REGULAR       = 'MULTICOLORED_HAPHPY_REGULAR';
    const MULTICOLORED_PHPDIVERSITY_REGULAR = 'MULTICOLORED_PHPDIVERSITY_REGULAR';
    const ORANGE_PHPARCH_REGULAR            = 'ORANGE_PHPARCH_REGULAR';
    const PINK_OPENGOODIES_REGULAR          = 'PINK_OPENGOODIES_REGULAR';
    const PINK_ORIGINAL_REGULAR             = 'PINK_ORIGINAL_REGULAR';
    const PURPLE_HEROKU_REGULAR             = 'PURPLE_HEROKU_REGULAR';
    const PURPLE_PHPWOMEN_REGULAR           = 'PURPLE_PHPWOMEN_REGULAR';
    const RED_CHILI_REGULAR                 = 'RED_CHILI_REGULAR';
    const RED_LARAVEL_REGULAR               = 'RED_LARAVEL_REGULAR';
    const TEAL_ZEND_REGULAR                 = 'TEAL_ZEND_REGULAR';
    const WHITE_CONFOO_REGULAR              = 'WHITE_CONFOO_REGULAR';
    const WHITE_DPC_REGULAR                 = 'WHITE_DPC_REGULAR';
    const WHITE_GLOBALIS_REGULAR            = 'WHITE_GLOBALIS_REGULAR';
    const WHITE_ZEND_REGULAR                = 'WHITE_ZEND_REGULAR';
    const YELLOW_SUNSHINEPHP_REGULAR        = 'YELLOW_SUNSHINEPHP_REGULAR';

    const BLACK_AMSTERDAMPHP_LARGE        = 'BLACK_AMSTERDAMPHP_LARGE';
    const BLACK_SYMFONY_10_YEARS_LARGE    = 'BLACK_SYMFONY_10_YEARS_LARGE';
    const BLACK_SYMFONY_LARGE             = 'BLACK_SYMFONY_LARGE';
    const BLUE_OPENGOODIES_LARGE          = 'BLUE_OPENGOODIES_LARGE';
    const BLUE_ORACLE_LARGE               = 'BLUE_ORACLE_LARGE';
    const BLUE_ORIGINAL_LARGE             = 'BLUE_ORIGINAL_LARGE';
    const BLUE_SHOPWARE_LARGE             = 'BLUE_SHOPWARE_LARGE';
    const BLUE_ZEND_LARGE                 = 'BLUE_ZEND_LARGE';
    const BLUE_ZRAY_LARGE                 = 'BLUE_ZRAY_LARGE';
    const BROWN_TRUENORTHPHP_LARGE        = 'BROWN_TRUENORTHPHP_LARGE';
    const DENIM_ZEND_LARGE                = "DENIM_ZEND_LARGE";
    const GREEN_ZF2_LARGE                 = 'GREEN_ZF2_LARGE';
    const MULTICOLORED_PHPDIVERSITY_LARGE = 'MULTICOLORED_PHPDIVERSITY_LARGE';
    const ORANGE_PHPARCH_LARGE            = 'ORANGE_PHPARCH_LARGE';
    const PINK_OPENGOODIES_LARGE          = 'PINK_OPENGOODIES_LARGE';
    const PINK_ORIGINAL_LARGE             = 'PINK_ORIGINAL_LARGE';
    const PURPLE_PHPWOMEN_LARGE           = 'PURPLE_PHPWOMEN_LARGE';
    const RED_CHILI_LARGE                 = 'RED_CHILI_LARGE';
    const RED_LARAVEL_LARGE               = 'RED_LARAVEL_LARGE';
    const TEAL_ZEND_LARGE                 = 'TEAL_ZEND_LARGE';
    const WHITE_CONFOO_LARGE              = 'WHITE_CONFOO_LARGE';
    const WHITE_DPC_LARGE                 = 'WHITE_DPC_LARGE';
    const WHITE_GLOBALIS_LARGE            = 'WHITE_GLOBALIS_LARGE';
    const WHITE_ZEND_LARGE                = 'WHITE_ZEND_LARGE';
    const YELLOW_SUNSHINEPHP_LARGE        = 'YELLOW_SUNSHINEPHP_LARGE';

    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function availableTypes(): array
    {
        $reflected  = new ReflectionClass(self::class);
        $validTypes = $reflected->getConstants();

        return $validTypes;
    }


    public static function fromString(string $type): self
    {
        if (!in_array($type, self::availableTypes())) {
            throw SorryThatIsAnInvalid::breed($type);
        }

        return new self($type);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function equals($other): bool
    {
        return ($other instanceof static && $other->type === $this->type);
    }

    public function toString(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public static function __callStatic($name, $arguments)
    {
        $name = strtoupper(preg_replace('/\B([A-Z])/', '_$1', $name));

        return self::fromString($name);
    }
}
