<?php

class AlphabetHelper
{
	const REQUEST_PARAM_NAME = 'title';
	protected $_current_letter = '';

  function __construct()
  {
    $request = lmbToolkit :: instance()->getRequest();
    if($request->has(self :: REQUEST_PARAM_NAME))
      $this->_current_letter = $request->get(self :: REQUEST_PARAM_NAME);
  }

  function getAlphabet()
  {
    $result = array();
    for($i = 'A'; $i <= 'Z'; $i++)
    {
    	if(1 !== strlen($i))
    	  continue;
      $result[] = $i;
    }
    return $result;
  }

  function getCurrentLetter()
  {
    return $this->_current_letter;
  }
}