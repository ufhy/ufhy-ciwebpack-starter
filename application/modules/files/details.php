<?php

class Module_Files {

    public $version = '1.1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Files',
            ),
            'icon' => 'la la-file',
            'menu' => 'files',
            'menu_group' => 'navigation',
            'roles' => array(
                'read',
                'create_file','edit_file','remove_file',
                'create_folder','edit_folder','remove_folder'
            ),
            'events' => [
                'files::folder_created'
            ]
        );
    }

    public function admin_menu(&$menu, $current_state)
    {
        $info = $this->info();
        unset($menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]);
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']] = new stdClass();
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]->icon = $info['icon'];
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]->url = 'files';
        $menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]->active = 'files' === $current_state;
    }

}