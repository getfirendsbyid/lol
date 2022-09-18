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
namespace App\Controller;

use App\Constants\HttpCode;
use App\Exception\BusinessException;
use App\Model\Audio;
use App\Model\Ban;
use App\Model\Heros;
use App\Model\Skin;
use App\Model\Tutorial;
use App\Request\DownloadRequest;
use App\Request\GetRadioListRequest;
use App\Request\HeroListRequest;
use App\Request\SkinListRequest;
use App\Request\TurorialListRequest;
use App\Utils\ApiResponseTrait;
use Hyperf\HttpServer\Contract\ResponseInterface;

class IndexController extends AbstractController
{
    use ApiResponseTrait;

    public function list(HeroListRequest $request)
    {
        $request->validated();
        $name = $request->input('name');

        $all = Heros::list($name);
        return $this->responseSuccess("获取成功",$all);
    }

    public function getSkinList(SkinListRequest $request)
    {
        $request->validated();
        $heroId = $request->input('heroId');
        if (empty($heroId)){
            throw new BusinessException(HttpCode::LogicError,'英雄id不能为空');
        }
        $data = Skin::list($heroId);
        return $this->responseSuccess("获取成功",$data);
    }

    public function getAudioList(GetRadioListRequest $request){
        $request->validated();
        $heroId = $request->input('heroId');
        $skinId = $request->input('skinId');
        $page = $request->input('page');
        $limit = $request->input('limit');

        $data = Audio::list($heroId,$skinId,$page,$limit);
        return $this->responseSuccess("获取成功",$data);

    }

    public function getHeroInfo(SkinListRequest $request)
    {
        $request->validated();
        $heroId = $request->input('heroId');
        $hero = Heros::where('heroId','=',$heroId)->first();
        return $this->responseSuccess("获取成功",$hero);
    }

    public function download(DownloadRequest $request,ResponseInterface $response)
    {
        $id = $request->input('id');
        $type =  $request->input('type');
        if ($type==1){
            $audio = Audio::find($id);
            if (empty($audio)){
                throw new BusinessException(HttpCode::LogicError,'错误数据');
            }
            return $response->download('public/'.$audio['url'], $id.'.wav');
        }elseif ($type==2){
            $audio = Tutorial::find($id);
            if (empty($audio)){
                throw new BusinessException(HttpCode::LogicError,'错误数据');
            }
            return $response->download('public/'.$audio['audioUrl'], $id.'.wav');
        }

    }

    public function banList(SkinListRequest $request)
    {
        $request->validated();
        $heroId = $request->input('heroId');
        $hero = Heros::where('heroId','=',$heroId)->first();
        if (empty($hero)){
            throw new BusinessException(HttpCode::LogicError,'该英雄不存在');
        }
        $data = Ban::where('heroId','=',$hero['id'])->get();
        return $this->responseSuccess("获取成功",$data);

    }

    public function turorialList(TurorialListRequest $request)
    {
        $request->validated();
        $page = $request->input('page');
        $limit = $request->input('limit');
        $data = [];
        $list = Tutorial::take($limit)->skip($limit*($page-1))->get();
        $total = Tutorial::get()->count();
        $data['list'] = $list;
        $data['total'] = $total;
        return $this->responseSuccess("获取成功",$data);
    }

}
