<?php

namespace App\Libraries;

use Illuminate\Hashing\HashServiceProvider;
use App\Libraries\SHA1Hasher;

class SHA1HashServiceProvider extends HashServiceProvider {

    public function register() {
        $this->app->singleton('hash', function () {
            return new SHA1Hasher();
        });

    }
}