<?php

/**
 * Laravel - Commenting system  
 * 
 * @author   Araz Jafaripur <mjafaripur@yahoo.com>
 */

namespace jafaripur\LaravelComments;

use Illuminate\Support\Manager;
use DB;

class CommentManager extends Manager {

    public function getDefaultDriver() {
        return $this->getConfig('comments.store');
    }

    public function createDatabaseDriver() {
        $connection = DB::connection($this->getConfig('comments.connection'));
        $table = $this->getConfig('comments.table');

        return new DatabaseSettingStore($connection, $table);
    }

    protected function getConfig($key) {
        return $this->app['config']->get($key);
    }

}
