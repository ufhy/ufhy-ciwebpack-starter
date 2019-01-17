<?php

echo Asset::render_js('vue');

if (ENVIRONMENT === 'development') {
    echo Asset::render_js('webpack-dev-server');
    echo Asset::render_js('webpack-profiler');
}

echo Asset::render_js('webpack-bundle');
echo Asset::render_js('webpack-page');