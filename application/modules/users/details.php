<?php

class Module_Users {

    public $version = '1.1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Users',
            ),
            'icon' => 'la la-group',
            'menu' => 'users',
            'menu_group' => 'navigation',
            'sections' => array(
                'user' => array(
                    'name' => 'menu::user',
                    'uri' => 'users/user',
                    'roles' => [
                        'read','create', 'edit', 'remove','change_password'
                    ],
                    'event' => [
                        'users::user:created','users::user:edited','users::user:removed'
                    ]
                ),
                'group' => array(
                    'name' => 'menu::group',
                    'uri' => 'users/group',
                    'roles' => [
                        'read','create', 'edit', 'remove', 'change_permission'
                    ],
                    'events' => [
                        'users::group:permission_changed','users::group:created',
                        'users::group:edited','users::group:removed'
                    ]
                ),
            )
        );
    }

    public function admin_menu(&$menu, $current_state)
    {
        $info = $this->info();
        unset($menu['menu::group:'.$info['menu_group']]['menu::'.$info['menu']]);

        if (userHasModule('users'))
        {
            if (isset($info['sections']) && is_array($info['sections']))
            {
                $submenus = [];
                foreach ($info['sections'] as $key => $item)
                {
                    if (userHasModuleSection('users', $key)) {
                        $submenus[$item['name']] = new stdClass();
                        $submenus[$item['name']]->url = $item['uri'];
                        $submenus[$item['name']]->active = $key === $current_state;
                    }
                }

                $menu['menu::group:' . $info['menu_group']]['menu::' . $info['menu']]['icon'] = $info['icon'];
                $menu['menu::group:' . $info['menu_group']]['menu::' . $info['menu']]['items'] = $submenus;
            }
        }
    }

}