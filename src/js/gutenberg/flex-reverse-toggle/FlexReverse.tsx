import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, PanelBody } from '@wordpress/components';

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
					},
				},
			};
		}
		return settings;
	}

	/**
	 * Adds a toggle control to the block inspector to reverse the flex direction
	 */
	private addFlexDirectionToggle = createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: any ) => {
			const { attributes, setAttributes, name } = props;
			const { isDirectionReversed } = attributes;
			if ( ! this.isAllowed( name ) ) {
				return <BlockEdit { ...props } />;
			}
			const isDisabled =
				attributes.layout && attributes.layout?.type !== 'flex';
			const direction = getReversedDirection( {
				attributes,
				name,
				value: isDirectionReversed,
			} );
			const blockStyles = props.style || {};
			if ( null !== direction ) {
				// If the direction is not null, we set the flexDirection style
				blockStyles.flexDirection = direction;
			}
			return (
				<>
					<BlockEdit { ...{ ...props, style: blockStyles } } />
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
	}, 'addFlexDirectionToggle' );

	/**
	 * Adds inline styles to the block in the Editor view to reverse the flex direction
	 */
	private addInlineStyles = createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: any ) => {
			const { attributes, name } = props;
			if ( ! this.isAllowed( name ) || ! attributes.isDirectionReversed ) {
				return <BlockListBlock { ...props } />;
			}
			const direction = getReversedDirection( {
				attributes,
				name,
				value: attributes.isDirectionReversed ?? false,
			} );
			const blockStyles = props.style || {};
			if ( null !== direction ) {
				// If the direction is not null, we set the flexDirection style
				blockStyles.flexDirection = direction;
			}
			return (
				<BlockListBlock
					{ ...props }
					wrapperProps={ {
						...props.wrapperProps,
						style: blockStyles,
					} }
				/>
			);
		};
	}, 'addInlineStyles' );

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
				// eslint-disable-next-line no-console
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
		if ( false === value ) {
			return 'vertical' === attributes.layout.orientation
				? 'column'
				: 'row';
		}
		return 'vertical' === attributes.layout.orientation
			? 'column-reverse'
			: 'row-reverse';
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

export { FlexReverse };
