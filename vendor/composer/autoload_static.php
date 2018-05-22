<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit34e0b56c8bb6cd785845c84bd6890548
{
    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
    );

    public static $classMap = array (
        'PiramideUploader' => __DIR__ . '/../..' . '/piramide-uploader/PiramideUploader.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit34e0b56c8bb6cd785845c84bd6890548::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit34e0b56c8bb6cd785845c84bd6890548::$classMap;

        }, null, ClassLoader::class);
    }
}
