// Функция для определения мобильного устройства
function isMobileDevice() {
    return window.innerWidth <= 768;
}

// Функция для отправки информации о типе устройства на сервер
function sendDeviceInfo() {
    const isMobile = isMobileDevice();
    
    // Создаем скрытое поле в форме или отправляем через AJAX
    const deviceInput = document.createElement('input');
    deviceInput.type = 'hidden';
    deviceInput.name = 'is_mobile';
    deviceInput.value = isMobile ? '1' : '0';
    
    // Добавляем к форме фильтрации, если она существует
    const filterForm = document.querySelector('form[data-filter-form]');
    if (filterForm) {
        filterForm.appendChild(deviceInput);
    }
}

// Выполняем при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    sendDeviceInfo();
    
    // Также отправляем информацию при изменении размера окна
    window.addEventListener('resize', function() {
        sendDeviceInfo();
    });
});
