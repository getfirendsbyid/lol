<?php

namespace App\Utils;


use App\Constants\HttpCode;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use Psr\Http\Message\ResponseInterface;

trait ApiResponseTrait
{
    private $httpCode = HttpCode::OK;
    private $headers = [
        'Author' => 'Colorado'
    ];

    private $errorCode = 100000;
    private $errorMsg = '';

    protected $response;

    /**
     * 成功响应
     * @param mixed $data
     * @return ResponseInterface
     */
    public function success($data): ResponseInterface
    {
        return $this->respond($data);
    }

    /**
     * 错误返回
     * @param string $err_msg     错误信息
     * @param int    $err_code    错误业务码
     * @param array  $data        额外返回的数据
     * @return ResponseInterface
     */
    public function fail(string $err_msg = '', int $err_code = 200000, array $data = []): ResponseInterface
    {
        return $this->setHttpCode($this->httpCode == HttpCode::OK ? HttpCode::BAD_REQUEST : $this->httpCode)
            ->respond([
                'err_code' => $err_code ?? $this->errorCode,
                'err_msg' => $err_msg ?? $this->errorMsg,
                'data' => $data
            ]);
    }

    /**
     * 设置http返回码
     * @param int $code    http返回码
     * @return $this
     */
    final public function setHttpCode(int $code = HttpCode::OK): self
    {
        $this->httpCode = $code;
        return $this;
    }

    /**
     * 设置返回头部header值
     * @param string $key
     * @param        $value
     * @return $this
     */
    public function addHttpHeader(string $key, $value): self
    {
        $this->headers += [$key => $value];
        return $this;
    }

    /**
     * 批量设置头部返回
     * @param array $headers    header数组：[key1 => value1, key2 => value2]
     * @return $this
     */
    public function addHttpHeaders(array $headers = []): self
    {
        $this->headers += $headers;
        return $this;
    }

    /**
     * @param null|array|Arrayable|Jsonable|string $response
     * @return ResponseInterface
     */
    private function respond($response): ResponseInterface
    {
        if (is_string($response)) {
            return $this->response()->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream($response));
        }

        if (is_array($response) || $response instanceof Arrayable) {
            return $this->response()
                ->withAddedHeader('content-type', 'application/json')
                ->withBody(new SwooleStream(Json::encode($response)));
        }

        if ($response instanceof Jsonable) {
            return $this->response()
                ->withAddedHeader('content-type', 'application/json')
                ->withBody(new SwooleStream((string)$response));
        }

        return $this->response()->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream((string)$response));
    }

    /**
     * @return mixed|ResponseInterface|null
     */
    protected function response(): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        foreach ($this->headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }
        return $response;
    }

    /**
     * @param $token
     * @return ResponseInterface
     */
    protected function responseSuccess($msg,$data=[]): ResponseInterface
    {
        $res = [
            'code'=>200,
            'msg'=>$msg,
            'data'=>$data,
        ];
        return $this->success($res);
    }
}