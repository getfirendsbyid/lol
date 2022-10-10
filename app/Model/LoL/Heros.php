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


class Heros extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qy_lol_heros';
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


    public static function list($name)
    {
        if (empty($name)){
            $where = [];
        }else{
            $where = [['name',"like",'%'.$name.'%']];
        }
        return Heros::where($where)->get();
    }

    public static function homeList()
    {
        $data =  Heros::limit(12)->get();
        foreach ($data as $list=>$item){
            $item['imgUrl'] =  'https://game.gtimg.cn/images/lol/act/img/skinloading/'.$item['heroId'].'000.jpg';
        }
        return $data;
    }




}
