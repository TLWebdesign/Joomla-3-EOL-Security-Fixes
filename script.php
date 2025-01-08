<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\Installer;

class  joomla3eolsecurityfixesInstallerScript
{
    function preflight($type, $parent)
    {
        // Get the "files" directory path from the plugin's installation package
	    $sourcePath = realpath($parent->getParent()->getPath('source') . '/files');

        // Define the root path of your Joomla installation
        $rootPath = JPATH_ROOT;

        // Retrieve all the files from the "files" directory and its subdirectories
        $files = Folder::files($sourcePath, '.', true, true);

        foreach ($files as $file) {
            // Calculate the relative path from the "files" directory
            $relativePath = str_replace($sourcePath, '', $file);

            // Determine the destination path in the root directory
            $destPath = $rootPath . '/' . $relativePath;

            // Check if the file exists in the destination path
            if (File::exists($destPath)) {
                if (!File::copy($file, $destPath, '', false)) {
                    Factory::getApplication()->enqueueMessage("Failed to replace $relativePath", 'error');
                } else {
                    Factory::getApplication()->enqueueMessage("Replaced $relativePath", 'message');
                }
            } else {
                JError::raiseWarning(500, "original File not found: " . $relativePath);
            }
        }

        return true;
    }

    function postflight($type, $parent) {
        // Remove this plugin to leave no trace
        $this->uninstallPlugin();
    }

    private function uninstallPlugin()
    {
        $plugins = $this->findThisPlugin();
        foreach ($plugins as $plugin) {
            if (Installer::getInstance()->uninstall($plugin->type, $plugin->extension_id)) {
                Factory::getApplication()->enqueueMessage("Plugin " . $plugin->name . " successfully removed", 'message');
            } else {
                Factory::getApplication()->enqueueMessage("Plugin " . $plugin->name . " could not be removed automatically. Please uninstall manually!", 'warning');
            }
        }
    }
    private function findThisPlugin()
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select(array('extension_id', 'type', 'name'))
            ->from('#__extensions')
            ->where($db->quoteName('element') . 'IN (' . $db->quote('languagehotfix') . ', ' . $db->quote('joomla3eolsecurityfixes') . ')')
            ->where($db->quoteName('type') . ' = ' . $db->quote('file'));
        $db->setQuery($query);

        return $db->loadObjectList();
    }
}
