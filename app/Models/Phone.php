<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phone extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_main';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    use HasFactory;


    public function getNameAttribute(): string
    {
        return $this->title;
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }
}
