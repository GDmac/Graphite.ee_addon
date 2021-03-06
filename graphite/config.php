<?php

/**
 * Graphite Config File *
 * @package         graphite_ee_addon
 * @version         2.1
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/graphite
 * @copyright       Copyright (c) 2012, Joel 
 */

if ( ! defined('GRAPHITE_NAME'))
{
	define('GRAPHITE_NAME',         'Graphite');
	define('GRAPHITE_CLASS_NAME',   'Graphite');
	define('GRAPHITE_VERSION',      '2.3');
	define('GRAPHITE_DOCS',         'http://squarebit.co.uk/graphite');

}

$config['name']    = GRAPHITE_NAME;
$config['version'] = GRAPHITE_VERSION;
$config['nsm_addon_updater']['versions_xml']='http://squarebit.co.uk/versions/graphite.xml';
