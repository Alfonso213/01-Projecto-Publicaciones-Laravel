<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\returnArgument;

class Post extends Model
{
    protected $fillable = ['body',];

    protected $with = ['user'];

    public function user()
    {
        return $this-> belongsTo(User::class);
    }
}
