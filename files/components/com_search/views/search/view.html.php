<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   (C) 2007 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\String\StringHelper;

/**
 * HTML View class for the search component
 *
 * @since  1.0
 */
class SearchViewSearch extends JViewLegacy
{

    protected $pagination = null;

    /**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since 1.0
	 */
	public function display($tpl = null)
	{
		JLoader::register('SearchHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/search.php');

		$app     = JFactory::getApplication();
		$uri     = JUri::getInstance();
		$error   = null;
		$results = null;
		$total   = 0;

		// Get some data from the model
		$areas      = $this->get('areas');
		$state      = $this->get('state');
		$searchWord = $state->get('keyword');
		$params     = $app->getParams();

		if (!$app->getMenu()->getActive())
		{
			$params->set('page_title', JText::_('COM_SEARCH_SEARCH'));
		}

		$title = $params->get('page_title');

		if ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($params->get('menu-meta_description'))
		{
			$this->document->setDescription($params->get('menu-meta_description'));
		}

		if ($params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		}

		if ($params->get('robots'))
		{
			$this->document->setMetadata('robots', $params->get('robots'));
		}

		// Built select lists
		$orders   = array();
		$orders[] = JHtml::_('select.option', 'newest', JText::_('COM_SEARCH_NEWEST_FIRST'));
		$orders[] = JHtml::_('select.option', 'oldest', JText::_('COM_SEARCH_OLDEST_FIRST'));
		$orders[] = JHtml::_('select.option', 'popular', JText::_('COM_SEARCH_MOST_POPULAR'));
		$orders[] = JHtml::_('select.option', 'alpha', JText::_('COM_SEARCH_ALPHABETICAL'));
		$orders[] = JHtml::_('select.option', 'category', JText::_('JCATEGORY'));

		$lists             = array();
		$lists['ordering'] = JHtml::_('select.genericlist', $orders, 'ordering', 'class="inputbox"', 'value', 'text', $state->get('ordering'));

		$searchphrases         = array();
		$searchphrases[]       = JHtml::_('select.option', 'all', JText::_('COM_SEARCH_ALL_WORDS'));
		$searchphrases[]       = JHtml::_('select.option', 'any', JText::_('COM_SEARCH_ANY_WORDS'));
		$searchphrases[]       = JHtml::_('select.option', 'exact', JText::_('COM_SEARCH_EXACT_PHRASE'));
		$lists['searchphrase'] = JHtml::_('select.radiolist', $searchphrases, 'searchphrase', '', 'value', 'text', $state->get('match'));

		// Log the search
		\Joomla\CMS\Helper\SearchHelper::logSearch($searchWord, 'com_search');

		// Limit search-word
		$lang        = JFactory::getLanguage();
		$upper_limit = $lang->getUpperLimitSearchWord();
		$lower_limit = $lang->getLowerLimitSearchWord();

		if (SearchHelper::limitSearchWord($searchWord))
		{
			$error = JText::sprintf('COM_SEARCH_ERROR_SEARCH_MESSAGE', $lower_limit, $upper_limit);
		}

		// Sanitise search-word
		if (SearchHelper::santiseSearchWord($searchWord, $state->get('match')))
		{
			$error = JText::_('COM_SEARCH_ERROR_IGNOREKEYWORD');
		}

		if (!$searchWord && !empty($this->input) && count($this->input->post))
		{
			// $error = JText::_('COM_SEARCH_ERROR_ENTERKEYWORD');
		}

		// Put the filtered results back into the model
		// for next release, the checks should be done in the model perhaps...
		$state->set('keyword', $searchWord);

		if ($error === null)
		{
			$results    = $this->get('data');
			$total      = $this->get('total');
			$pagination = $this->get('pagination');

			// Flag indicates to not add limitstart=0 to URL
			$pagination->hideEmptyLimitstart = true;

            // Add additional parameters
			$queryParameterList = [
				'searchword'  => 'string',
				'searchphrase'  => 'string',
				'areas' => 'array',
				'ordering' => 'string'
			];

            foreach ($queryParameterList as $parameter => $filter)
			{
                $value = $app->input->get($parameter, null, $filter);

                if (is_null($value))
				{
                    continue;
                }

                $pagination->setAdditionalUrlParam($parameter, $value);
            }

			if ($state->get('match') === 'exact')
			{
				$searchWords = array($searchWord);
				$needle      = $searchWord;
			}
			else
			{
				$searchWordA = preg_replace('#\xE3\x80\x80#', ' ', $searchWord);
				$searchWords = preg_split("/\s+/u", $searchWordA);
				$needle      = $searchWords[0];
			}

			JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');

			// Make sure there are no slashes in the needle
			$needle = str_replace('/', '\/', $needle);

			for ($i = 0, $count = count($results); $i < $count; ++$i)
			{
				$rowTitle = &$results[$i]->title;
				$rowTitleHighLighted = $this->highLight($rowTitle, $needle, $searchWords);
				$rowText = &$results[$i]->text;
				$rowTextHighLighted = $this->highLight($rowText, $needle, $searchWords);

				$result = &$results[$i];
				$created = '';

				if ($result->created)
				{
					$created = JHtml::_('date', $result->created, JText::_('DATE_FORMAT_LC3'));
				}

				$result->title   = $rowTitleHighLighted;
				$result->text    = JHtml::_('content.prepare', $rowTextHighLighted, '', 'com_search.search');
				$result->created = $created;
				$result->count   = $i + 1;
			}
		}

		// Check for layout override
		$active = JFactory::getApplication()->getMenu()->getActive();

		if (isset($active->query['layout']))
		{
			$this->setLayout($active->query['layout']);
		}

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx', ''));
		$this->pagination    = &$pagination;
		$this->results       = &$results;
		$this->lists         = &$lists;
		$this->params        = &$params;
		$this->ordering      = $state->get('ordering');
		$this->searchword    = $searchWord;
		$this->origkeyword   = $state->get('origkeyword');
		$this->searchphrase  = $state->get('match');
		$this->searchareas   = $areas;
		$this->total         = $total;
		$this->error         = $error;
		$this->action        = $uri;

		parent::display($tpl);
	}

	/**
	 * Method to control the highlighting of keywords
	 *
	 * @param   string  $string       text to be searched
	 * @param   string  $needle       text to search for
	 * @param   string  $searchWords  words to be searched
	 *
	 * @return  mixed  A string.
	 *
	 * @since   3.8.4
	 */
	public function highLight($string, $needle, $searchWords)
	{
		$hl1            = '<span class="highlight">';
		$hl2            = '</span>';
		$mbString       = extension_loaded('mbstring');
		$highlighterLen = strlen($hl1 . $hl2);

		// Doing HTML entity decoding here, just in case we get any HTML entities here.
		$quoteStyle   = version_compare(PHP_VERSION, '5.4', '>=') ? ENT_NOQUOTES | ENT_HTML401 : ENT_NOQUOTES;
		$row          = html_entity_decode($string, $quoteStyle, 'UTF-8');
		$row          = SearchHelper::prepareSearchContent($row, $needle);
		$searchWords  = array_values(array_unique($searchWords));
		$lowerCaseRow = $mbString ? mb_strtolower($row) : StringHelper::strtolower($row);

		$transliteratedLowerCaseRow = SearchHelper::remove_accents($lowerCaseRow);

		$posCollector = array();

		foreach ($searchWords as $highlightWord)
		{
			$found = false;

			if ($mbString)
			{
				$lowerCaseHighlightWord = mb_strtolower($highlightWord);

				if (($pos = mb_strpos($lowerCaseRow, $lowerCaseHighlightWord)) !== false)
				{
					$found = true;
				}
				elseif (($pos = mb_strpos($transliteratedLowerCaseRow, $lowerCaseHighlightWord)) !== false)
				{
					$found = true;
				}
			}
			else
			{
				$lowerCaseHighlightWord = StringHelper::strtolower($highlightWord);

				if (($pos = StringHelper::strpos($lowerCaseRow, $lowerCaseHighlightWord)) !== false)
				{
					$found = true;
				}
				elseif (($pos = StringHelper::strpos($transliteratedLowerCaseRow, $lowerCaseHighlightWord)) !== false)
				{
					$found = true;
				}
			}

			if ($found === true)
			{
				// Iconv transliterates '€' to 'EUR'
				// TODO: add other expanding translations?
				$eur_compensation = $pos > 0 ? substr_count($row, "\xE2\x82\xAC", 0, $pos) * 2 : 0;
				$pos -= $eur_compensation;

				// Collect pos and search-word
				$posCollector[$pos] = $highlightWord;
			}
		}

		if (count($posCollector))
		{
			// Sort by pos. Easier to handle overlapping highlighter-spans
			ksort($posCollector);
			$cnt                = 0;
			$lastHighlighterEnd = -1;

			foreach ($posCollector as $pos => $highlightWord)
			{
				$pos += $cnt * $highlighterLen;

				/*
				 * Avoid overlapping/corrupted highlighter-spans
				 * TODO $chkOverlap could be used to highlight remaining part
				 * of search-word outside last highlighter-span.
				 * At the moment no additional highlighter is set.
				 */
				$chkOverlap = $pos - $lastHighlighterEnd;

				if ($chkOverlap >= 0)
				{
					// Set highlighter around search-word
					if ($mbString)
					{
						$highlightWordLen = mb_strlen($highlightWord);
						$row              = mb_substr($row, 0, $pos) . $hl1 . mb_substr($row, $pos, $highlightWordLen)
							. $hl2 . mb_substr($row, $pos + $highlightWordLen);
					}
					else
					{
						$highlightWordLen = StringHelper::strlen($highlightWord);
						$row              = StringHelper::substr($row, 0, $pos)
							. $hl1 . StringHelper::substr($row, $pos, StringHelper::strlen($highlightWord))
							. $hl2 . StringHelper::substr($row, $pos + StringHelper::strlen($highlightWord));
					}

					$cnt++;
					$lastHighlighterEnd = $pos + $highlightWordLen + $highlighterLen;
				}
			}
		}

		return $row;
	}
}
