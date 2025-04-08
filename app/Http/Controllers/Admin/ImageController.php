<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * 处理图片上传
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'upload' => 'required|file|image|max:2048', // 最大 2MB
            ]);

            if (!$request->hasFile('upload')) {
                throw new \Exception('没有接收到上传的文件');
            }

            $file = $request->file('upload');
            
            // 记录文件信息
            Log::info('Uploaded file info', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'error' => $file->getError()
            ]);

            if ($file->getError() !== UPLOAD_ERR_OK) {
                throw new \Exception('文件上传出错：' . $file->getErrorMessage());
            }

            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            
            // 存储图片
            $path = $file->storeAs('articles/images', $fileName, 'public');
            
            if (!$path) {
                throw new \Exception('文件存储失败');
            }

            // 获取完整URL
            $url = asset('storage/' . $path);
            
            // 记录成功信息
            Log::info('Image uploaded successfully', [
                'path' => $path,
                'url' => $url
            ]);

            return response()->json([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $url
            ]);

        } catch (\Exception $e) {
            Log::error('Image upload failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => '图片上传失败：' . $e->getMessage()
                ]
            ], 400);
        }
    }

    /**
     * 代理下载外部图片
     */
    public function proxyImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'url' => 'required|url'
            ]);

            $imageUrl = $request->input('url');
            
            // 记录下载信息
            Log::info('Downloading image', [
                'url' => $imageUrl
            ]);

            // 解析URL获取域名
            $parsedUrl = parse_url($imageUrl);
            $host = $parsedUrl['host'] ?? '';

            // 设置请求头
            $headers = [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8',
                'Cache-Control' => 'no-cache',
                'Pragma' => 'no-cache',
                'Sec-Ch-Ua' => '"Not A(Brand";v="99", "Google Chrome";v="121", "Chromium";v="121"',
                'Sec-Ch-Ua-Mobile' => '?0',
                'Sec-Ch-Ua-Platform' => '"Windows"',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'none',
                'Sec-Fetch-User' => '?1',
                'Upgrade-Insecure-Requests' => '1',
                'Referer' => "https://{$host}/"
            ];

            // 下载图片
            $response = Http::withHeaders($headers)
                ->withOptions([
                    'verify' => false,  // 忽略SSL验证
                    'timeout' => 30,    // 设置超时时间
                ])
                ->get($imageUrl);
            
            if (!$response->successful()) {
                throw new \Exception('图片下载失败：' . $response->status());
            }

            // 获取文件扩展名
            $contentType = $response->header('Content-Type');
            $extension = match ($contentType) {
                'image/jpeg', 'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'jpg'
            };

            // 生成文件名
            $fileName = Str::random(40) . '.' . $extension;
            
            // 存储图片
            $path = 'articles/images/' . $fileName;
            if (!Storage::disk('public')->put($path, $response->body())) {
                throw new \Exception('文件存储失败');
            }

            // 获取完整URL
            $url = asset('storage/' . $path);
            
            // 记录成功信息
            Log::info('Image downloaded successfully', [
                'path' => $path,
                'url' => $url
            ]);

            return response()->json([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $url
            ]);

        } catch (\Exception $e) {
            Log::error('Image proxy failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => '图片下载失败：' . $e->getMessage()
                ]
            ], 400);
        }
    }
} 