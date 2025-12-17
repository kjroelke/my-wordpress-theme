import { FlexReverse } from './FlexReverse';

/**
 * Adds a toggle control to reverse the flex direction of a block.
 *
 * @param namespace the namespace of the callback functions to run
 */
export default function addFlexReverseToggle( namespace: string ) {
	try {
		new FlexReverse( namespace );
	} catch ( error ) {
		// eslint-disable-next-line no-console
		console.error( `Error initializing FlexReverse:`, error );
	}
}
