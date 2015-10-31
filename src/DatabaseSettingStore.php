<?php

/**
 * Laravel - Commenting system
 * 
 * @author   Araz Jafaripur <mjafaripur@yahoo.com>
 */

namespace jafaripur\LaravelComments;

use Illuminate\Database\Connection;

class DatabaseSettingStore extends CommentStore {

    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * The table to query from.
     *
     * @var string
     */
    protected $table;

    /**
     * Any query constraints that should be applied.
     *
     * @var Closure|null
     */
    protected $queryConstraint;

    /**
     * @param \Illuminate\Database\Connection $connection
     * @param string                         $table
     */
    public function __construct(Connection $connection, $table = null) {
        $this->connection = $connection;
        $this->table = $table ? : 'comments';
    }

    /**
     * Set the table to query from.
     *
     * @param string $table
     */
    public function setTable($table) {
        $this->table = $table;
    }

    /**
     * Set the query constraint.
     *
     * @param Closure $callback
     */
    public function setConstraint(\Closure $callback) {
        $this->comments = [];
        $this->queryConstraint = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function save($namespace, $threadId, $content, $parentId = 0, $approved = 0, $userId = null) {
        return $this->newQuery(true)->insert([
                    'namespace' => $namespace,
                    'thread_id' => (int) $threadId,
                    'approved' => (int) $approved,
                    'user_id' => (int) $userId,
                    'parent_id' => (int) $parentId,
                    'content' => $content,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function read($namespace = null, $threadId = null, $approved = null, $userId = null, $parentId = null) {
        $query = $this->newQuery();

        $query = $this->addWhereQuery($query, 'namespace', '=', $namespace);
        $query = $this->addWhereQuery($query, 'thread_id', '=', $threadId);
        $query = $this->addWhereQuery($query, 'approved', '=', $approved);
        $query = $this->addWhereQuery($query, 'user_id', '=', $userId);
        $query = $this->addWhereQuery($query, 'parent_id', '=', $parentId);

        $comments = $query->get();

        if (method_exists($comments, 'toArray')) {
            $comments = $comments->toArray();
        }

        return $this->prepareComments($comments);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id) {
        return $this->newQuery()
                        ->where('id', '=', $id)
                        ->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function approve($id) {
        return $this->newQuery()
                ->where('id', '=', $id)
                ->update([
                    'approved' => 1
                ]);
    }
    
     /**
     * {@inheritdoc}
     */
    public function unapprove($id) {
        return $this->newQuery()
                ->where('id', '=', $id)
                ->update([
                    'approved' => 0
                ]);
    }

    /**
     * Create a new query builder instance.
     *
     * @param  $insert  boolean  Whether the query is an insert or not.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newQuery($insert = false) {
        $query = $this->connection->table($this->table);

        if ($this->queryConstraint !== null) {
            $callback = $this->queryConstraint;
            $callback($query, $insert);
        }

        return $query;
    }

    /**
     * 
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $name name of the field for adding in condition
     * @param string $operator the condition like an "=", ">", ...
     * @param string $value value of the field for adding to condition
     * @return \Illuminate\Database\Query\Builder
     */
    protected function addWhereQuery($query, $name, $operator, $value) {
        if (!empty($value)) {
            return $query->where($name, $operator, $value);
        }

        return $query;
    }

}
