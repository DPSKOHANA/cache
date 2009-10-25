<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana Cache eAccelerator Driver
 * 
 * Requires eAccelerator
 * 
 * @package Cache
 * @author Sam de Freyssinet <sam@def.reyssi.net>
 * @copyright (c) 2009 Sam de Freyssinet
 * @license ISC http://www.opensource.org/licenses/isc-license.txt
 * Permission to use, copy, modify, and/or distribute 
 * this software for any purpose with or without fee
 * is hereby granted, provided that the above copyright 
 * notice and this permission notice appear in all copies.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS 
 * ALL WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO 
 * EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, 
 * INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES 
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, 
 * WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER 
 * TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH 
 * THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */
class Cache_Eaccelerator extends Cache {

	/**
	 * Creates an instance. As there is no
	 * OOP interface this is largely redundent
	 *
	 * @return Cache_Apc
	 * @access public
	 * @static
	 */
	static public function instance()
	{
		static $instance;

		(NULL === $instance) and $instance = new Cache_Eaccelerator;

		return $instance;
	}

	/**
	 * Check for existence of the eAccelerator extension
	 *
	 * @access protected
	 * @throws Cache_Exception
	 */
	protected function __construct()
	{
		if ( ! extension_loaded('eaccelerator'))
			throw new Cache_Exception('PHP eAccelerator extension is not available.');
	}

	/**
	 * Retrieve a value based on an id
	 *
	 * @param string $id 
	 * @param string $default [Optional] Default value to return if id not found
	 * @return mixed
	 * @access public
	 */
	public function get($id, $default = NULL)
	{
		return (($data = eaccelerator_get($this->sanitize_id($id))) === FALSE) ? $default : $data;
	}

	/**
	 * Set a value based on an id. Optionally add tags.
	 * 
	 * @param string $id 
	 * @param string $data 
	 * @param integer $lifetime [Optional]
	 * @return boolean
	 * @access public
	 */
	public function set($id, $data, $lifetime = NULL)
	{
		if (NULL === $lifetime)
			$lifetime = time() + Kohana::config('cache-eaccelerator')->default_expire;

		return eaccelerator_put($this->sanitize_id($id), $data, $lifetime);
	}

	/**
	 * Delete a cache entry based on id
	 *
	 * @param string $id 
	 * @param integer $timeout [Optional]
	 * @return boolean
	 * @access public
	 */
	public function delete($id)
	{
		return eaccelerator_rm($this->sanitize_id($id));
	}

	/**
	 * Delete all cache entries
	 *
	 * @return boolean
	 * @access public
	 */
	public function delete_all()
	{
		return eaccelerator_clean();
	}
}