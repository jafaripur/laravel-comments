<?php

class DatabaseTest extends AbstractFunctionalTest {

    public function setUp() {
        $this->container = new \Illuminate\Container\Container;
        $this->capsule = new \Illuminate\Database\Capsule\Manager($this->container);
        $this->capsule->setAsGlobal();
        $this->container['db'] = $this->capsule;
        $this->capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $this->capsule->schema()->create('comments', function($table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->default(0)->index();
            $table->integer('user_id')->nullable()->default(0)->index();
            $table->integer('thread_id')->index();
            $table->string('namespace')->index();
            $table->text('content');
            $table->integer('point')->default(0);
            $table->boolean('approved')->default(0);
        });
    }

    public function tearDown() {
        $this->capsule->schema()->drop('comments');
        unset($this->capsule);
        unset($this->container);
    }

    /**
     * 
     * @param array $data
     * @return \jafaripur\LaravelComments\DatabaseSettingStore
     */
    protected function createStore(array $data = []) {
        if ($data) {
            $store = $this->createStore();
            foreach($data as $item)
            {
                $store->save(function($fields) use ($item){
                    $fields->namespace = $item['namespace'];
                    $fields->threadId = $item['thread_id'];
                    $fields->content = $item['content'];
                    $fields->parentId = $item['parent_id'];
                    $fields->approved = $item['approved'];
                    $fields->userId = $item['user_id'];
                });
            }
            unset($store);
        }

        return new \jafaripur\LaravelComments\DatabaseSettingStore(
                $this->capsule->getConnection()
        );
    }

    protected function getDbData()
    {
        return [
            [
                'parent_id' => '0',
                'user_id' => '0',
                'thread_id' => '1',
                'namespace' => 'post',
                'content' => 'just test for comment 1',
                'approved' => rand(0, 1),
                'point' => rand(0, 50),
            ],
            [
                'parent_id' => '0',
                'user_id' => '0',
                'thread_id' => '1',
                'namespace' => 'post',
                'content' => 'just test for comment 2',
                'approved' => rand(0, 1),
                'point' => rand(0, 50),
            ],
            [
                'parent_id' => '0',
                'user_id' => '0',
                'thread_id' => '1',
                'namespace' => 'post',
                'content' => 'just test for comment 3',
                'approved' => rand(0, 1),
                'point' => rand(0, 50),
            ],
            [
                'parent_id' => '0',
                'user_id' => '0',
                'thread_id' => '1',
                'namespace' => 'post',
                'content' => 'just test for comment 4',
                'approved' => rand(0, 1),
                'point' => rand(0, 50),
            ],
            [
                'parent_id' => '0',
                'user_id' => '0',
                'thread_id' => '1',
                'namespace' => 'post',
                'content' => 'just test for comment 5',
                'approved' => rand(0, 1),
                'point' => rand(0, 50),
            ],
        ];
    }
}
