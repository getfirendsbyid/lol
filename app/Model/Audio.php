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



use App\Constants\HttpCode;
use App\Exception\BusinessException;
use PhpCsFixer\DocBlock\Tag;
use function PHPUnit\Framework\throwException;


class Audio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lol_audio';
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


    public static function list($heroId,$skinId,$page,$limit)
    {
        $hero = Heros::where('heroId','=',$heroId)->first();
        if (empty($hero)){
            throw new BusinessException(HttpCode::LogicError,'该英雄不存在');
        }
        $where = [
            ['hero_id',"=",$hero['id']],
            ['skin_id',"=",$skinId]
        ];
        $data = [];
         $res = Audio::where($where)->take($limit)->skip($limit*($page-1))->get();
         $total = Audio::where($where)->get()->count();
         $data['list'] = $res;
         $data['total'] = $total;
         return $data;
    }





}
