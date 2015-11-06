<?php

/**
 * This file is part of the Morfy.
 *
 * (c) Romanenko Sergey / Awilum <awilum@msn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Config
{
    /**
     * An instance of the Plugins class
     *
     * @var object
     * @access  protected
     */
    protected static $instance = null;

    /**
     * Config
     *
     * @var array
     * @access  protected
     */
    protected static $config = array();

    /**
     * Config Paths
     *
     * @var array
     * @access  protected
     */
    protected static $config_paths = array('config', 'themes', 'plugins');

    /**
     * Protected clone method to enforce singleton behavior.
     *
     * @access  protected
     */
    protected function __clone()
    {
        // Nothing here.
    }

    /**
     * Constructor.
     *
     * @access  protected
     */
    protected function __construct()
    {
        foreach (static::$config_paths as $config_path) {
            $config_list[$config_path] = File::scan(ROOT_DIR . '/' . $config_path, 'yml');
        }

        foreach ($config_list['config'] as $config) {
            static::$config[File::name($config)] = Spyc::YAMLLoad(file_get_contents($config));
        }

        foreach ($config_list['plugins'] as $config) {
            static::$config['plugins'][File::name($config)] = Spyc::YAMLLoad(file_get_contents($config));
        }

        foreach ($config_list['themes'] as $config) {
            if (File::name($config) == static::$config['system']['theme']) {
                static::$config['theme'] = Spyc::YAMLLoad(file_get_contents($config));
            }
        }
    }

    /**
     * Set new or update existing config variable
     *
     *  <code>
     *      Config::set('site.title', 'value');
     *  </code>
     *
     * @access public
     * @param string $key   Key
     * @param mixed  $value Value
     */
    public static function set($key, $value)
    {
        Arr::set(static::$config, $key, $value);
    }

    /**
     * Get config variable
     *
     *  <code>
     *      Config::get('site');
     *      Config::get('site.title');
     *  </code>
     *
     * @access  public
     * @param  string $key Key
     * @return mixed
     */
    public static function get($key)
    {
        return Arr::get(static::$config, $key);
    }

    /**
     * Get config array
     *
     *  <code>
     *      $config = Config::getConfig();
     *  </code>
     *
     * @access  public
     * @return array
     */
    public static function getConfig()
    {
        return static::$config;
    }

    /**
     * Initialize Morfy Config
     *
     *  <code>
     *      Config::init();
     *  </code>
     *
     * @access  public
     */
    public static function init()
    {
        if (! isset(self::$instance)) {
            self::$instance = new Config();
        }
        return self::$instance;
    }
}
