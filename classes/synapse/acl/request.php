<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * ACL Request
 *
 * @package    ACL
 * @author     Synapse Studios
 * @author     Jeremy Lindblom <jeremy@synapsestudios.com>
 * @copyright  (c) 2010 Synapse Studios
 */
class Synapse_ACL_Request
{
	protected $_directory;
	protected $_controller;
	protected $_action;

	public static function factory($action, $controller, $directory = NULL)
	{
		return new ACL_Request($action, $controller, $directory);
	}

	public static function from_array(array $request)
	{
		$action = (string) Arr::get($request, 'action');
		$controller = (string) Arr::get($request, 'controller');
		$directory = (string) Arr::get($request, 'directory');

		return new ACL_Request($action, $controller, $directory);
	}

	public static function from_request(Kohana_Request $request)
	{
		$action = $request->action();
		$controller = $request->controller();
		$directory = $request->directory();

		return new ACL_Request($action, $controller, $directory);
	}

	public function __construct($action, $controller, $directory = NULL)
	{
		if (empty($action) OR empty($controller))
			throw new InvalidArgumentException('A controller and an action must be provided to create an instance of ACL_Action.');

		$this->_action = $action;
		$this->_controller = $controller;
		$this->_directory = $directory;
	}

	/**
	 * Sets and gets the directory for the controller.
	 *
	 * @param   string   $directory  Directory to execute the controller from
	 * @return  mixed
	 */
	public function directory($directory = NULL)
	{
		if ($directory === NULL)
		{
			// Act as a getter
			return $this->_directory;
		}

		// Act as a setter
		$this->_directory = (string) $directory;

		return $this;
	}

	/**
	 * Sets and gets the controller for the matched route.
	 *
	 * @param   string   $controller  Controller to execute the action
	 * @return  mixed
	 */
	public function controller($controller = NULL)
	{
		if ($controller === NULL)
		{
			// Act as a getter
			return $this->_controller;
		}

		// Act as a setter
		$this->_controller = (string) $controller;

		return $this;
	}

	/**
	 * Sets and gets the action for the controller.
	 *
	 * @param   string   $action  Action to execute the controller from
	 * @return  mixed
	 */
	public function action($action = NULL)
	{
		if ($action === NULL)
		{
			// Act as a getter
			return $this->_action;
		}

		// Act as a setter
		$this->_action = (string) $action;

		return $this;
	}

	public function as_array()
	{
		return array(
			'directory' => $this->_directory,
			'controller' => $this->_controller,
			'action' => $this->_action,
		);
	}

	public function uri(Kohana_Route $route, array $params)
	{
		$params = Arr::merge($this->as_array(), $params);
		$uri = $route->uri($params);

		return $uri;
	}
}