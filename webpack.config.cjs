const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );
const path = require( 'path' );

const blockEditor = [ 'editDefaultBlocks' ];

module.exports = {
	...defaultConfig,
	...{
		resolve: {
			...defaultConfig.resolve,
			alias: {
				'@': path.resolve( __dirname, `./src/js` ),
				'@styles': path.resolve( __dirname, `./src/styles` ),
			},
		},
		entry: () => ( {
			...defaultConfig.entry(),
			global: `./src/index.ts`,
			'admin/editor': `./src/styles/editor.scss`,
			...addEntries( blockEditor, 'admin' ),
		} ),

		output: {
			path: __dirname + `./build`,
			filename: `[name].js`,
		},
	},
};

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
				`./src/styles/pages/${ asset }.scss`,
		},
		pages: {
			outputDir: ( assetOutput ) => `pages/${ assetOutput }`,
			path: ( asset ) => `./src/js/${ asset }/index.ts`,
		},
		admin: {
			outputDir: ( assetOutput ) => `admin/${ assetOutput }`,
			path: ( asset ) => `./src/js/gutenberg/${ asset }.ts`,
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
