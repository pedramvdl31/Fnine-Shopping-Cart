<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    static public function dump($results) {
        if(isset($results)) {
            echo '<pre>';
            print_r($results);
            echo '</pre>';
        }

        return false;
    }
    
}
