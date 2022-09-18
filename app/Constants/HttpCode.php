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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class HttpCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;

    public const OK = 200;

    public const UNAUTHORIZED = 401;

    public const BAD_REQUEST = 402;

    public const FAIL = 403;

    public const  LogicError = 1000; //业务逻辑错误

    // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
    public const IllegalToken = 50008; //非法token

    public const OtherClientsLogged = 50012; //其他账户登陆

    public const TokenExpired = 50014; //token过期

    public const UNPROCESSABLE_ENTITY = 405; //token过期




}
