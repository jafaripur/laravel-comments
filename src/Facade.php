<?php

/**
 * Laravel - Commenting system
 * 
 * @author   Araz Jafaripur <mjafaripur@yahoo.com>
 */

namespace jafaripur\LaravelComments;

class Facade extends \Illuminate\Support\Facades\Facade {

    protected static function getFacadeAccessor() {
        return 'jafaripur\LaravelComments\CommentManager';
    }

}
