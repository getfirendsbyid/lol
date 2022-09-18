<?php

declare(strict_types=1);
/**
 * This file is part of api.
 *
 * @link     https://www.qqdeveloper.io
 * @document https://www.qqdeveloper.wiki
 * @contact  2665274677@qq.com
 * @license  Apache2.0
 */
namespace App\Exception\Handler;

use App\Constants\HttpCode;
use App\Exception\BusinessException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 自定义表单验证异常处理器.
 *
 * Class FromValidateExceptionHandler
 */
class BusinessExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof BusinessException) {
            // 格式化异常数据格式
            $data = json_encode([
                'err_code' => $throwable->getCode(),
                // 获取异常信息
                'err_msg' => $throwable->getMessage(),
                'data' => [],
            ]);
            $this->stopPropagation();
            return $response->withStatus(200)->withBody(new SwooleStream($data));
        }
        return $response;
    }
    // 异常处理器处理该异常
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}