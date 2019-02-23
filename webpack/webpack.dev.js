const webpack = require('webpack');
const path = require('path');
const glob = require('glob');
const merge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const common = require('./webpack.common');
const entryFiles = require('./helpers/entryFiles');

const config = merge(common, {
  mode: 'development',
  devtool: "cheap-module-eval-source-map",
  entry: entryFiles(true),
  output: {
    filename: '[name].js',
    chunkFilename: '[name].[hash].js',
    path: '/public/dist/',
    publicPath: 'http://localhost:9000/dist/'
  },
  devServer: {
    contentBase: path.join(__dirname, 'public'),
    disableHostCheck: true,
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