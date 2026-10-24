import { store as blockEditorStore } from '@wordpress/block-editor';
import { store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { addFilter } from '@wordpress/hooks';
import type { ComponentType } from '@wordpress/element';

type Query = {
	perPage?: number | null;
	offset?: number;
	postType?: string;
	order?: string;
	orderBy?: string;
	author?: number | string;
	search?: string;
	exclude?: number[];
	sticky?: string;
	parents?: number[];
	taxQuery?: Record< string, number[] > | null;
};

type EditProps = {
	name: string;
	clientId: string;
	context?: {
		query?: Query;
		[ key: string ]: unknown;
	};
	[ key: string ]: unknown;
};

function DeduplicatedPreview( {
	BlockEdit,
	...props
}: EditProps & { BlockEdit: ComponentType< EditProps > } ) {
	const usedIds = useSelect(
		( select ) => {
			const editor = select( blockEditorStore );

			// Find the Query Loop containing this Post Template.
			const queryId = editor
				.getBlockParentsByBlockName( props.clientId, 'core/query' )
				.at( -1 );

			if ( ! queryId ) {
				return [];
			}

			const used: number[] = [];
			const core = select( coreStore );
			// Visit Query Loops in document order.
			for ( const id of editor.getClientIdsWithDescendants() ) {
				if ( id === queryId ) {
					break;
				}

				if ( editor.getBlockName( id ) !== 'core/query' ) {
					continue;
				}

				const q = ( editor.getBlockAttributes( id )?.query ??
					{} ) as Query;

				const postType = q.postType || 'post';

				const args: Record< string, unknown > = {
					offset: q.offset || 0,
					order: q.order || 'desc',
					orderby: q.orderBy || 'date',
				};

				if ( q.perPage ) {
					args.per_page = q.perPage;
				}
				if ( q.author ) {
					args.author = q.author;
				}
				if ( q.search ) {
					args.search = q.search;
				}
				if ( q.parents?.length ) {
					args.parent = q.parents;
				}
				if ( q.sticky ) {
					args.sticky = q.sticky === 'only';
				}

				// Include exclusions from previous loops.
				const exclude = [
					...new Set( [ ...used, ...( q.exclude || [] ) ] ),
				];

				if ( exclude.length ) {
					args.exclude = exclude;
				}

				// Map taxonomy slugs to their REST API parameter names.

				if ( q.taxQuery && Object.keys( q.taxQuery ).length ) {
					const taxonomies = core.getTaxonomies( {
						type: postType,
						per_page: -1,
						context: 'view',
					} );

					if ( ! taxonomies ) {
						return null;
					}

					const groups =
						'include' in q.taxQuery || 'exclude' in q.taxQuery
							? q.taxQuery
							: { include: q.taxQuery };

					for ( const [ mode, filters ] of Object.entries(
						groups
					) ) {
						if ( ! filters || typeof filters !== 'object' ) {
							continue;
						}

						for ( const [ slug, terms ] of Object.entries(
							filters
						) ) {
							if ( ! Array.isArray( terms ) || ! terms.length ) {
								continue;
							}

							const restBase = taxonomies.find(
								( taxonomy ) => taxonomy.slug === slug
							)?.rest_base;

							if ( restBase ) {
								const key =
									mode === 'exclude'
										? `${ restBase }_exclude`
										: restBase;

								args[ key ] = terms;
							}
						}
					}
				}

				const posts = core.getEntityRecords(
					'postType',
					postType,
					args
				) as { id: number }[] | null;

				// Wait for this query before resolving the next one.
				if ( ! posts ) {
					return null;
				}

				used.push( ...posts.map( ( post ) => post.id ) );
			}
			return used;
		},
		[ props.clientId ]
	);
	if ( usedIds === null ) {
		return <p role="status">Loading posts…</p>;
	}
	const query = props.context?.query || {};

	return (
		<BlockEdit
			{ ...props }
			context={ {
				...props.context,
				query: {
					...query,
					exclude: [
						...new Set( [
							...( query.exclude || [] ),
							...usedIds,
						] ),
					],
				},
			} }
		/>
	);
}

addFilter(
	'editor.BlockEdit',
	'kjr/query-deduplication',
	( BlockEdit: ComponentType< EditProps > ) => ( props: EditProps ) => {
		if ( props.name !== 'core/post-template' ) {
			return <BlockEdit { ...props } />;
		}

		return <DeduplicatedPreview { ...props } BlockEdit={ BlockEdit } />;
	}
);
