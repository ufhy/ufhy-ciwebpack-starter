<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Auth_theme
{
    public function init()
    {
        if (ENVIRONMENT === 'development')
        {
            Asset::js('core::vue.js', true, 'vue');

            Asset::add_path('webpack', [
                'path' => 'http://localhost:9000',
                'js_dir' => '/',
                'css_dir' => '/',
                'img_dir' => '/'
            ]);

            Asset::js('webpack::webpack-dev-server.js', true, 'webpack-dev-server');
            Asset::js('webpack::dist/only-dev-server.js', true, 'webpack-dev-server');
            Asset::js('webpack::dist/webpack-dev-server.js', true, 'webpack-server');
            Asset::js('webpack::dist/profiler.js', true, 'webpack-profiler');
            Asset::css('webpack::dist/profiler.css', true, 'webpack-profiler');
        }
        else {
            Asset::add_path('webpack', [
                'path' => '',
                'js_dir' => '',
                'css_dir' => '',
                'img_dir' => ''
            ]);

            Asset::js('core::vue.min.js', true, 'vue');
        }

        Asset::js('webpack::dist/vuetify.js', true, 'webpack-vendors');
        Asset::css('webpack::dist/vuetify.css', true, 'webpack-vendors');
        Asset::css('webpack::dist/line-awesome.css', true, 'webpack-vendors');

        $scriptMeta = [
            sprintf('window.API_URL="%s";', site_url('api')),
            sprintf('window.SITE_URL="%s";', site_url()),
            sprintf('window.BASE_URL="%s";', base_url()),
            sprintf('window.SITE_TITLE_FULL="%s";', ci()->template->siteNameFull),
            sprintf('window.SITE_TITLE_ABBR="%s";', ci()->template->siteNameAbbr)
        ];
        ci()->template->append_metadata(
            sprintf(
                '<script>%s</script>', implode(' ', $scriptMeta)
            )
        );

        Asset::js('webpack::dist/auth-theme.js', true, 'webpack-bundle');
        Asset::css('webpack::dist/auth-theme.css', true, 'webpack-bundle');
    }
}