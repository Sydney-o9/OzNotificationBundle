<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$vendorDir = __DIR__.'/../../../../';
require_once $vendorDir.'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                => array($vendorDir.'/symfony/src', $vendorDir.'/bundles'),
    'Doctrine\\Common'       => $vendorDir.'/doctrine-common/lib',
    'Doctrine\\DBAL'         => $vendorDir.'/doctrine-dbal/lib',
    'Doctrine\\ODM\\MongoDB' => $vendorDir.'/doctrine-mongodb-odm/lib',
    'Doctrine\\MongoDB'      => $vendorDir.'/doctrine-mongodb/lib',
    'Doctrine'               => $vendorDir.'/doctrine/lib',
));
$loader->register();

set_include_path($vendorDir.'/phing/classes'.PATH_SEPARATOR.get_include_path());

spl_autoload_register(function($class) {
    if (0 === strpos($class, 'Oz\\NotificationBundle\\')) {
        $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $class), 2)).'.php';
        if (!stream_resolve_include_path($path)) {
            return false;
        }
        require_once $path;
        return true;
    }
});