import path from 'path';
import { fileURLToPath } from 'url';
import config from '@wordpress/scripts/config/webpack.config.js';

const __filename = fileURLToPath( import.meta.url );
const __dirname = path.dirname( __filename );
const configs = Array.isArray( config ) ? config : [ config ];

const THEME_ROOT = path.resolve( __dirname );
const THEME_SRC = path.resolve( THEME_ROOT, 'src' );
const THEME_DIST = path.resolve( THEME_ROOT, 'dist' );

/**
 * Array of strings modeled after folder names (e.g. 'about-choctaw'). Inside of these folders, an `index.ts` file is expected. If that's not what you want, consider editing the `addEntries` function below.
 *
 * **Be sure to import page scss in these files**
 */
const appNames = [];
const modules = []; // for global js files
const blockEditor = [ 'editDefaultBlocks' ];

/**
 * For SCSS files (no leading `_`)
 * Array of strings modeled after scss names (e.g. 'we-are-choctaw')
 */
const styleSheets = []; // for scss only

const addAlias = ( webpackConfig ) => ( {
	...webpackConfig,
	resolve: {
		...webpackConfig.resolve,
		alias: {
			...webpackConfig.resolve?.alias,
			'~': THEME_SRC,
			'@styles': path.resolve( THEME_SRC, 'styles' ),
		},
	},
} );
const addEditorEntry = ( webpackConfig ) => {
	const isModuleBuild = webpackConfig.output?.module;

	const updatedConfig = {
		...webpackConfig,
		entry: {
			...webpackConfig.entry,
		},
		output: {
			...webpackConfig.output,
			path: THEME_DIST,
			filename: '[name].js',
		},
	};

	if ( isModuleBuild ) {
		return updatedConfig;
	}

	return {
		...updatedConfig,
		entry: async () => {
			return {
				global: path.resolve( THEME_SRC, 'styles/main.scss' ),
				'admin/editor': path.resolve( THEME_SRC, 'styles/editor.scss' ),
				...addEntries( appNames, 'pages' ),
				...addEntries( modules, 'modules' ),
				...addEntries( styleSheets, 'styles' ),
				...addEntries( blockEditor, 'admin' ),
			};
		},
	};
};

export default configs.map( addAlias ).map( addEditorEntry );

/**
 * Helper function to add entries to the entries object. It takes an array of strings in either kebab-case or snake_case and returns an object with the key as the entry name and the value as the path to the entry file.
 * @param {Array}  array - Array of strings
 * @param {string} type  - The type of entry. Either 'pages' or 'styles'
 */
function addEntries( array, type ) {
	if ( ! Array.isArray( array ) ) {
		throw new Error( `Expecting an array, received ${ typeof array }!` );
	}
	if ( 0 >= array.length ) {
		return {};
	}
	const entries = {};
	const typeOutput = {
		styles: {
			outputDir: ( assetOutput ) => `pages/${ assetOutput }`,
			path: ( asset ) =>
				path.resolve( THEME_SRC, `styles/pages/${ asset }.scss` ),
		},
		pages: {
			outputDir: ( assetOutput ) => `pages/${ assetOutput }`,
			path: ( asset ) =>
				path.resolve( THEME_SRC, `js/${ asset }/index.ts` ),
		},
		admin: {
			outputDir: ( assetOutput ) => `admin/${ assetOutput }`,
			path: ( asset ) =>
				path.resolve( THEME_SRC, `js/gutenberg/${ asset }.ts` ),
		},
		modules: {
			outputDir: ( assetOutput ) => `modules/${ assetOutput }`,
			path: ( asset ) =>
				path.resolve( THEME_SRC, `js/modules/${ asset }.js` ),
		},
	};
	array.forEach( ( asset ) => {
		const assetOutput = snakeToCamel( asset );

		if ( Object.hasOwn( typeOutput, type ) ) {
			const output = typeOutput[ type ];
			entries[ output.outputDir( assetOutput ) ] = output.path( asset );
		} else {
			throw new Error(
				`Invalid type! Expected one of ${ Object.keys(
					typeOutput
				).join( ', ' ) }, received "${ type }"`
			);
		}
	} );
	return entries;
}

/**
 * A simple utility class to alter strings from kebab-case or snake_case to camelCase
 *
 * @param {string} str - The string to be converted
 */
function snakeToCamel( str ) {
	return str.replace( /([-_][a-z])/g, ( group ) =>
		group.toUpperCase().replace( '-', '' ).replace( '_', '' )
	);
}
