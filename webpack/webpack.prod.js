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
  // 'webpack-dev-server': 'webpack-dev-server/client?http://localhost:9000',
  // 'only-dev-server': 'webpack/hot/only-dev-server',
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
  devtool: "source-map",
  mode: 'production',
  entry: entryFiles,
  output: {
    filename: '[name][hash].js',
    path: path.resolve(__dirname, '../public/dist'),
    chunkFilename: '[name]-[chunkhash].js',
    publicPath: '/dist/'
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "[name].css",
      chunkFilename: "[id][hash].css"
    }),
    new webpack.DefinePlugin({
      "ENV": JSON.stringify("production")
    }),
  ]
});

module.exports = config;