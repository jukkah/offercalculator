<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Zend\Filter;

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class StaticFilter
{
    /**
     * @var FilterPluginManager
     */
    protected static $plugins;

    /**
     * Set plugin manager for resolving filter classes
     * 
     * @param  FilterPluginManager $manager 
     * @return void
     */
    public static function setPluginManager(FilterPluginManager $manager = null)
    {
        // Don't share by default to allow different arguments on subsequent calls
        if ($manager instanceof FilterPluginManager) {
            $manager->setShareByDefault(false);
        }
        self::$plugins = $manager;
    }

    /**
     * Get plugin manager for loading filter classes
     * 
     * @return FilterPluginManager
     */
    public static function getPluginManager()
    {
        if (null === self::$plugins) {
            static::setPluginManager(new FilterPluginManager());
        }
        return self::$plugins;
    }

    /**
     * Returns a value filtered through a specified filter class, without requiring separate
     * instantiation of the filter object.
     *
     * The first argument of this method is a data input value, that you would have filtered.
     * The second argument is a string, which corresponds to the basename of the filter class,
     * relative to the Zend_Filter namespace. This method automatically loads the class,
     * creates an instance, and applies the filter() method to the data input. You can also pass
     * an array of constructor arguments, if they are needed for the filter class.
     *
     * @param  mixed        $value
     * @param  string       $classBaseName
     * @param  array        $args          OPTIONAL
     * @return mixed
     * @throws Exception\ExceptionInterface
     */
    public static function execute($value, $classBaseName, array $args = array())
    {
        $plugins = static::getPluginManager();

        $filter = $plugins->get($classBaseName, $args);
        return $filter->filter($value);
    }
}