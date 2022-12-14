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
namespace App\Model;



use App\Model\LoL\Heros;


class Games extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qy_games';
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


    public static function homeList($limit=8)
    {
        $games =  Games::where('show','=',1)->get();
        foreach ($games as  $item){
            $heroWhere = [
                ['show','=',1],
                ['game_id','=',$item['id']]
            ];
            $heroes = Heros::where($heroWhere)->take($limit)->get();
            $item['hero'] = $heroes;
        }
        return $games;
    }





}
