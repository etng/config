<?php

namespace Encore\Admin\Config;

use Illuminate\Database\Eloquent\Model;

class ConfigModel extends Model
{
    public static $_data_types = [];
    public static function getDataTypes(){
        if(!self::$_data_types){
            self::$_data_types = collect(['text', 'int', 'float', 'lines', 'json', 'kvlines'])->mapWithKeys(function($x){
                return [$x => trans('config.data_type.' . $x)];
            })->toArray();
        }
        return self::$_data_types;
    }
    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable(config('admin.extensions.config.table', 'admin_config'));
    }
}
