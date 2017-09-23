<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6a6d632d67e4f39705df4f1767d24dc8
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'coffee\\' => 7,
        ),
        'W' => 
        array (
            'Whoops\\' => 7,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
        'K' => 
        array (
            'Klein\\' => 6,
        ),
        'I' => 
        array (
            'Illuminate\\Support\\' => 19,
            'Illuminate\\Database\\' => 20,
            'Illuminate\\Contracts\\' => 21,
            'Illuminate\\Container\\' => 21,
        ),
        'D' => 
        array (
            'Doctrine\\' => 9,
        ),
        'C' => 
        array (
            'Carbon\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'coffee\\' => 
        array (
            0 => __DIR__ . '/..' . '/coffee',
        ),
        'Whoops\\' => 
        array (
            0 => __DIR__ . '/..' . '/services/whoops/src/Whoops',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/symfony/translation',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/psr/container/src',
        ),
        'Klein\\' => 
        array (
            0 => __DIR__ . '/..' . '/services/klein/src/Klein',
        ),
        'Illuminate\\Support\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/support',
        ),
        'Illuminate\\Database\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/database',
        ),
        'Illuminate\\Contracts\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/contracts',
        ),
        'Illuminate\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/container',
        ),
        'Doctrine\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/doctrine/inflector/lib/Doctrine',
        ),
        'Carbon\\' => 
        array (
            0 => __DIR__ . '/..' . '/drives/database/eloquent/nesbot/carbon/src/Carbon',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6a6d632d67e4f39705df4f1767d24dc8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6a6d632d67e4f39705df4f1767d24dc8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
