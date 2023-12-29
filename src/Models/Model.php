<?php

namespace UnknowSk\Core\Models;

class User extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use \Staudenmeir\EloquentHasManyDeep\HasTableAlias;
    use \Znck\Eloquent\Traits\BelongsToThrough;
}
