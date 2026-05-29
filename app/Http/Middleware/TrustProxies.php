<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TrustProxies extends Middleware
{
    /**
     * Tin cậy tất cả proxy/CDN (Cloudflare/Nginx…)
     * Nếu muốn chặt hơn, đổi '*' thành mảng IP/CIDR proxy của bạn.
     */
    protected $proxies = '*';

    /**
     * Bitmask header X-Forwarded-* cần tin cậy.
     * Ta không dùng HEADER_X_FORWARDED_ALL để tránh lỗi "undefined constant".
     */
    protected $headers;

    public function __construct()
    {
        // OR các hằng số có tồn tại (mỗi phiên bản Symfony khác nhau có thể thiếu 1-2 cái)
        $this->headers =
            (defined(SymfonyRequest::class.'::HEADER_X_FORWARDED_FOR')   ? SymfonyRequest::HEADER_X_FORWARDED_FOR   : 0)
          | (defined(SymfonyRequest::class.'::HEADER_X_FORWARDED_HOST')  ? SymfonyRequest::HEADER_X_FORWARDED_HOST  : 0)
          | (defined(SymfonyRequest::class.'::HEADER_X_FORWARDED_PORT')  ? SymfonyRequest::HEADER_X_FORWARDED_PORT  : 0)
          | (defined(SymfonyRequest::class.'::HEADER_X_FORWARDED_PROTO') ? SymfonyRequest::HEADER_X_FORWARDED_PROTO : 0);

        // Fallback: nếu vì lý do nào đó tất cả đều = 0, dùng chuẩn RFC 7239 "Forwarded"
        if ($this->headers === 0 && defined(SymfonyRequest::class.'::HEADER_FORWARDED')) {
            $this->headers = SymfonyRequest::HEADER_FORWARDED;
        }
    }
}
