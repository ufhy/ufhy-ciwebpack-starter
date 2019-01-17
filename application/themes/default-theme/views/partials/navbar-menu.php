<?php

if (isset($menu_items['menu::group:navigation']))
{
    $sidemenu = '';
    foreach ($menu_items['menu::group:navigation'] as $key_item => $item)
    {
        if (is_object($item))
        {
            $sidemenu .= sprintf(
                '<b-nav-item to="/%s">%s</b-nav-item>',
                $item->url === '/' ? '' : $item->url, lang($key_item)
            );
        }
        else if (is_array($item) && isset($item['items'])) {
            $activeSub = false;
            $subs = [];
            foreach ($item['items'] as $key_sub => $sub) {
                if ($sub->active) {
                    $activeSub = true;
                }
                array_push(
                    $subs,
                    sprintf(
                        '<b-dropdown-item to="/%s">%s</b-dropdown-item>',
                        $sub->url, lang($key_sub)
                    )
                );
            }
            $sidemenu .= sprintf('<b-nav-item-dropdown text="%s">', lang($key_item));
            $sidemenu .= implode(' ', $subs);
            $sidemenu .= '</b-nav-item-dropdown>';
        }
    }

    echo $sidemenu;
}