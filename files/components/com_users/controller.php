<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Base controller class for Users.
 *
 * @since  1.5
 */
class UsersController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->getCmd('view', 'login');
		$vFormat = $document->getType();
		$lName   = $this->input->getCmd('layout', 'default');

		if ($view = $this->getView($vName, $vFormat))
		{
			// Do any specific processing by view.
			switch ($vName)
			{
				case 'registration':
				case 'profile':
				case 'login':
				case 'reset':
				case 'remind':
					$model = $this->getModel($vName);
					break;
				default:
					$model = $this->getModel('Login');
					break;
			}

			// Make sure we don't send a referer
			if (in_array($vName, array('remind', 'reset')))
			{
				JFactory::getApplication()->setHeader('Referrer-Policy', 'no-referrer', true);
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->document = $document;

			$view->display();
		}
	}
}
