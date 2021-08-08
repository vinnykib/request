<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1638773a21466ccf19b70673069ee272
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Includes\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Includes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1638773a21466ccf19b70673069ee272::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1638773a21466ccf19b70673069ee272::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1638773a21466ccf19b70673069ee272::$classMap;

        }, null, ClassLoader::class);
    }
}
