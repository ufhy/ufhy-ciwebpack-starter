const webpack = require('webpack');
const path = require('path');
const glob = require('glob');
const merge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const common = require('./webpack.common');
const splitString = require('./helpers/splitString');

const appPath = path.join(__dirname, '../application');
const pathEntries = [
  path.resolve(appPath,'themes/**/assets/js/*.js')
];

const entryFiles = {
  'webpack-dev-server': 'webpack-dev-server/client?http://localhost:9000',
  'only-dev-server': 'webpack/hot/only-dev-server',
  'profiler': path.resolve(appPath, 'views/profiler/profiler.js'),
};

pathEntries.forEach((path) => {
  const globpaths = glob.sync(path);
  const parentdir  = 'js';
  const ext  = 'js';
  globpaths.forEach((path) => {
    const key = splitString(path, `/${parentdir}/`).slice(-1)[0].replace(`.${ext}`, '');
    entryFiles[key] = path;
  });
});

const config = merge(common, {
  mode: 'development',
  devtool: "cheap-module-eval-source-map",
  entry: entryFiles,
  output: {
    filename: '[name].js',
    chunkFilename: '[name].[hash].js',
    path: '/public/dist/',
    publicPath: 'http://localhost:9000/dist/'
  },
  devServer: {
    contentBase: path.join(__dirname, 'public'),
    port: 9000,
    headers: { "Access-Control-Allow-Origin": "*" },
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin(),
    new MiniCssExtractPlugin({
      filename: "[name].css",
      chunkFilename: "[id].[hash].css"
    }),
    new webpack.DefinePlugin({
      "ENV": JSON.stringify("development")
    }),
    new webpack.SourceMapDevToolPlugin({
      filename: "[file].map",
      exclude: ["/vendor/"]
    })
  ]
});

module.exports = config;