<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

use ReflectionClass;

class Breed
{
    const BLUE_ORIGINAL_REGULAR             = 'BLUE_ORIGINAL_REGULAR';
    const BLUE_ORACLE_REGULAR               = 'BLUE_ORACLE_REGULAR';
    const BLUE_ZEND_REGULAR                 = 'BLUE_ZEND_REGULAR';
    const PINK_ORIGINAL_REGULAR             = 'PINK_ORIGINAL_REGULAR';
    const GREEN_ZF2_REGULAR                 = 'GREEN_ZF2_REGULAR';
    const RED_CHILI_REGULAR                 = 'RED_CHILI_REGULAR';
    const ORANGE_PHPARCH_REGULAR            = 'ORANGE_PHPARCH_REGULAR';
    const PURPLE_PHPWOMEN_REGULAR           = 'PURPLE_PHPWOMEN_REGULAR';
    const YELLOW_SUNSHINEPHP_REGULAR        = 'YELLOW_SUNSHINEPHP_REGULAR';
    const RED_LARAVEL_REGULAR               = 'RED_LARAVEL_REGULAR';
    const BLUE_APIGILITY_REGULAR            = 'BLUE_APIGILITY_REGULAR';
    const BLUE_ZRAY_REGULAR                 = 'BLUE_ZRAY_REGULAR';
    const BLACK_AMSTERDAMPHP_REGULAR        = 'BLACK_AMSTERDAMPHP_REGULAR';
    const WHITE_CONFOO_REGULAR              = 'WHITE_CONFOO_REGULAR';
    const GOLD_OPENGOODIES_REGULAR          = 'GOLD_OPENGOODIES_REGULAR';
    const BLUE_OPENGOODIES_REGULAR          = 'BLUE_OPENGOODIES_REGULAR';
    const PINK_OPENGOODIES_REGULAR          = 'PINK_OPENGOODIES_REGULAR';
    const MULTICOLORED_HAPHPY_REGULAR       = 'MULTICOLORED_HAPHPY_REGULAR';
    const TEAL_ZEND_REGULAR                 = 'TEAL_ZEND_REGULAR';
    const BLACK_SYMFONY_10_YEARS_REGULAR    = 'BLACK_SYMFONY_10_YEARS_REGULAR';
    const BLACK_SYMFONY_REGULAR             = 'BLACK_SYMFONY_REGULAR';
    const BLUE_SHOPWARE_REGULAR             = 'BLUE_SHOPWARE_REGULAR';
    const WHITE_GLOBALIS_REGULAR            = 'WHITE_GLOBALIS_REGULAR';
    const WHITE_DPC_REGULAR                 = 'WHITE_DPC_REGULAR';
    const WHITE_ZEND_REGULAR                = 'WHITE_ZEND_REGULAR';
    const MULTICOLORED_PHPDIVERSITY_REGULAR = 'MULTICOLORED_PHPDIVERSITY_REGULAR';

    const BLUE_ORIGINAL_LARGE             = 'BLUE_ORIGINAL_LARGE';
    const BLUE_ORACLE_LARGE               = 'BLUE_ORACLE_LARGE';
    const BLUE_ZEND_LARGE                 = 'BLUE_ZEND_LARGE';
    const PINK_ORIGINAL_LARGE             = 'PINK_ORIGINAL_LARGE';
    const GREEN_ZF2_LARGE                 = 'GREEN_ZF2_LARGE';
    const RED_CHILI_LARGE                 = 'RED_CHILI_LARGE';
    const ORANGE_PHPARCH_LARGE            = 'ORANGE_PHPARCH_LARGE';
    const PURPLE_PHPWOMEN_LARGE           = 'PURPLE_PHPWOMEN_LARGE';
    const YELLOW_SUNSHINEPHP_LARGE        = 'YELLOW_SUNSHINEPHP_LARGE';
    const RED_LARAVEL_LARGE               = 'RED_LARAVEL_LARGE';
    const BLUE_APIGILITY_LARGE            = 'BLUE_APIGILITY_LARGE';
    const BLUE_ZRAY_LARGE                 = 'BLUE_ZRAY_LARGE';
    const BLACK_AMSTERDAMPHP_LARGE        = 'BLACK_AMSTERDAMPHP_LARGE';
    const WHITE_CONFOO_LARGE              = 'WHITE_CONFOO_LARGE';
    const GOLD_OPENGOODIES_LARGE          = 'GOLD_OPENGOODIES_LARGE';
    const BLUE_OPENGOODIES_LARGE          = 'BLUE_OPENGOODIES_LARGE';
    const PINK_OPENGOODIES_LARGE          = 'PINK_OPENGOODIES_LARGE';
    const MULTICOLORED_HAPHPY_LARGE       = 'MULTICOLORED_HAPHPY_LARGE';
    const TEAL_ZEND_LARGE                 = 'TEAL_ZEND_LARGE';
    const BLACK_SYMFONY_10_YEARS_LARGE    = 'BLACK_SYMFONY_10_YEARS_LARGE';
    const BLACK_SYMFONY_LARGE             = 'BLACK_SYMFONY_LARGE';
    const BLUE_SHOPWARE_LARGE             = 'BLUE_SHOPWARE_LARGE';
    const WHITE_GLOBALIS_LARGE            = 'WHITE_GLOBALIS_LARGE';
    const WHITE_DPC_LARGE                 = 'WHITE_DPC_LARGE';
    const WHITE_ZEND_LARGE                = 'WHITE_ZEND_LARGE';
    const MULTICOLORED_PHPDIVERSITY_LARGE = 'MULTICOLORED_PHPDIVERSITY_LARGE';

    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromString(string $type): self
    {
        $reflected = new ReflectionClass(self::class);
        $validTypes = $reflected->getConstants();

        if (!in_array($type, $validTypes)) {
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

    public static function blueOriginalRegular(): self
    {
        return new self(self::BLUE_ORIGINAL_REGULAR);
    }

    public static function blueOracleRegular(): self
    {
        return new self(self::BLUE_ORACLE_REGULAR);
    }

    public static function blueZendRegular(): self
    {
        return new self(self::BLUE_ZEND_REGULAR);
    }

    public static function pinkOriginalRegular(): self
    {
        return new self(self::PINK_ORIGINAL_REGULAR);
    }

    public static function greenZf2Regular(): self
    {
        return new self(self::GREEN_ZF2_REGULAR);
    }

    public static function redChiliRegular(): self
    {
        return new self(self::RED_CHILI_REGULAR);
    }

    public static function orangePhparchRegular(): self
    {
        return new self(self::ORANGE_PHPARCH_REGULAR);
    }

    public static function purplePhpwomenRegular(): self
    {
        return new self(self::PURPLE_PHPWOMEN_REGULAR);
    }

    public static function yellowSunshinephpRegular(): self
    {
        return new self(self::YELLOW_SUNSHINEPHP_REGULAR);
    }

    public static function redLaravelRegular(): self
    {
        return new self(self::RED_LARAVEL_REGULAR);
    }

    public static function blueApigilityRegular(): self
    {
        return new self(self::BLUE_APIGILITY_REGULAR);
    }

    public static function blueZrayRegular(): self
    {
        return new self(self::BLUE_ZRAY_REGULAR);
    }

    public static function blackAmsterdamphpRegular(): self
    {
        return new self(self::BLACK_AMSTERDAMPHP_REGULAR);
    }

    public static function whiteConfooRegular(): self
    {
        return new self(self::WHITE_CONFOO_REGULAR);
    }

    public static function goldOpengoodiesRegular(): self
    {
        return new self(self::GOLD_OPENGOODIES_REGULAR);
    }

    public static function blueOpengoodiesRegular(): self
    {
        return new self(self::BLUE_OPENGOODIES_REGULAR);
    }

    public static function pinkOpengoodiesRegular(): self
    {
        return new self(self::PINK_OPENGOODIES_REGULAR);
    }

    public static function multicoloredHaphpyRegular(): self
    {
        return new self(self::MULTICOLORED_HAPHPY_REGULAR);
    }

    public static function tealZendRegular(): self
    {
        return new self(self::TEAL_ZEND_REGULAR);
    }

    public static function blackSymfony10YearsRegular(): self
    {
        return new self(self::BLACK_SYMFONY_10_YEARS_REGULAR);
    }

    public static function blackSymfonyRegular(): self
    {
        return new self(self::BLACK_SYMFONY_REGULAR);
    }

    public static function blueShopwareRegular(): self
    {
        return new self(self::BLUE_SHOPWARE_REGULAR);
    }

    public static function whiteGlobalisRegular(): self
    {
        return new self(self::WHITE_GLOBALIS_REGULAR);
    }

    public static function whiteDpcRegular(): self
    {
        return new self(self::WHITE_DPC_REGULAR);
    }

    public static function whiteZendRegular(): self
    {
        return new self(self::WHITE_ZEND_REGULAR);
    }

    public static function multicoloredPhpdiversityRegular(): self
    {
        return new self(self::MULTICOLORED_PHPDIVERSITY_REGULAR);
    }

    public static function blueOriginalLarge(): self
    {
        return new self(self::BLUE_ORIGINAL_LARGE);
    }

    public static function blueOracleLarge(): self
    {
        return new self(self::BLUE_ORACLE_LARGE);
    }

    public static function blueZendLarge(): self
    {
        return new self(self::BLUE_ZEND_LARGE);
    }

    public static function pinkOriginalLarge(): self
    {
        return new self(self::PINK_ORIGINAL_LARGE);
    }

    public static function greenZf2Large(): self
    {
        return new self(self::GREEN_ZF2_LARGE);
    }

    public static function redChiliLarge(): self
    {
        return new self(self::RED_CHILI_LARGE);
    }

    public static function orangePhparchLarge(): self
    {
        return new self(self::ORANGE_PHPARCH_LARGE);
    }

    public static function purplePhpwomenLarge(): self
    {
        return new self(self::PURPLE_PHPWOMEN_LARGE);
    }

    public static function yellowSunshinephpLarge(): self
    {
        return new self(self::YELLOW_SUNSHINEPHP_LARGE);
    }

    public static function redLaravelLarge(): self
    {
        return new self(self::RED_LARAVEL_LARGE);
    }

    public static function blueApigilityLarge(): self
    {
        return new self(self::BLUE_APIGILITY_LARGE);
    }

    public static function blueZrayLarge(): self
    {
        return new self(self::BLUE_ZRAY_LARGE);
    }

    public static function blackAmsterdamphpLarge(): self
    {
        return new self(self::BLACK_AMSTERDAMPHP_LARGE);
    }

    public static function whiteConfooLarge(): self
    {
        return new self(self::WHITE_CONFOO_LARGE);
    }

    public static function goldOpengoodiesLarge(): self
    {
        return new self(self::GOLD_OPENGOODIES_LARGE);
    }

    public static function blueOpengoodiesLarge(): self
    {
        return new self(self::BLUE_OPENGOODIES_LARGE);
    }

    public static function pinkOpengoodiesLarge(): self
    {
        return new self(self::PINK_OPENGOODIES_LARGE);
    }

    public static function multicoloredHaphpyLarge(): self
    {
        return new self(self::MULTICOLORED_HAPHPY_LARGE);
    }

    public static function tealZendLarge(): self
    {
        return new self(self::TEAL_ZEND_LARGE);
    }

    public static function blackSymfony10YearsLarge(): self
    {
        return new self(self::BLACK_SYMFONY_10_YEARS_LARGE);
    }

    public static function blackSymfonyLarge(): self
    {
        return new self(self::BLACK_SYMFONY_LARGE);
    }

    public static function blueShopwareLarge(): self
    {
        return new self(self::BLUE_SHOPWARE_LARGE);
    }

    public static function whiteGlobalisLarge(): self
    {
        return new self(self::WHITE_GLOBALIS_LARGE);
    }

    public static function whiteDpcLarge(): self
    {
        return new self(self::WHITE_DPC_LARGE);
    }

    public static function whiteZendLarge(): self
    {
        return new self(self::WHITE_ZEND_LARGE);
    }

    public static function multicoloredPhpdiversityLarge(): self
    {
        return new self(self::MULTICOLORED_PHPDIVERSITY_LARGE);
    }

}
