<?php declare(strict_types = 1);

namespace jotaa\franky_web\helpers\http;

use WeakReference;

class Response
{
    private int $code;
    private array $response;
    private WeakReference $logger;

    public function __construct(int $code, array $response, $logger)
    {
        $this->code = $code;
        $this->response = $response;
        $this->logger = WeakReference::create($logger);
    }

    public function answerJson(int $options)
    {
        try {
            $json = json_encode(
                $this->response,
                $options
            );

            if (ob_get_length() > 0) {
                ob_end_clean();
            }
            header('Content-type:application/json;charset=utf-8');
            http_response_code($this->code);
            echo $json;
        } catch (\Exception $e) {
            $this->logger->get()->error($e);
            $this->answerJson(
                500,
                [
                    "error" => $e->getMessage(),
                ],
                JSON_HEX_QUOT|JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE
            );
        }
    }
}
