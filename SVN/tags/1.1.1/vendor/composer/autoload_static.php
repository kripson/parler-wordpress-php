<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba20d35a43b818aa9484ac23f5003954
{
    public static $classMap = array (
        'Parler_Api_Service' => __DIR__ . '/../..' . '/parler/class-parler-api-service.php',
        'Parler_Async_Request' => __DIR__ . '/../..' . '/parler/class-parler-async-request.php',
        'Parler_Background_Process' => __DIR__ . '/../..' . '/parler/class-parler-background-process.php',
        'Parler_For_WordPress' => __DIR__ . '/../..' . '/parler/class-parler-for-wordpress.php',
        'Parler_For_WordPress_Activator' => __DIR__ . '/../..' . '/parler/class-parler-for-wordpress-activator.php',
        'Parler_For_WordPress_Deactivator' => __DIR__ . '/../..' . '/parler/class-parler-for-wordpress-deactivator.php',
        'Parler_For_WordPress_I18n' => __DIR__ . '/../..' . '/parler/class-parler-for-wordpress-i18n.php',
        'Parler_For_WordPress_Loader' => __DIR__ . '/../..' . '/parler/class-parler-for-wordpress-loader.php',
        'Parler_For_WordPress_Widget' => __DIR__ . '/../..' . '/parler/class-parler-for-wordpress-widget.php',
        'Parler_Import_Posts_Process' => __DIR__ . '/../..' . '/parler/class-parler-import-posts-process.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitba20d35a43b818aa9484ac23f5003954::$classMap;

        }, null, ClassLoader::class);
    }
}
