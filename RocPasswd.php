<?php

/**
 * Password Generation class
 *
 * @author Alex Lombry <alex.lombry@gmail.com>
 * @version 1.0
 */
class RocPasswd {

    /**
     * Password
     * @var String
     */
    private $password;
    /**
     * Not encrypted password
     * @var String
     */
    private $clearPassword;
    /**
     * Salt for password
     * Recommand to create salf from config File and NEVER CHANGE IT
     * @var String
     */
    private $salt = ".:'YourEncryptionKeyGoesHere':.";

    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * Create a new password with encryption
     * @return String
     */
    public function create() {
        return $this->_securePasswd();
    }

    /**
     * User modify password
     * @param  String $password
     * @return String
     */
    public function userCreate($password) {
        if (isset($password) and $password != "") {
            return $this->_passwdEncode($password);
        }
        return false;
    }

    /**
     * Read a password for login attemps
     * @param  String $passwd
     * @return String
     */
    public function read($passwd) {
        return $this->_passwdEncode($passwd);
    }

    /**
     * Generate not encrypted password with 16 char
     * @return String
     */
    private function _generatePasswd() {
        $length = 16;
        $this->password = "";
        $authorizedString = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ./'!";
        $maxlength = strlen($authorizedString);

        if ($length > $maxlength) {
            $length = $maxlength;
        }

        $i = 0;

        while ($i < $length) {
            $char = substr($authorizedString, mt_rand(0, $maxlength - 1), 1);
            if (!strstr($this->password, $char)) {
                $this->password .= $char;
                $i++;
            }
        }

        return $this->password;
    }

    /**
     * Encrypt a password for account creation or modification
     * @return Array
     */
    private function _securePasswd() {
        $this->_generatePasswd();
        $this->clearPassword = $this->password;
        $this->password = $this->_passwdEncode($this->password);

        return array('clear' => $this->clearPassword, 'real' => $this->password);
    }

    /**
     * Encrypt password
     * @param  String $password
     * @return String
     */
    private function _passwdEncode($password) {
        return strrev(md5(sha1(base64_encode($password.$this->salt))));
    }

}

