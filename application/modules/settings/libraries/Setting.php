<?php

class Setting
{
    public function __construct()
    {
        ci()->load->model('settings/setting_model');
    }

    public static function get($name, $module = null)
    {
        if (!is_null($module)) {
            ci()->setting_model->where(['module' => $module]);
        }

        $setting = ci()->setting_model
            ->set_cache('get_'.$module.'_'.$name)
            ->get(array('slug' => $name));

        if ($setting->type == 'checkbox') {
            $value = !$setting
                ? config_item($name)
                : $setting->value
                    ? explode(',', $setting->value)
                    : explode(',', $setting->default) ;
        }
        else {
            $value = !$setting
                ? config_item($name)
                : $setting->value
                    ? $setting->value
                    : $setting->default ;
        }

        return $value;
    }

    public static function saveChange($slug, $value)
    {
        $setting = ci()->setting_model->get(['slug' => $slug]);
        if (!$setting) {
            return false;
        }

        $update = ci()->setting_model->update(
            ['value' => $value], ['slug' => $slug]
        );

        return $update;
    }
}