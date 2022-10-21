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
namespace App\Model\GenShin;



use App\Constants\HttpCode;
use App\Exception\BusinessException;
use App\Model\Model;


class Heros extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qy_gs_heros';
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


    public static function getHeroList($page,$limit)
    {
        return Heros::take($limit)->skip(($page-1)*$limit)->get();
    }

    public static function info($id)
    {
        $hero = Heros::find($id);

        $language = Language::all();


        foreach ($language as $key=>$item){
            $where = [
                ['qy_gs_language.id','=',$item['id']],
                ['qy_gs_heros.id','=',$id]
            ];
            $audio = Audio::leftJoin('qy_gs_heros','qy_gs_heros.id','=','qy_gs_audio.hero_id')
                ->leftJoin('qy_gs_language','qy_gs_language.id','qy_gs_audio.language_id')
                ->where($where)
                ->get();
            $data[$key]['info'] = $audio;
            $data[$key]['language'] = $item['name'];
        }
        $hero['audio'] = $data;
        return $hero;

    }


}
