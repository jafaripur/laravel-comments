<?php

use jafaripur\LaravelComments\DatabaseSettingStore;

abstract class AbstractFunctionalTest extends PHPUnit_Framework_TestCase {

    protected abstract function createStore(array $data = array());
    protected abstract function getDbData();
    
    /** @test */
    public function get_comment_list() {
        $data = $this->getDbData();
        $store = $this->createStore($data);
        $comments = $store->read();
        
        $this->assertEquals(count($data), count($comments));
        
        echo count($data)." Comment successfully inserted\n";
        
        
        //$this->assertStoreEquals($store, array('foo' => array('bar' => 'baz')));
    }
    
    /** @test */
    public function delete_comment() {
        $data = $this->getDbData();
        $store = $this->createStore($data);
        $comments = $store->read();
        $count = 0;
        foreach($comments as $comment)
        {
            $store->delete($comment['id']);
            $count++;
        }
        
        $this->assertEquals($count, count($comments));
        
        echo "{$count} Comment successfully deleted.\n";
        
        //$this->assertStoreEquals($store, array('foo' => array('bar' => 'baz')));
    }
    
    /** @test */
    public function approve_unapprove_comment() {
        $data = $this->getDbData();
        $store = $this->createStore($data);
        $comments = $store->read();
        $approved = 0;
        $unapproved = 0;
        foreach($comments as $comment)
        {
            if ($comment['approved'] == '1')
            {
                $store->unapprove($comment['id']);
                $unapproved++;
            }
            elseif ($comment['approved'] == '0')
            {
                $store->approve($comment['id']);
                $approved++;
            }
        }
        
        $this->assertEquals($approved + $unapproved, count($comments));
        
        echo "{$approved} Comment approved and {$unapproved} comment unapproved.\n";
        
        //$this->assertStoreEquals($store, array('foo' => array('bar' => 'baz')));
    }
}
