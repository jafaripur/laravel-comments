<?php

/**
 * Laravel - Commenting system  
 * 
 * @author   Araz Jafaripur <mjafaripur@yahoo.com>
 */

namespace jafaripur\LaravelComments;

abstract class CommentStore {

    /**
     * Format the output of comments for child and parent
     * 
     * @param array $comments array of comment object
     * @param boolean $addChildToParent add child to parent items
     * @return array array of comment object
     */
    public function prepareComments($comments, $addChildToParent = true) {
        if (!$addChildToParent)
        {
            return $comments;
        }
        
        $newComments = [];
        foreach ($comments as $comment) {
            if (is_object($comment))
            {
                $comment = (array)$comment;
            }
            $comment['childs'] = [];
            $newComments[$comment['id']] = $comment;
        }

        // This is the array you get after your database query
        // Order is non important, I put the parents first here simply to make it clearer.
        /*
          $comments = array(
          // some top level (parent == 0)
          1 => array('id' => 1, 'parent' => 0, 'childs' => array()),
          5 => array('id' => 5, 'parent' => 0, 'childs' => array()),
          2 => array('id' => 2, 'parent' => 0, 'childs' => array()),
          10 => array('id' => 10, 'parent' => 0, 'childs' => array()),
          // and some childs
          3 => array('id' => 3, 'parent' => 1, 'childs' => array()),
          6 => array('id' => 6, 'parent' => 2, 'childs' => array()),
          4 => array('id' => 4, 'parent' => 2, 'childs' => array()),
          7 => array('id' => 7, 'parent' => 3, 'childs' => array()),
          8 => array('id' => 8, 'parent' => 7, 'childs' => array()),
          9 => array('id' => 9, 'parent' => 6, 'childs' => array()),
          );
         */

        // now loop your comments list, and everytime you find a child, push it 
        //   into its parent
        foreach ($newComments as $k => &$v) {
            if ($v['parent_id'] != 0) {
                $newComments[$v['parent_id']]['childs'][] = & $v;
            }
        }
        unset($v);

        // delete the childs comments from the top level
        foreach ($newComments as $k => $v) {
            if ($v['parent_id'] != 0) {
                unset($newComments[$k]);
            }
        }
        
        return $newComments;
        
    }
    
    // now we display the comments list, this is a basic recursive function
    public function displayComments($comments, $level = 0) {
      foreach ($comments as $comment) {
        echo str_repeat('-', $level + 1).' comment '.$comment->id."\n";
        if (!empty($comment['childs'])) {
          $this->displayComments($comment['childs'], $level + 1);
        }
      }
    }
    
    /**
     * Read the data from the store.
     *
     * @param string $namespace unique namespace
     * @param integer $threadId id of owner
     * @param integer $approved just read approved or unapproved, (0, 1, null)
     * @param integer $userId id of user, this can be null
     * @param integer $parentId id of parent for comment, this can be null
     * 
     * @return array
     */
    abstract public function read($namespace = null, $threadId = null, $approved = null, $userId = null, $parentId = null);

    /**
     * Write the data into the store.
     *
     * @param string $namespace unique namespace
     * @param integer $threadId id of owner
     * @param string $content content of the comment
     * @param integer $parentId id of parent comment
     * @param integer $approved just read approved or unapproved, (0, 1)
     * @param integer $userId id of user, this can be null
     *
     * @return boolean
     */
    abstract public function save($namespace, $threadId, $content, $parentId = 0, $approved = 0, $userId = null);
    
    /**
     * @param integer $id id of comment
     * 
     * @return boolean
     */
    abstract public function delete($id);
    
    /**
     * @param integer $id id of comment
     * 
     * @return boolean
     */
    abstract public function approve($id);
    
    /**
     * @param integer $id id of comment
     * 
     * @return boolean
     */
    abstract public function unapprove($id);
}
