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

    public static function getHeroInfo($id,$skinId,$page,$limit)
    {
        $heroData  =  Heros::find($id);
        $skinModel = Skin::query();
        if (empty($skinId)){
            $skinModel->where('hero_id','=',$id);
        }else{
            $skinModel->where('id','=',$skinId);
        }
        $skin =  $skinModel->select(['id','skin_name','url'])->get();
        $heroData['skin'] = $skin;
        $skinIds = [];
        foreach ($skin as $item){
            array_push($skinIds,$item['id']);
        }
        $audio = Audio::whereIn('skin_id',$skinIds)
            ->take($limit)
            ->skip($limit*($page-1))
            ->select()
            ->get();
        $heroData['audio'] = $audio;
        $audioCount = Audio::whereIn('skin_id',$skinIds)
            ->select('id')
            ->get()->count();
        $heroData['count'] =$audioCount;
        return $heroData;
    }


}
