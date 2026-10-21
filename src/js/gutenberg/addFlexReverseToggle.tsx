/* eslint-disable */
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, PanelBody } from '@wordpress/components';

/**
 * Adds a toggle control to reverse the flex direction of a block.
 *
 * @param namespace the namespace of the callback functions to run
 */
export default function addFlexReverseToggle( namespace: string ) {
	try {
		new FlexReverse( namespace );
	} catch ( error ) {
		 
		console.error( `Error initializing RowReverse:`, error );
	}
}

class FlexReverse {
	/**
	 * The namespace of the filters
	 */
	namespace: string;
	private canReverse = [ 'core/columns', 'core/group' ];
	constructor( namespace: string ) {
		this.namespace = namespace;
		this.addFlexDirectionControl();
	}

	/**
	 * Wires up filters to necessary callbacks to make the flex direction toggle work
	 */
	private addFlexDirectionControl() {
		const hooks = [
			{
				hook: 'blocks.registerBlockType',
				namespace: `${ this.namespace }/add-flex-direction`,
				callback: this.addFlexDirectionAttribute.bind( this ),
			},
			{
				hook: 'editor.BlockEdit',
				namespace: `${ this.namespace }/with-flex-direction-control`,
				callback: this.addFlexDirectionToggle.bind( this ),
			},
			{
				hook: 'blocks.getSaveContent.extraProps',
				namespace: `${ this.namespace }/columns-flex-direction-styles`,
				callback: this.addFlexDirectionStyles.bind( this ),
			},
			{
				hook: 'editor.BlockListBlock',
				namespace: `${ this.namespace }/columns-flex-direction`,
				callback: this.addInlineStyles.bind( this ),
			},
		];
		hooks.forEach( ( { hook, namespace, callback } ) => {
			addFilter( hook, namespace, callback );
		} );
	}

	/**
	 * Utility function to check if a block can be reversed
	 *
	 * @param name the name of the block
	 */
	private isAllowed( name: string ): boolean {
		return this.canReverse.includes( name );
	}

	/**
	 * Add 	flex-direction attribute to the block settings
	 * @param settings the block settings
	 * @param name     the name of the block
	 * @return
	 */
	private addFlexDirectionAttribute( settings, name: string ): {} {
		if ( this.isAllowed( name ) ) {
			return {
				...settings,
				attributes: {
					...settings.attributes,
					isDirectionReversed: {
						type: 'boolean',
						default: false,
					},
				},
			};
		}
		return settings;
	}

	/**
	 * Adds a toggle control to the block inspector to reverse the flex direction
	 */
	private addFlexDirectionToggle = createHigherOrderComponent(
		( BlockEdit ) => {
			return ( props: any ) => {
				const { attributes, setAttributes, name } = props;
				const { isDirectionReversed } = attributes;
				if ( ! this.isAllowed( name ) ) {
					return <BlockEdit { ...props } />;
				}
				const isDisabled =
					'core/group' === name && 'flex' !== attributes.layout?.type;
				const direction = getReversedDirection( {
					attributes,
					name,
					value: isDirectionReversed,
				} );
				const blockStyles = props.style || {};
				const updatedStyles =
					null !== direction
						? { ...blockStyles, flexDirection: direction }
						: blockStyles;
				return (
					<>
						<BlockEdit { ...{ ...props, style: updatedStyles } } />
						<InspectorControls>
							<PanelBody title="Flex Direction">
								<ToggleControl
									__nextHasNoMarginBottom
									label="Reverse Direction"
									checked={ isDirectionReversed }
									onChange={ ( value ) =>
										setAttributes( {
											isDirectionReversed: value,
										} )
									}
									disabled={ isDisabled }
									help="Reverses the flow of the blocks. Useful for responsive design."
								/>
							</PanelBody>
						</InspectorControls>
					</>
				);
			};
		},
		'addFlexDirectionToggle'
	);

	/**
	 * Adds inline styles to the block in the Editor view to reverse the flex direction
	 */
	private addInlineStyles = createHigherOrderComponent(
		( BlockListBlock ) => {
			return ( props: any ) => {
				const { attributes, name } = props;
				if ( ! this.isAllowed( name ) ) {
					return <BlockListBlock { ...props } />;
				}
				const direction = getReversedDirection( {
					attributes,
					name,
					value: attributes.isDirectionReversed ?? false,
				} );
				const blockStyles = props.style || {};
				const updatedStyles =
					null !== direction
						? { ...blockStyles, flexDirection: direction }
						: blockStyles;
				return (
					<BlockListBlock
						{ ...props }
						wrapperProps={ {
							...props.wrapperProps,
							style: updatedStyles,
						} }
					/>
				);
			};
		},
		'addInlineStyles'
	);

	// Apply the flex-direction style on the front-end
	private addFlexDirectionStyles(
		props: any,
		blockType: any,
		attributes: any
	) {
		if ( this.isAllowed( blockType.name ) ) {
			try {
				const { isDirectionReversed } = attributes;
				if ( isDirectionReversed !== undefined ) {
					const direction = getReversedDirection( {
						attributes,
						value: isDirectionReversed,
						name: blockType.name,
					} );
					if ( direction ) {
						return {
							...props,
							style: {
								...props.style,
								flexDirection: direction,
							},
						};
					}
				}
			} catch ( error ) {
				 
				console.error( error );
			}
		}
		return props;
	}
}

type GetReversedDirectionParams =
	| {
			attributes: {
				layout: {
					type: 'constrained' | 'flex' | 'default' | 'grid';
					orientation: 'horizontal' | 'vertical';
				};
				className?: string;
			};
			value?: boolean;
			name: 'core/group';
	  }
	| {
			attributes: {
				isDirectionReversed?: boolean;
				className?: string;
			};
			value?: boolean;
			name: 'core/columns';
	  };

/**
 * Returns the CSS flex-direction value based on the attributes and toggle value.
 * @param attributes passed attributes from the block
 * @param value      the value of the toggle
 * @param name       the name of the block
 * @return
 */
function getReversedDirection( {
	attributes,
	value,
	name,
}: GetReversedDirectionParams ): string | null {
	if ( 'core/group' === name ) {
		if ( 'flex' !== attributes.layout?.type || undefined === value ) {
			return null;
		}

		const isVertical = 'vertical' === attributes.layout.orientation;

		if ( ! value ) {
			return isVertical ? 'column' : 'row';
		}

		return isVertical ? 'column-reverse' : 'row-reverse';
	}
	if ( 'core/columns' === name ) {
		if ( undefined === attributes.isDirectionReversed ) {
			return null;
		}
		if ( attributes.isDirectionReversed ) {
			return 'row-reverse';
		}
		return 'row';
	}
	return null;
}
