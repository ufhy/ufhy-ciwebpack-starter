const webpack = require('webpack');
const path = require('path');
const glob = require('glob');
const merge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const common = require('./webpack.common');
const entryFiles = require('./helpers/entryFiles');

const config = merge(common, {
  devtool: "source-map",
  mode: 'production',
  entry: entryFiles(false),
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