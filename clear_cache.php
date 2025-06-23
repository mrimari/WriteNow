<?php

// Скрипт для очистки кэша Laravel
// Запустите этот файл через браузер или командную строку на хостинге

echo "Начинаем очистку кэша...\n";

// Подключаем автозагрузчик Laravel
require_once __DIR__ . '/vendor/autoload.php';

// Создаем экземпляр приложения
$app = require_once __DIR__ . '/bootstrap/app.php';

// Очищаем различные типы кэша
try {
    // Очистка кэша конфигурации
    $app['cache']->flush();
    echo "✓ Кэш приложения очищен\n";
    
    // Очистка кэша маршрутов
    $app['router']->clearCompiledRoutes();
    echo "✓ Кэш маршрутов очищен\n";
    
    // Очистка кэша конфигурации
    $app['config']->clear();
    echo "✓ Кэш конфигурации очищен\n";
    
    // Очистка кэша представлений
    $viewPath = storage_path('framework/views');
    if (is_dir($viewPath)) {
        $files = glob($viewPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "✓ Кэш представлений очищен\n";
    }
    
    // Очистка кэша сессий
    $sessionPath = storage_path('framework/sessions');
    if (is_dir($sessionPath)) {
        $files = glob($sessionPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "✓ Кэш сессий очищен\n";
    }
    
    echo "\n✅ Все кэши успешно очищены!\n";
    echo "Теперь обновите страницу в браузере.\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка при очистке кэша: " . $e->getMessage() . "\n";
} 