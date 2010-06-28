<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id$
 * @package    wact
 */

class lmbMacroTemplateHighlightHandler
{
  protected $html = '';
  protected $current_tag = '';
  protected $self_closing_tag = false;
  protected $template_path_history = array();

  protected function _writeAttributes($attributes)
  {
    if(!is_array($attributes))
      return;

    foreach($attributes as $name => $value)
      $this->html .= ' ' . $name . '="' . $value . '"';
  }

  function openHandler($parser, $name, $attrs)
  {
    if($this->self_closing_tag)
      $this->html .= '&gt;';

    $this->self_closing_tag = true;

    $this->html .= '&lt;<span class="html_tag">' . $name . '</span>';

    $this->_writeAttributes($attrs);
  }

  function closeHandler($parser, $name)
  {
    if($this->self_closing_tag)
    {
      $this->html .= '/&gt;';
      $this->self_closing_tag = false;
      return;
    }

    $this->html .= '&lt;/<span class="html_tag">' . $name . '</span>&gt;';
  }

  function dataHandler($parser, $data)
  {
    if($this->self_closing_tag)
    {
      $this->self_closing_tag = false;
      $this->html .= '&gt;';
    }

    $data = str_replace("\t", '  ', $data);
    $data = preg_replace('~(\{(\$|\^|#)[^\}]+\})~', "<span class='expression'>\\1</span>", $data);
    $data = $this->_hightlightMacroTagsAndAttributes($data);
    
    $this->html .= $data;
  }

  function escapeHandler($parser, $data)
  {
    $this->html .= '<span class="comment">&lt;!' . $data . '&gt;</span>';
  }

  function getHtml()
  {
    $lines = preg_split( "#\r\n|\r|\n#", $this->html);

    $content = '';
    $max = sizeof($lines);
    $digits = strlen("{$max}");

    for($i=0; $i < $max; $i++)
    {
      $j = $i + 1;
      $content .= "<span class='line_number'>{$j}" . str_repeat('&nbsp;', $digits - strlen("{$j}")) . "</span> " .  $lines[$i] . "\n";
    }

    return $content;
  }
  
  function processPHPCode($parser, $target, $data)
  {
    $this->html .= "\n" . '&lt;?' . $target . "\n" . $data . "\n" . "?&gt;";
  }

  protected function _hightlightMacroTagsAndAttributes($data)
  {
    preg_match_all('/({?[^{]*){{([^}}]*)}}([^{]*)/', $data, $matches, PREG_SET_ORDER);
    
    if(!count($matches))
      return $data;
    
    $result = "";
    foreach($matches as $match)
    {
      $tag_content = $match[2];
      preg_match('/([^\s]*)(.*)/', $tag_content, $tag_matches);

      $tag_name = $tag_matches[1];
      $result .= $match[1] . '<span class="macro_tag">{{' . $tag_name;
      
      $attributes_strings = explode(' ', $tag_matches[2]);
      foreach($attributes_strings as $item)
      {
        if(strpos($item, '=') === false)
          continue;
        
        $attr = explode('=', $item);
        $attr_name = $attr[0];
        $attr_value = $attr[1];        
        $result .= ' <span class="macro_attr_name">' . $attr_name . '</span>=';

        if(in_array($tag_name, array('wrap', 'include', 'insert')) && in_array($attr_name, array('file', 'with')))
          $result .= '<span class="template_path">' . $attr_value . '</span>';
        else
          $result .= '<span class="macro_attr">' . $attr_value . '</span>';
      }
      
      $result .= '}}</span>' . $match[3];
    }
    
    return $result;
  }
}
?>