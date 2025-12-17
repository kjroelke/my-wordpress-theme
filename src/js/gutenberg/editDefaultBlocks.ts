import addFlexReverseToggle from './flex-reverse-toggle/addFlexReverseToggle';
import domReady from '@wordpress/dom-ready';

function alterBlocks(): void {
	const namespace = 'macrosbysara';
	addFlexReverseToggle( namespace );
}
domReady( alterBlocks );
