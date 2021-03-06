<?php
/**
 * Elgg page handler functions
 *
 * @package Elgg.Core
 * @subpackage Routing
 */

/**
 * Registers a page handler for a particular identifier
 *
 * For example, you can register a function called 'blog_page_handler' for handler type 'blog'
 * For all URLs  http://yoururl/blog/*, the blog_page_handler() function will be called.
 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
 * array to that function.
 * For example, the URL http://yoururl/blog/username/friends/ would result in the call:
 * blog_page_handler(array('username','friends'), blog);
 *
 * A request to register a page handler with the same identifier as previously registered
 * handler will replace the previous one.
 *
 * The context is set to the page handler identifier before the registered
 * page handler function is called. For the above example, the context is set to 'blog'.
 *
 * Page handlers should return true to indicate that they handled the request.
 * Requests not handled are forwarded to the front page with a reason of 404.
 * Plugins can register for the 'forward', '404' plugin hook. @see forward()
 *
 * @param string $handler  The page type to handle
 * @param string $function Your function name
 *
 * @return bool Depending on success
 */
function elgg_register_page_handler($handler, $function) {
	return _elgg_services()->router->registerPageHandler($handler, $function);
}

/**
 * Unregister a page handler for an identifier
 *
 * Note: to replace a page handler, call elgg_register_page_handler()
 *
 * @param string $handler The page type identifier
 *
 * @since 1.7.2
 * @return void
 */
function elgg_unregister_page_handler($handler) {
	_elgg_services()->router->unregisterPageHandler($handler);
}

/**
 * Serve an error page
 *
 * @todo not sending status codes yet
 *
 * @param string $hook   The name of the hook
 * @param string $type   The type of the hook
 * @param bool   $result The current value of the hook
 * @param array  $params Parameters related to the hook
 * @return void
 */
function elgg_error_page_handler($hook, $type, $result, $params) {
	if (elgg_view_exists("errors/$type")) {
		$content = elgg_view("errors/$type", $params);
	} else {
		$content = elgg_view("errors/default", $params);
	}
	$body = elgg_view_layout('error', array('content' => $content));
	echo elgg_view_page('', $body, 'error');
	exit;
}

/**
 * Initializes the page handler/routing system
 *
 * @return void
 * @access private
 */
function page_handler_init() {
	elgg_register_plugin_hook_handler('forward', '404', 'elgg_error_page_handler');
}

elgg_register_event_handler('init', 'system', 'page_handler_init');
