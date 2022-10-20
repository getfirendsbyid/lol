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


class Audio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qy_gs_audio';
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





}
