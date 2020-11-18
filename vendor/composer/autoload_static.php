<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit07a432bb33836826f90f81925b89c43c
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AppleSignIn\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AppleSignIn\\' => 
        array (
            0 => __DIR__ . '/..' . '/wubuwei/php-apple-signin',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit07a432bb33836826f90f81925b89c43c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit07a432bb33836826f90f81925b89c43c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}