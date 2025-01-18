<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Services\ShortUrlService;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function encode(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'url' => 'required|url',
            'alias' => 'nullable|alpha_num|unique:short_urls,alias',
        ]);

        $encodedUrl = ShortUrlService::encode($request->input('url'), $request->input('alias'));

        if(!$encodedUrl)
        {
            return response()->json([
                'error' => 'Could not shorten the URL. Please try again later.'
            ], 500);
        }

        return response()->json([
            'short_url' => $encodedUrl->short_url
        ], 200);
    }

    public function decode(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'alias' => 'required|alpha_num'
        ]);

        $decodedUrl = ShortUrlService::decode($request->input('alias'));

        if(!$decodedUrl)
        {
            return response()->json([
                'error' => 'URL not found.'
            ], 404);
        }

        return response()->json([
            'original_url' => $decodedUrl->original_url
        ], 200);
    }

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $shortUrls = ShortUrl::query();

        if($request->has('query') && !empty($request->input('query')))
        {
            $shortUrls->where('original_url', 'like', '%'.$request->input('query').'%')->orWhere('alias', 'like', '%'.$request->input('query').'%');
        }

        $shortUrls->orderBy('created_at', 'desc');

        return response()->json($shortUrls->paginate($request->input('limit', 10)), 200);
    }

    public function redirect($alias): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
    {
        $decodedUrl = ShortUrlService::decode($alias);

        if(!$decodedUrl)
        {
            return response()->json([
                'error' => 'URL not found.'
            ], 404);
        }

        $decodedUrl->increment('uses');

        return redirect($decodedUrl->original_url);
    }
}
