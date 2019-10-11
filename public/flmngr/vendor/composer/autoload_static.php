<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfe8a0c704780527a7fca5237182feb4c
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EdSDK\\FlmngrServer\\' => 19,
            'EdSDK\\FileUploaderServer\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EdSDK\\FlmngrServer\\' => 
        array (
            0 => __DIR__ . '/..' . '/edsdk/flmngr-server-php/src',
        ),
        'EdSDK\\FileUploaderServer\\' => 
        array (
            0 => __DIR__ . '/..' . '/edsdk/file-uploader-server-php/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfe8a0c704780527a7fca5237182feb4c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfe8a0c704780527a7fca5237182feb4c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
