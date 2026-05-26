<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_contenthistory
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

?>
<h3>
<?php echo JText::sprintf('COM_CONTENTHISTORY_PREVIEW_SUBTITLE_DATE', $this->escape($this->item->save_date)); ?>
<?php if ($this->item->version_note) : ?>
	&nbsp;&nbsp;<?php echo JText::sprintf('COM_CONTENTHISTORY_PREVIEW_SUBTITLE', $this->escape($this->item->version_note)); ?>
<?php endif; ?>
</h3>
<table class="table table-striped" >
<thead><tr>
	<th width="25%"><?php echo JText::_('COM_CONTENTHISTORY_PREVIEW_FIELD'); ?></th>
	<th><?php echo JText::_('COM_CONTENTHISTORY_PREVIEW_VALUE'); ?></th>
</tr></thead>
<tbody>
<?php foreach ($this->item->data as $name => $value) : ?>
	<tr>
	<?php if (is_object($value->value)) : ?>
		<td><strong><?php echo $value->label; ?></strong></td>
		<td></td><tr>
		<?php foreach ($value->value as $subName => $subValue) : ?>
			<?php if ($subValue) : ?>
				<?php $subValue->value = (is_object($subValue->value) || is_array($subValue->value)) ? json_encode($subValue->value) : $subValue->value; ?>
				<tr>
				<td><i>&nbsp;&nbsp;<?php echo $subValue->label; ?></i></td>
				<td><?php echo $this->escape($subValue->value); ?></td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php else : ?>
		<td><strong><?php echo $value->label; ?></strong></td>
		<td><?php echo $this->escape($value->value); ?></td>
	<?php endif; ?>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
