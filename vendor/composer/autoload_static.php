<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitccc00d822d265c46e3cb9baccc6094fe
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

    public static $classMap = array (
        'PHPMailer\\PHPMailer\\Exception' => __DIR__ . '/..' . '/phpmailer/phpmailer/src/Exception.php',
        'PHPMailer\\PHPMailer\\OAuth' => __DIR__ . '/..' . '/phpmailer/phpmailer/src/OAuth.php',
        'PHPMailer\\PHPMailer\\PHPMailer' => __DIR__ . '/..' . '/phpmailer/phpmailer/src/PHPMailer.php',
        'PHPMailer\\PHPMailer\\POP3' => __DIR__ . '/..' . '/phpmailer/phpmailer/src/POP3.php',
        'PHPMailer\\PHPMailer\\SMTP' => __DIR__ . '/..' . '/phpmailer/phpmailer/src/SMTP.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitccc00d822d265c46e3cb9baccc6094fe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitccc00d822d265c46e3cb9baccc6094fe::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitccc00d822d265c46e3cb9baccc6094fe::$classMap;

        }, null, ClassLoader::class);
    }
}