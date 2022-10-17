<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Model\LoL;



use App\Model\Model;


class Tutorial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qy_lol_tutorial';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];




    public static function list($id,$page,$limit)
    {
        return Tutorial::where('map_id','=',$id)->take($limit)->skip(($page-1)*$limit)->get();
    }





}
