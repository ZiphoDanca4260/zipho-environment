<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit070873495fd11e67d89dbe6aaf9864fc
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WhichBrowser\\' => 13,
        ),
        'P' => 
        array (
            'Psr\\Cache\\' => 10,
        ),
        'M' => 
        array (
            'MatthiasMullie\\PathConverter\\' => 29,
            'MatthiasMullie\\Minify\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WhichBrowser\\' => 
        array (
            0 => __DIR__ . '/..' . '/whichbrowser/parser/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'MatthiasMullie\\PathConverter\\' => 
        array (
            0 => __DIR__ . '/..' . '/matthiasmullie/path-converter/src',
        ),
        'MatthiasMullie\\Minify\\' => 
        array (
            0 => __DIR__ . '/..' . '/matthiasmullie/minify/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit070873495fd11e67d89dbe6aaf9864fc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit070873495fd11e67d89dbe6aaf9864fc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit070873495fd11e67d89dbe6aaf9864fc::$classMap;

        }, null, ClassLoader::class);
    }
}