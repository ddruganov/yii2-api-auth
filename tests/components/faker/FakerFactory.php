<?php

namespace tests\components\faker;

use Faker\Factory as BaseFakerFactory;

class FakerFactory extends BaseFakerFactory
{
    public static function create($locale = self::DEFAULT_LOCALE)
    {
        return self::createFromClass(Generator::class, $locale);
    }

    private static function createFromClass(string $className, $locale = self::DEFAULT_LOCALE)
    {
        $generator = new $className();

        foreach (static::$defaultProviders as $provider) {
            $providerClassName = self::getProviderClassname($provider, $locale);
            $generator->addProvider(new $providerClassName($generator));
        }

        return $generator;
    }
}
