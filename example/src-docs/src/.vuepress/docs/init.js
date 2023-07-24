function init() {
	// here we can initialize some values from docs.config.json e.g. set some env variables

	let apis = {};
	try {
		apis = require('../api.json');
	}
	catch (error) {
		// ignore this error, should only appear if api.json doesn't exist.
	}

	return {
		apis,
		config: require('../../../docs.config.json'),
	};
}

export {
	init as default,
};