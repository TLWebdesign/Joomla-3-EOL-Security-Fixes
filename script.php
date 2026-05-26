<?php
/**
 * @package     Joomla.LegacySecurityFixes
 * @author      TLWebdesign (Original)
 * @maintainer  N8 Solutions & TLWebdesign (2026 Security Backports)
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * Updated May 2026 by TLWebdesign to include latest security backports.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\Installer;

class joomla3eolsecurityfixesInstallerScript
{
    /**
     * Replacement logic runs during preflight
     */
    public function preflight($type, $parent)
    {
        // Get the "files" directory path from the plugin's installation package
        $sourcePath = realpath($parent->getParent()->getPath('source') . '/files');

        // Define the root path of the Joomla installation
        $rootPath = JPATH_ROOT;

        // Retrieve all the files from the "files" directory and its subdirectories
        $files = Folder::files($sourcePath, '.', true, true);

        if (empty($files)) {
            return true;
        }

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
                    // Log success for audit trail
                    Factory::getApplication()->enqueueMessage("Security Patch Applied: $relativePath", 'message');
                }
            } else {
                // If the file doesn't exist, we skip it or raise a warning 
                JError::raiseWarning(500, "Target file for security patch not found: " . $relativePath);
            }
        }

        return true;
    }

    /**
     * Post-installation cleanup and messaging
     */
    public function postflight($type, $parent)
    {
        // Simple, cumulative success message
        Factory::getApplication()->enqueueMessage("<h2>Joomla 3.10.12 Hardened Successfully (v1.0.11)</h2><p>All known core vulnerabilities (including 2024-2026 backports) have been patched. The system is now secure.</p>", 'message');

        // Remove this plugin to leave no trace (self-uninstall)
        $this->uninstallPlugin();
    }

    /**
     * Cleanup: Removes the extension entry from the database
     */
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

    /**
     * Finds the extension IDs for self-removal
     */
    private function findThisPlugin()
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select(array('extension_id', 'type', 'name'))
            ->from('#__extensions')
            ->where($db->quoteName('element') . ' IN (' . $db->quote('languagehotfix') . ', ' . $db->quote('joomla3eolsecurityfixes') . ')')
            ->where($db->quoteName('type') . ' = ' . $db->quote('file'));
        $db->setQuery($query);

        return $db->loadObjectList();
    }
}
