<?php

namespace Encore\Admin\Config;

use Encore\Admin\Admin;
use Encore\Admin\Extension;

class Config extends Extension
{
    /**
     * Load configure into laravel from database.
     *
     * @return void
     */
    public static function load()
    {
        foreach (ConfigModel::all(['name', 'value', 'data_type']) as $config) {
            if($config['data_type']=='int'){
                $config['value'] = intval($config['value']);
            }elseif($config['data_type']=='float'){
                $config['value'] = floatval($config['value']);
            }elseif($config['data_type']=='json'){
                $config['value'] = json_decode($config['value']);
            }elseif($config['data_type']=='lines'){
                $config['value'] = preg_split('!\r\n+!', $config['value'], 0, PREG_SPLIT_NO_EMPTY);
            }
            elseif($config['data_type']=='kvlines'){
                $config['value'] = collect(preg_split('!\r\n+!', $config['value'], 0, PREG_SPLIT_NO_EMPTY))->mapWithKeys(function($x){
                    list($k, $v) = explode(':', $x, 2);
                    return [$k => $v];
                })->toArray();
            }
            config([$config['name'] => $config['value']]);
        }
    }

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('config', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->resource('config', 'Encore\Admin\Config\ConfigController');
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Config', 'config', 'fa-toggle-on');

        parent::createPermission('Admin Config', 'ext.config', 'config*');
    }
}
