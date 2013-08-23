<?php
/**
 * Magify Commerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magify.com so we can send you a copy immediately.
 *
 * @category    Magify
 * @package     Magify_Core
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magify_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Checks if any module is activated
	 * @param string $name
	 * $name to be given as full module name e.g. 'Magify_Core'
	 * @return bool
	 */
	public function moduleActive($name)
    {
        return ('true' == (string)Mage::getConfig()->getNode('modules/' . $name . '/active'));
    }
    
    /**
     * Turns colour hexcode to an RGB array
     * @param string $hexcode
     * @param bool $returnAsString
     * @param string $seperator
     * @return array or string
     */
	public function hex2RGB($hexcode, $returnAsString = false, $seperator = ',')
	{
	    $hexcode = preg_replace("/[^0-9A-Fa-f]/", '', $hexcode); // Gets a proper hex string
	    $rgbArray = array();
	    if (strlen($hexcode) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
	        $colorVal = hexdec($hexcode);
	        $rgbArray[] = 0xFF & ($colorVal >> 0x10);
	        $rgbArray[] = 0xFF & ($colorVal >> 0x8);
	        $rgbArray[] = 0xFF & $colorVal;
	    } elseif (strlen($hexcode) == 3) { //if shorthand notation, need some string manipulations
	        $rgbArray[] = hexdec(str_repeat(substr($hexcode, 0, 1), 2));
	        $rgbArray[] = hexdec(str_repeat(substr($hexcode, 1, 1), 2));
	        $rgbArray[] = hexdec(str_repeat(substr($hexcode, 2, 1), 2));
	    } else {
	        return false; //Invalid hex color code
	    }
	    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
	}
	
    /**
     * Truncates a HTML string without breaking tags
     * @param string $text
     * @param int $length
     * @param string $ending
     * @param bool $exact
     * @param bool $considerHtml
     * @return HTML string
     */
	public static function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
	{
		if($considerHtml)
		{
			// if the plain text is shorter than the maximum length, return the whole text
			if($length == 0 || $length == NULL || strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$total_length = 0;
			$open_tags = array();
			$truncate = '';
			foreach ($lines as $line_matchings)
			{
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if(!empty($line_matchings[1]))
				{
					// if it's an "empty element" with or without xhtml-conform closing slash
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
						// do nothing
					// if tag is a closing tag
					}
					elseif(preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings))
					{
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
						unset($open_tags[$pos]);
						}
					// if tag is an opening tag
					}
					elseif(preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings))
					{
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if($total_length+$content_length> $length)
				{
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if(preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE))
					{
						// calculate the real length of all entities in the legal range
						foreach($entities[0] as $entity){
							if($entity[1]+1-$entities_length <= $left)
							{
								$left--;
								$entities_length += strlen($entity[0]);
							}
							else
							{
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				}
				else
				{
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if($total_length>= $length)
				{
					break;
				}
			}
		} else {
			if(strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}
		// if the words shouldn't be cut in the middle...
		if(!$exact){
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if(isset($spacepos)){
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if($considerHtml)
		{
			// close all unclosed html-tags
			foreach($open_tags as $tag)
			{
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
}