<?php

namespace Ahocorasick\Src;

class AhocorasickTest
{
  /**
   * @var \Ahocorasick\Src\Ahocorasick
   */
  protected $ahocorasick;

  /**
   * @var \Ahocorasick\Src\TreeNodes
   */
  protected $treeNodes;

  public function __construct(Ahocorasick $ahocorasick, TreeNodes $treeNodes)
  {
    $this->ahocorasick = $ahocorasick;
    $this->treeNodes = $treeNodes;
  }

  /**
   * Delete cache.
   *
   * @param string $keywordsName Cache name of data 
   */
  public function deleteCache($keywordsName)
  {
    $filePath = "/tmp/".sha1($keywordsName).".dat";
    if(file_exists($filePath)) {
        unlink($filePath);
    }
  }

  /**
   * Searches for occurrence of $keywords in each element of $textArray.
   *
   * @param array  $textArray
   * @param array  $keywords
   * @param string $keywordsName Cache name of data 
   * @param bool   $useCache 
   * @param array  $delimiters   Characters which define the word boundary
   * @param        $sortByLength 'asc', 'desc', or false
   * @param bool   $trimText
   *
   * @return array
   */
  public function search(array $textArray, array $keywords, $keywordsName, $useCache = true, array $delimiters = [], $sortByLength = 'desc', $trimText = true)
  {
    $filePath = "/tmp/".sha1($keywordsName).".dat";
      
    if (!file_exists($filePath) || !$useCache) {
      $ac = $this->ahocorasick;
      $ac->setCombineResults(false); // false if each occurrence of a particular word (different positions) to be shown
      $tree = $ac->buildTree($keywords);
      unset($keywords);
      $this->saveToCache($ac, $filePath);
    } else {
      $ac = unserialize(file_get_contents($filePath));
    }

    $results = [];
    foreach($textArray as $key => $text) {
      if($trimText) {
        $text = trim($text);
      }

      $output = $ac->FindAll($text);
      if (!empty($delimiters)) {
          $output = $this->filterNonDelimiterCases($text, $output, $delimiters);
      }

      // sort in descending order of string length of value
      $output = $this->sortByLength($output, $sortByLength);

      $results[$key] = $output;
    }    
    unset($ac);

    if(!$useCache) {
      $this->deleteCache($keywordsName);
    }

    return $results;
  }

  /**
   * Save TreeNodes object to cache
   *
   * @param object $tree
   * @param string  $filePath
   */
  protected function saveToCache($tree, $filePath)
  {
    if(!file_exists($filePath)) {
      file_put_contents($filePath, serialize($tree));
    }
  }

  /**
   * Filter out matches where keyword is not an exact boundary match.
   *
   * If $keyword is 'abc' and $text is 'babcd', then boundary match is not
   * possible. If keyword is 'abc' and $text is 'b abc d', there is a boundary
   * match at position 2.
   *
   * @param string $text
   * @param array  $matches
   * @param array  $delimiters
   *
   * @return array
   */
  protected function filterNonDelimiterCases($text, array $matches, array $delimiters)
  {
      $ret = [];
      foreach ($matches as $key => $match) {

        $startCheck = $this->isExactMatchStart($text, $match['value'], $match['end_pos'] + 1, $delimiters);
        $endCheck = $this->isExactMatchEnd($text, $match['value'], $match['end_pos'] + 1, $delimiters);

        if ($startCheck && $endCheck) {
            $ret[] = $match;
        }
      }

      return $ret;
  }

  /**
   * Check for exact boundary match at the beginning of the keyword.
   *
   * If $keyword is 'abc' and $text is 'abcdef g', there is an exact boundary
   * match in the beginning.
   *
   * @param string $text
   * @param string $keyword
   * @param int    $endPos
   * @param array  $delimiters
   *
   * @return bool
   */
  protected function isExactMatchStart($text, $keyword, $endPos, array $delimiters)
  {
      $startPos = $endPos - strlen($keyword);
      if ($startPos === 0) {
          return true;
      }
      if ($startPos < 0) {
          return false;
      }

      return in_array(substr($text, $startPos - 1, 1), $delimiters);
  }

  /**
   * Check for exact boundary match at the end of the keyword.
   *
   * If $keyword is 'abc' and $text is 'bcabc d', there is an exact boundary
   * match at the end of the keyword.
   *
   * @param string $text
   * @param string $keyword
   * @param int    $endPos
   * @param array  $delmiters
   *
   * @return bool
   */
  protected function isExactMatchEnd($text, $keyword, $endPos, array $delimiters)
  {
      if ($endPos === strlen($text)) {
          return true;
      }
      if ($endPos > strlen($text)) {
          return false;
      }

      return in_array($text[$endPos], $delimiters);
  }

  /**
   * Sort array of matches in descending order of string length of `value` key.
   *
   * @param array $array
   * @param $sortKey = 'asc', 'desc', or false
   *
   * @return array
   */
  protected function sortByLength(array $array, $sortKey)
  {
    if($sortKey == 'desc') {
      usort($array, function ($a, $b) {
          return strlen($b['value']) - strlen($a['value']);
      });

      return $array;
    } elseif($sortKey == 'asc') {
      usort($array, function ($a, $b) {
          return strlen($a['value']) - strlen($b['value']);
      });
      
      return $array;
    }

    return $array;
  }
}