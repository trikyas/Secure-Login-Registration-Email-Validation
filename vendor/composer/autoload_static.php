<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitce054e6b8b34bd0a0ec7f38a3f22ed4e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitce054e6b8b34bd0a0ec7f38a3f22ed4e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitce054e6b8b34bd0a0ec7f38a3f22ed4e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
