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
        $skin = Skin::where('hero_id','=',$id)->select(['id','skin_name','url'])->get();
        $defaultSkin = [
            ['id'=>0,'skin_name'=>'全部','url'=>'']
        ];
        $skinIds = [];
        foreach ($skin as $item){
            array_push($defaultSkin,$item);
            array_push($skinIds,$item['id']);
        }
        $heroData['skin'] = $defaultSkin;

        $audioModel = Audio::query();
        if ($skinId==0){
            $audioModel->whereIn('skin_id',$skinIds)->where('qy_lol_audio.hero_id','=',$id);
        }else{
            $audioModel->where('skin_id','=',$skinId)->where('qy_lol_audio.hero_id','=',$id);
        }
        $audio = $audioModel->take($limit)->skip($limit*($page-1))
            ->leftJoin('qy_lol_skin','qy_lol_skin.id','=','qy_lol_audio.skin_id')
            ->select('qy_lol_audio.id','qy_lol_audio.hero_id','qy_lol_audio.url','qy_lol_audio.name','skin_name')
            ->get();
        $heroData['audio'] = $audio;
        $audioCount = Audio::whereIn('skin_id',$skinIds)
            ->select('id')
            ->get()->count();
        $heroData['count'] = $audioCount;
        $heroData['pageCount'] =ceil($audioCount/$limit);
        return $heroData;
    }

    public static function heroList($name='',$page,$limit)
    {
        $heroModel = Heros::query();
        if (!empty($name)){
            $heroModel->where('name','like','%'.$name.'%')
                ->orWhere('title','like','%'.$name.'%');
        }
        $data = $heroModel->take($limit)
            ->skip(($page-1)*$limit)
            ->select('id','heroId','name','alias','title','selectAudio','banAudio')
            ->get();
        return $data;
    }


}
