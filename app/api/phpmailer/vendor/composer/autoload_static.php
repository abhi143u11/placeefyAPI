<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc401ebad3227c0f330ddf03256abc53a
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitc401ebad3227c0f330ddf03256abc53a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc401ebad3227c0f330ddf03256abc53a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
