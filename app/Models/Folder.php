<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable =
        [
            'user_id',
            'file_id',
            'folder_id',
            'name',
            'path',
            'type',
            'is_hidden'
        ];
  

        public function children()
        {
            return $this->hasMany(Folder::class, 'folder_id');
        }
        
        public function parent()
        {
            return $this->belongsTo(Folder::class, 'folder_id');
        }

}
