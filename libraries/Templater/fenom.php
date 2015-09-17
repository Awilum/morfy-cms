<?php
/**
 * Fenom template Engine Integration
 *
 *  @package Morfy
 *  @subpackage Templater
 *  @author Pavel Belousov / pafnuty
 *  @version 1.0.0
 *
 */

include LIBRARIES_PATH . '/Templater/fenom-templater/Fenom.php';

\Fenom::registerAutoload();
/**
 * @see https://github.com/fenom-template/fenom/blob/master/docs/en/configuration.md
 * @var array
 */
$tplOptions = array(
    'disable_methods'      => false,
    'disable_native_funcs' => false,
    'auto_reload'          => true,
    'force_compile'        => false,
    'disable_cache'        => false,
    'force_include'        => true,
    'auto_escape'          => false,
    'force_verify'         => false,
    'disable_php_calls'    => false,
    'disable_statics'      => false,
    'strip'                => true,
);

$template = (!empty($page['template'])) ? $page['template'] : 'index';

$fenom = Fenom::factory(
    THEMES_PATH . '/' . $config['site_theme'] . '/',
    ROOT_DIR . '/cache/templates/',
    $tplOptions
);

// Do global tag {$.config} for the template
$fenom->addAccessorSmart('config', 'site_config', Fenom::ACCESSOR_PROPERTY);
$fenom->site_config = $config;

// Display page
$fenom->display($template . '.tpl', $page);
