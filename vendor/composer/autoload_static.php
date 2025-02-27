<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9f617e735f4816eff267c021ffc7f25f
{
    public static $files = array (
        '3773ef3f09c37da5478d578e32b03a4b' => __DIR__ . '/..' . '/automattic/jetpack-assets/actions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TextingOnly\\' => 12,
        ),
        'A' => 
        array (
            'Automattic\\Jetpack\\Autoloader\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TextingOnly\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
        'Automattic\\Jetpack\\Autoloader\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/jetpack-autoloader/src',
        ),
    );

    public static $classMap = array (
        'Automattic\\Jetpack\\A8c_Mc_Stats' => __DIR__ . '/..' . '/automattic/jetpack-a8c-mc-stats/src/class-a8c-mc-stats.php',
        'Automattic\\Jetpack\\Assets' => __DIR__ . '/..' . '/automattic/jetpack-assets/src/class-assets.php',
        'Automattic\\Jetpack\\Assets\\Semver' => __DIR__ . '/..' . '/automattic/jetpack-assets/src/class-semver.php',
        'Automattic\\Jetpack\\Autoloader\\AutoloadGenerator' => __DIR__ . '/..' . '/automattic/jetpack-autoloader/src/AutoloadGenerator.php',
        'Automattic\\Jetpack\\Automatic_Install_Skin' => __DIR__ . '/..' . '/automattic/jetpack-plugins-installer/src/class-automatic-install-skin.php',
        'Automattic\\Jetpack\\Constants' => __DIR__ . '/..' . '/automattic/jetpack-constants/src/class-constants.php',
        'Automattic\\Jetpack\\Plugins_Installer' => __DIR__ . '/..' . '/automattic/jetpack-plugins-installer/src/class-plugins-installer.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9f617e735f4816eff267c021ffc7f25f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9f617e735f4816eff267c021ffc7f25f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9f617e735f4816eff267c021ffc7f25f::$classMap;

        }, null, ClassLoader::class);
    }
}
