<?php
/**
 * The main system representation class
 *
 * Represents the current state of the system
 * and is stored in the session.
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * The main system representation class
 *
 * Represents the current state of the system
 * and is stored in the session.
 */
class System {
    public function __construct() {
        $this->enigma = new Enigma();

        $this->rotors = [
            ['rotor'=> 1, 'setting'=>'A'],
            ['rotor'=> 2, 'setting'=>'A'],
            ['rotor'=> 3, 'setting'=>'A']
        ];
    }

	/**
	 * Clear the system to an initial state
	 *
	 * This is called by IndexView to indicate no active system
	 */
	public function clear() {
        $this->user = null;
        $this->enigma->clear();
        $this->pressed = null;
        $this->lighted = null;
        $this->encoded = '';
        $this->decoded = '';

	}

    /**
     * Reset the Enigma to the last configured state
     */
	public function reset() {



	    $this->setEnigmaRotors();

        $this->pressed = null;
        $this->lighted = null;
    }

    /**
     * Is the system ready to use? If not, we can't
     * run any of the encoding/decoding pages
     */
	public function ready() {
	    return $this->user !== null;
    }

    /**
     * Get a pointer to the underlying Enigma machine
     * @return Enigma Enigma machine
     */
    public function getEnigma() {
	    return $this->enigma;
    }

	/**
	 * Set a message that appears on a page.
	 *
	 * This is used for error messages.
     * @param $page int Page this message is for
	 * @param $message Message string to set or NULL if none
	 */
	public function setMessage($page, $message) {
		$this->messages[$page] = $message;
	}

	/**
     * Get any current message
     * @param $page int Page this message is for
	 * @return string Current message
	 */
	public function getMessage($page) {
	    if(!empty($this->messages[$page])) {
	        return $this->messages[$page];
        }

		return null;
	}

    /**
     * Clear all messages.
     */
	public function clearMessages() {
	    $this->messages = [];
    }

	/**
	 * Set the user's name
	 * @param $user User name
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
     * Get the user's name
	 * @return string User name
	 */
	public function getUser() {
		return $this->user;
	}

    /**
     * Set the configuration for the rotors.
     * @param $rotors Array of rotor information, with each rotor an array
     * with keys 'rotor' and 'setting'
     */
	public function setRotors($rotors) {
	    $this->rotors = $rotors;
	    $this->reset();
    }

    /**
     * Set the Enigma rotors to match $this->rotors.
     */
    private function setEnigmaRotors() {
	    for($r=1; $r<=3; $r++) {
	        $this->enigma->setRotor($r, $this->rotors[$r-1]['rotor']);
	        $this->enigma->setRotorSetting($r, $this->rotors[$r-1]['setting']);
        }
    }

    /**
     * Handle a keypress on the Enigma keyboard.
     * @param $key Key that was pressed
     */
    public function press($key) {
	    $this->pressed = $key;
	    $this->lighted = $this->enigma->pressed($key);
    }

    /**
     * Return an actively pressed button
     * @return string Pressed button or NULL if none
     */
    public function getPressed() {
	    return $this->pressed;
    }

    /**
     * Return the currently active lighted light
     * @return string Lighted light or NULL if none
     */
    public function getLighted() {
	    return $this->lighted;
    }

    /**
     * Get the decoded content for the Batch page
     * @return string Decoded content
     */
    public function getDecoded()
    {
        return $this->decoded;
    }

    /**
     * Set the decoded content for the Batch page
     * @param string $decoded Content to set
     */
    public function setDecoded($decoded)
    {
        $this->decoded = $decoded;
    }

    /**
     * Get the encoded content for the Batch page
     * @return string Encoded content
     */
    public function getEncoded()
    {
        return $this->encoded;
    }

    /**
     * Set the encoded content for the Batch page
     * @param string $encoded Content to set
     */
    public function setEncoded($encoded)
    {
        $this->encoded = $encoded;
    }

	private $messages = [];	    ///< Any error message for pages?
	private $user = null;		///< User name
    private $enigma;            ///< The Enigma machine

    private $rotors;            ///< Last setting of the rotors

    private $pressed = null;    ///< Key that has been pressed
    private $lighted = null;    ///< Letter that has been lighted

    private $decoded = '';      ///< Batch encoded content
    private $encoded = '';      ///< Batch decoded content

}