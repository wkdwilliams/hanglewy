<?php

namespace HangLewy;

class HangmanWords
{

  const WORDS = [
    "investigate",
    "describe",
    "mobile"
  ];

  static function generate()
  {
    return self::WORDS[rand(0, count(self::WORDS)-1)];
  }

}

?>
