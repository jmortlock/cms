<?php
namespace craft\app\io\flysystemadapters;

use \League\Flysystem\AdapterInterface as AdapterInterface;

/**
 * Interface IFlysystemAdapter
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @copyright Copyright (c) 2014, Pixel & Tonic, Inc.
 * @license   http://buildwithcraft.com/license Craft License Agreement
 * @see       http://buildwithcraft.com
 * @package   craft.app.io.flysystemadapters
 * @since     3.0
 */
interface IFlysystemAdapter extends AdapterInterface
{
	// Public Methods
	// =========================================================================

	/**
	 * Renames a directory.
	 *
	 * @param string $path    The old path of the file, relative to the source’s root.
	 * @param string $newPath The new path of the file, relative to the source’s root.
	 *
	 * @return bool Whether the operation was successful.
	 */
	public function renameDir($path, $newPath);
}
