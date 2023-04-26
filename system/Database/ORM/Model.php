<?php

namespace System\Database\ORM;

use System\Database\Traits;

abstract class Model
{
    use Traits\HasAttributes, Traits\HasCRUD, Traits\HasRelation, Traits\HasMethodCaller, Traits\HasRelation, Traits\HasQueryBuilder, Traits\HasSoftDelete;

}