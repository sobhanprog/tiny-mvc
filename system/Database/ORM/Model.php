<?php

namespace System\Database\ORM;

use System\Database\Traits;

abstract class Model
{
    use Traits\HasAttributes, Traits\HasCRUD, Traits\HasRelation, Traits\HasMethodCaller, Traits\HasRelation, Traits\HasQueryBuilder, Traits\HasSoftDelete;

    protected $table;
    protected $fillable = [];
    protected $hidden = [];
    protected $casts = [];
    protected $primaryKey = 'id';
    protected $createdAt = 'create_at';
    protected $updatedAt = 'updated_at';
    protected $deletedAt = null;
    protected $collection = [];

}