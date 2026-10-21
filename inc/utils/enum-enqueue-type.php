<?php
/**
 * Describes the allowable Enqueue types
 *
 * @package KJRoelke
 */

namespace KJRoelke\Utils;

/** Allowable Enqueue Types */
enum Enqueue_Type {
	case script;
	case style;
	case both;
}
