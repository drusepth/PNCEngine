<?php

  class PNCEngine {
    public function __construct($script_file) {
      $this->script_location = $script_file;
      $this->script_state    = 0;

      // Load in neccessary JS/CSS
      $this->initialize();
    }

    // Begin the game
    public function play() {
      if (!$this->validate_script()) {
        echo 'Cannot play: syntax errors in script';
      }

      $this->create_game();
    }

    /* Private functions */

    // Validate the script; ensure there are no syntax errors
    private function validate_script() {
      return true;
    }

    // Load script into $this->script
    private function read_script() {
      $this->script = file_get_contents($this->script_location);
    }

    // Initialize PNC
    private function initialize() {
      echo '<link rel="stylesheet" type="text/css" href="pnce.css" />';
      echo '<script type="text/javascript" src="jquery-1.7.1.min.js"></script>';
      echo '<script type="text/javascript" src="pnce.js"></script>';
    }

    // Reset script state to the beginning
    private function reset_script() {
      $this->script_state = 0;
    }

    // Write all neccessary HTML and JS to the browser
    private function create_game() {
      $this->read_script();
      $this->reset_script();

      $script_contents = explode("\n", $this->script);
      for ($i = 0; $i < count($script_contents); $i++) {
        $state = $script_contents[$i];

        // Ignore empty lines
        if ($state == "") {
          continue;
        }

        list($command, $parameters) = explode('|', $state);

        // Trim whitespace so scripts can look nice
        $command = strtolower(trim($command));

        // Grab the command's template
        $template = "<div id='pncstate_$i' class='state' style='" .
                    "z-index: " . (1000 - $i) . "'>" .
                    file_get_contents("templates/$command.tpl") .
                    "</div>";

        // Substitute in parameters
        switch ($command) {
          case 'title':
            $template = str_replace('{{title}}', $parameters, $template);
            break;
        }

        // Print this state out
        echo $template;
      }
    }

  }

  $engine = new PNCEngine('example.script');

  $engine->play();

?>
