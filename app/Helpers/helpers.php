<?php

use Illuminate\Support\Facades\Http;

if (! function_exists('checkImageUrl')) {
    /**
     * ตรวจสอบ url ของรูปภาพ ว่าใช้งานได้ไหม
     *
     * @param  string  $filename  ชื่อไฟล์ เช่น test.png หรือเป็น url http...
     * @return string url ที่ใช้งานได้
     */
    function checkImageUrl(string $filename): string
    {
        // ถ้า $filename เป็น URL (http/https) → return ไปเลย
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }

        $primaryBase  = env('PRIMARY_IMG_URL'). '/';
        $fallbackBase = env('FALLBACK_IMG_URL') . '/';

        $primaryUrl  = $primaryBase . ltrim($filename, '/');
        $fallbackUrl = $fallbackBase . ltrim($filename, '/');

        try {
            $response = Http::head($primaryUrl);

            if (
                $response->successful() &&
                str_starts_with($response->header('Content-Type'), 'image')
            ) {
                return $primaryUrl;
            }
        } catch (\Exception $e) {
            // fail → ใช้ fallback
        }

        return $fallbackUrl;
    }
}
