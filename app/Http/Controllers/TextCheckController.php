<?php

namespace App\Http\Controllers;

use App\Http\Requests\TextCheckRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Контроллер для проверки текста
 */
class TextCheckController extends Controller
{
    /**
     * URL API для проверки текста
     */
    private string $apiUrl = 'https://api.languagetool.org/v2/check';

    /**
     * Время кэширования в секундах
     */
    private int $cacheTime = 3600;

    /**
     * Проверяет текст на ошибки
     *
     * @param TextCheckRequest $request
     * @return JsonResponse
     */
    public function check(TextCheckRequest $request): JsonResponse
    {
        try {
            $text = $request->input('text');
            
            if (empty($text)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Текст для проверки не может быть пустым'
                ], 400);
            }

            // Проверяем кэш
            $cacheKey = 'text_check_' . md5($text);
            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }

            $response = Http::asForm()
                ->withOptions(['verify' => false])
                ->post($this->apiUrl, [
                    'text' => $text,
                    'language' => 'ru-RU',
                    'enabledOnly' => 'false',
                    'level' => 'picky',
                    'enabledCategories' => 'TYPOS,GRAMMAR,STYLE',
                ]);

            if (!$response->successful()) {
                Log::error('LanguageTool API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'error' => true,
                    'message' => 'Ошибка при обращении к сервису проверки текста'
                ], 500);
            }

            $result = $response->json();

            // Кэшируем результат
            Cache::put($cacheKey, $result, $this->cacheTime);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Text check error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Произошла ошибка при проверке текста'
            ], 500);
        }
    }
}

