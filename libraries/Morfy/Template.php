<?php

/**
 * This file is part of the Morfy.
 *
 * (c) Romanenko Sergey / Awilum <awilum@msn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Template
{
    /**
     * An instance of the Template class
     *
     * @var object
     * @access  protected
     */
    protected static $instance = null;

    /**
     * Fenom object
     *
     * @var object
     * @access  protected
     */
    protected static $fenom = null;

    /**
     * Constructor.
     *
     * @access  protected
     */
    protected function __construct()
    {
        // Create fenom cache directory if its not exists
        !Dir::exists(CACHE_PATH . '/fenom/') and Dir::create(CACHE_PATH . '/fenom/');

        // Create Unique Cache ID for Theme
        $theme_config_file = THEMES_PATH . '/' . Config::get('system.theme') . '/' . Config::get('system.theme') . '.yml';
        $theme_cache_id = md5('theme' . ROOT_DIR . $theme_config_file . filemtime($theme_config_file));

        // Set current them options
        if (Cache::driver()->contains($theme_cache_id)) {
            Config::set('theme', Cache::driver()->fetch($theme_cache_id));
        } else {
            $theme_config = Yaml::parseFile($theme_config_file);
            Config::set('theme', $theme_config);
            Cache::driver()->save($theme_cache_id, $theme_config);
        }

        // Create Fenom object
        $fenom = FenomExtended::factory(THEMES_PATH . '/' . Config::get('system.theme') . '/',
                                CACHE_PATH . '/fenom/',
                                Config::get('system.fenom'));

        // Add {$.config} for templates
        $fenom->addAccessorSmart('config', 'config', Fenom::ACCESSOR_PROPERTY);
        $fenom->config = Config::getConfig();

        static::$fenom = $fenom;
    }

    /**
     * Get Fenom Object
     *
     *  <code>
     *      Template::fenom()->display('template.tpl');
     *  </code>
     *
     * @access  public
     * @return object
     */
    public static function fenom()
    {
        return static::$fenom;
    }

    /**
     * Initialize Morfy Template
     *
     *  <code>
     *      Template::init();
     *  </code>
     *
     * @access  public
     */
    public static function init()
    {
        return !isset(self::$instance) and self::$instance = new Template();
    }
}

class FenomExtended extends \Fenom
{
    use \Fenom\StorageTrait;
}
