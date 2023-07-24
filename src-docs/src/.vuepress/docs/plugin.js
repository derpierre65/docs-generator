module.exports = (options, ctx) => {
	console.debug('plugin loaded', options, ctx);

	return {
		name: 'derpierre65/docs-generator',
		extendsPage: (page) => {
			page.data.$dg = options;
		},
	};
};