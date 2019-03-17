<?php

namespace HangLewy;

use HangLewy\HangmanASCII;
use HangLewy\HangmanWords;

class Hangman
{
    private $attempts;
    private $word;
    private $guessedLetters;
    private $hasWon;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['reset'])) {
            $this->reset();
        }

        $this->update();  //Construct the class

        if (isset($_POST['guess'])) {
            if ($_POST['letter'] != "") {
                if (in_array($_POST['letter'], str_split($this->word))) {
                    $_SESSION['guessedLetters'][] = $_POST['letter'];
                } else {
                    $_SESSION['attempts']++;
                }
            } elseif ($_POST['word'] != "") {
                if ($_POST['word'] == $this->word) {
                    $_SESSION['win'] = true;
                } else {
                    $_SESSION['attempts']++;
                }
            }

            $this->update();  //Update the class with new values
        }
    }

    private function reset()
    {
        session_unset();
    }

    private function update()
    {
        if (isset($_SESSION['attempts'])) {
            $this->attempts = $_SESSION['attempts'];
        } else {
            $this->attempts = 0;
        }

        if (!isset($_SESSION['word'])) {
            $_SESSION['word'] = $this->word = HangmanWords::generate();
        }
        $this->word = $_SESSION['word'];

        if (isset($_SESSION['guessedLetters'])) {
            $this->guessedLetters = $_SESSION['guessedLetters'];
        } else {
            $this->guessedLetters = $_SESSION['guessedLetters'] = [];
        }

        if (isset($_SESSION['win'])) {
            $this->hasWon = $_SESSION['win'];
        } else {
            $this->hasWon = $_SESSION['win'] = false;
        }
    }

    public function draw()
    {
        if ($this->attempts != 0) {
            echo
        "<pre>" .
          HangmanASCII::ASCII[count(HangmanASCII::ASCII)-$this->attempts-1] .
        "</pre><br />";
        }

        echo "<p>Attempts: <b style='color: red'>".$this->attempts."/".(count(HangmanASCII::ASCII)-1)."</b></p>";
        echo "Secret word: ";

        if ($this->hasWon) {
            echo $this->word;
        } else {
            foreach (str_split($this->word) as $key => $letter) {
                if (in_array($letter, $this->guessedLetters)) {
                    echo $letter;
                } else {
                    echo "-";
                }
            }
        }

        if ($this->attempts == count(HangmanASCII::ASCII)-1) {
            echo "
        <h2>Game Over</h2>
        <form method='post' style='display: inline;'>
        <input type='submit' name='reset' value='New Game' style='display: inline;' />
        </form>
        ";

            return;
        }
        if ($this->hasWon == true) {
            echo "
        <h2>Win!</h2>
        <form method='post' style='display: inline;'>
        <input type='submit' name='reset' value='New Game' style='display: inline;' />
        </form>
        ";
        } else {
            echo "
        <div style='margin-top: 10px;'><form method='post' style='display: inline;'>
        <input type='text' name='letter' placeholder='Guess a letter...' /><br />
        <input type='text' name='word' placeholder='Guess the word...' /><br />

        <input type='submit' name='guess' value='Guess' />
        </form>
        <form method='post' style='display: inline;'>
        <input type='submit' name='reset' value='New Game' style='display: inline;' />
        </form>
        </div>
      ";
        }
    }
}
