<?php

class Module_Dashboard {

    public $version = '1.1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Dashboard',
            ),
            'menu' => 'dashboard',
            'menu_group' => 'navigation',
            'icon' => 'ms-Icon ms-Icon--boards2',
            'roles' => array(),
            'shortcuts' => array()
        );
    }

    public function admin_menu(&$menu, $current_state)
    {
        $info = $this->info();
        unset($menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]);
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']] = new stdClass();
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]->icon = $info['icon'];
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]->url = 'dashboard';
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]->active = 'dashboard' === $current_state;
    }
}