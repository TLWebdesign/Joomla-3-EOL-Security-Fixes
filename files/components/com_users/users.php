<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

JLoader::register('UsersHelperRoute', JPATH_COMPONENT . '/helpers/route.php');

$app  = Factory::getApplication();
$user = Factory::getUser();
$view = $app->input->get('view');

if ($view)
{
	switch ($view)
	{
		case 'registration':
			// If the user is already logged in, redirect to the profile page.
			if ($user->get('guest') != 1)
			{
				// Redirect to profile page.
				$app->redirect(Route::_('index.php?option=com_users&view=profile', false));
			}

			// Check if user registration is enabled
			if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0)
			{
				// Registration is disabled - Redirect to login page.
				$app->redirect(Route::_('index.php?option=com_users&view=login', false));
			}
			break;
		case 'profile':
			if ($user->get('guest') == 1)
			{
				// Redirect to login page.
				$app->redirect(Route::_('index.php?option=com_users&view=login', false));
			}
			break;
		case 'reset':
		case 'remind':
			if ($user->get('guest') != 1)
			{
				// Redirect to profile page.
				$app->redirect(Route::_('index.php?option=com_users&view=profile', false));
			}
			break;
	}
}

$controller = JControllerLegacy::getInstance('Users');
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));
$controller->redirect();
