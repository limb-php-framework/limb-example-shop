<?php

class AlphabetHelper
{
  static function getAlphabet()
  {
    $current_letter = '';
    $request = lmbToolkit :: instance()->getRequest();
    if($request->hasAttribute('letter'))
      $current_letter = $request->get('letter');

    return self :: _getEnglishLetters($current_letter);
  }

  function _getEnglishLetters($current_letter = '')
  {
    $result = array();
    for($i = 'A'; $i <= 'Z'; $i++)
    {
      if(strlen($i) == 2)
        break;

      $result[$i]['letter'] = $i;
      if ($i == $current_letter)
        $result[$i]['current'] = true;
    }

    return $result;
  }
}

?>
