<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShortUrlResource;
use App\Models\ShortUrl;
use App\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShortUrlController extends Controller
{
    public function encode(Request $request): \Illuminate\Http\JsonResponse
    {
        if(!str_contains($request->input('url'), 'http://') && !str_contains($request->input('url'), 'https://')) {
            $request->merge(['url' => 'http://' . $request->input('url')]);
        }

        $validation = Validator::make($request->only(['url', 'alias']), [
            'url' => 'required|url',
            'alias' => 'nullable|alpha_num|unique:short_urls,alias',
        ]);

        if($validation->fails()) {
            return response()->json([
                'error' => $validation->errors()->first()
            ], 422);
        }

        $encodedUrl = ShortUrlService::encode($request->input('url'), $request->input('alias'));

        if(!$encodedUrl) {
            return response()->json([
                'error' => 'Could not shorten the URL. Please try again later.'
            ], 500);
        }

        return response()->json(ShortUrlResource::make($encodedUrl), 200);
    }

    public function decode(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = Validator::make($request->only(['alias']), [
            'alias' => 'required|alpha_num'
        ]);

        if($validation->fails()) {
            return response()->json([
                'error' => $validation->errors()->first()
            ], 422);
        }

        $decodedUrl = ShortUrlService::decode($request->input('alias'));

        if(!$decodedUrl) {
            return response()->json([
                'error' => 'URL not found.'
            ], 404);
        }

        return response()->json(ShortUrlResource::make($decodedUrl), 200);
    }

    public function list(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $shortUrls = ShortUrl::query();

        if ($request->has('query') && !empty($request->input('query'))) {
            $shortUrls->where('original_url', 'like', '%' . $request->input('query') . '%')
                ->orWhere('alias', 'like', '%' . $request->input('query') . '%');
        }

        $shortUrls->orderBy('created_at', 'desc');

        $paginatedShortUrls = $shortUrls->paginate($request->input('limit', 10));

        return ShortUrlResource::collection($paginatedShortUrls);
    }

    public function redirect($alias): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
    {
        $decodedUrl = ShortUrlService::decode($alias);

        if(!$decodedUrl) {
            return response()->json([
                'error' => 'URL not found.'
            ], 404);
        }

        $decodedUrl->increment('uses');

        return redirect($decodedUrl->original_url);
    }
}
