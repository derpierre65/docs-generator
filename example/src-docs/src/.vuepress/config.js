import {registerComponentsPlugin} from '@vuepress/plugin-register-components';
import {searchPlugin} from '@vuepress/plugin-search';
import {defaultTheme} from '@vuepress/theme-default';
import autoprefixer from 'autoprefixer';
import path from 'path';
import tailwindcss from 'tailwindcss';
import {defineUserConfig} from 'vuepress';
import docsGenerator from './docs/plugin';
import init from './docs/init';

const docsGeneratorPluginOptions = init();

export default defineUserConfig({
	lang: 'en-US',
	title: 'Docs Generator',
	description: 'Docs Generator Example',

	theme: defaultTheme({
		// lastUpdated: true,
		// editLink: true,
		// editLinkText: 'Edit me',
		// repo: 'derpierre65/docs-generator',
		// editLinkPattern: ':repo/edit/:branch/:path',
		navbar: [
			{text: 'Kraken', link: '/kraken/'},
			{text: 'Helix', link: '/helix/'},
			{text: 'Real API', link: '/real-api/'},
		],
		sidebar: {
			/*'/resources/my-api-version': [
				{
					text: 'Kraken Resources',
					children: [
						//%RESOURCES_API_VERSION%
						//%RESOURCES_KRAKEN%
					],
				},
			],*/
		},
	}),

	plugins: [
		searchPlugin({}),
		registerComponentsPlugin({
			componentsDir: path.resolve(__dirname, './components'),
		}),
		docsGenerator(docsGeneratorPluginOptions),
	],
	bundlerConfig: {
		viteOptions: {
			css: {
				postcss: {
					plugins: [
						tailwindcss,
						autoprefixer,
					],
				},
			},
		},
	},
});