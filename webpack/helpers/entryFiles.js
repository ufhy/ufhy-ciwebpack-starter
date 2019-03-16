const path = require('path');
const glob = require('glob');
const splitString = require('./splitString');

const appPath = path.join(__dirname, '../../application');
const pathEntries = [
	path.resolve(appPath, 'themes/vuetify-theme/assets/js/*.js'),
	path.resolve(appPath, 'themes/auth-theme/assets/js/*.js'),
];

module.exports = function(isDev) {
	let bundleFiles = {
		'profiler': path.resolve(appPath, 'views/profiler/profiler.js'),
		'vuetify': path.resolve(__dirname, '../../resources/vuetify/loaders.js'),
		'line-awesome': path.resolve(__dirname, '../../resources/line-awesome/loaders.js'),
	};
	if (isDev) {
		bundleFiles = {
			...bundleFiles,
			'webpack-dev-server': 'webpack-dev-server/client?http://0.0.0.0:9000',
			'only-dev-server': 'webpack/hot/only-dev-server',
		}
	}

	pathEntries.forEach((path) => {
		const globpaths = glob.sync(path);
		const parentdir = 'js';
		const ext = 'js';
		globpaths.forEach((path) => {
			const key = splitString(path, `/${parentdir}/`).slice(-1)[0].replace(`.${ext}`, '');
			bundleFiles[key] = path;
		});
	});

	return bundleFiles;
}