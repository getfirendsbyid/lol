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



use PhpCsFixer\DocBlock\Tag;
use function PHPUnit\Framework\throwException;


class Ban extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lol_ban';
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
        return Ban::where($where)->get();
    }





}
