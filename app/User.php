<?php

/**
 * User Model File.
 *
 * PHP version 7
 *
 * @category Model
 * @package  TableSync_API
 * @author   Mubaris NK <hello@mubaris.com>
 * @license  https://github.com/mubaris/ts-backend/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/mubaris/ts-backend/
 */

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * User Model Class.
 *
 * PHP version 7
 *
 * @category Model
 * @package  TableSync_API
 * @author   Mubaris NK <hello@mubaris.com>
 * @license  https://github.com/mubaris/ts-backend/blob/master/LICENSE.md BSD Licence
 * @link     https://github.com/mubaris/ts-backend/
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
