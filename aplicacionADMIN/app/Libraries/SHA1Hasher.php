<?php

namespace App\Libraries;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class SHA1Hasher implements HasherContract {


    /**
     * Get information about the given hashed value.
     *
     * @param  string  $hashedValue
     * @return array
     */
    public function info($hashedValue) {
        return [];
    }


    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @return array   $options
     * @return string
     */
    public function make($value, array $options = array()) {

        if(isset($options['salt'])){
            $salt = $options['salt'];
        }else{
            // Genero salt de 8 caracteres aleatorio
            $salt = random_bytes(8);
            //$salt = mcrypt_create_iv(8, MCRYPT_DEV_URANDOM);
        }

        $hash = hash_pbkdf2("sha1", $value, $salt, 1000, 40);

        return "pbkdf2:sha1:1000$" . $salt . "$" . $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = array()) {
        return $this->make($value, $options) === $hashedValue;
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = array()) {
        return false;
    }

}