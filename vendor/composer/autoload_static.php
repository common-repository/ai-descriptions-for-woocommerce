<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc1a7ba2d9e16105b0c0eb57f2dcdb073
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Orhanerday\\OpenAi\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Orhanerday\\OpenAi\\' => 
        array (
            0 => __DIR__ . '/..' . '/orhanerday/open-ai/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 
            array (
                0 => __DIR__ . '/..' . '/composer/installers/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc1a7ba2d9e16105b0c0eb57f2dcdb073::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc1a7ba2d9e16105b0c0eb57f2dcdb073::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitc1a7ba2d9e16105b0c0eb57f2dcdb073::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitc1a7ba2d9e16105b0c0eb57f2dcdb073::$classMap;

        }, null, ClassLoader::class);
    }
}
