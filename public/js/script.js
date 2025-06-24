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

// --- Toast уведомления ---
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.style = 'background: ' + (type === 'error' ? '#e74c3c' : type === 'success' ? '#27ae60' : '#333') + '; color: #fff; padding: 12px 20px; border-radius: 6px; margin-bottom: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); min-width: 200px; font-size: 16px;';
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => { toast.remove(); }, 3500);
}

// Показываем сообщения из LaravelToast
if (window.LaravelToast) {
    if (window.LaravelToast.success) showToast(window.LaravelToast.success, 'success');
    if (window.LaravelToast.error) showToast(window.LaravelToast.error, 'error');
}

// --- Реактивность для админки ---
function initAdminUsersPage() {
    // Получаем id текущего пользователя-админа из meta-тега (добавить в layout если нет)
    let currentAdminId = null;
    const meta = document.querySelector('meta[name="current-admin-id"]');
    if (meta) {
        currentAdminId = meta.getAttribute('content');
    }
    if (currentAdminId) {
        document.querySelectorAll('tr').forEach(function(row) {
            const idCell = row.querySelector('td');
            if (idCell && idCell.textContent.trim() === currentAdminId) {
                row.querySelectorAll('button').forEach(btn => {
                    btn.disabled = true;
                    btn.title = 'Нельзя выполнять действия над собой';
                });
            }
        });
    }
    function sendAdminAction(form, onSuccess, onError) {
        const url = form.action;
        const method = form.querySelector('input[name="_method"]')?.value || form.method;
        const formData = new FormData(form);
        fetch(url, {
            method: method.toUpperCase(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]')?.value,
            },
            body: formData
        })
        .then(response => response.json().catch(() => response.text()))
        .then(data => {
            if (typeof onSuccess === 'function') onSuccess(data);
        })
        .catch(err => {
            if (typeof onError === 'function') onError(err);
        });
    }
    document.querySelectorAll('.admin-table form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const row = form.closest('tr');
            if (currentAdminId && row) {
                const idCell = row.querySelector('td');
                if (idCell && idCell.textContent.trim() === currentAdminId) {
                    e.preventDefault();
                    showToast('Нельзя выполнять действия над собой', 'error');
                    return false;
                }
            }
            e.preventDefault();
            if (form.querySelector('button.admin-btn-danger') && !confirm('Вы уверены?')) {
                return false;
            }
            sendAdminAction(form, function(data) {
                if (typeof data === 'object' && data.error) {
                    showToast(data.error, 'error');
                    return;
                }
                if (form.action.includes('delete')) {
                    form.closest('tr').remove();
                    showToast('Пользователь удалён', 'success');
                } else {
                    location.reload();
                }
            }, function() {
                showToast('Ошибка запроса', 'error');
            });
        });
    });
}

function handlePageChange() {
    if (window.location.pathname.includes('/admin/users')) {
        setTimeout(initAdminUsersPage, 100); // подождать рендер
    }
}

function ajaxifyAdminUsersPagination() {
    const tableContainer = document.getElementById('admin-users-table');
    if (!tableContainer) return;
    tableContainer.addEventListener('click', function(e) {
        const link = e.target.closest('a.admin-pagination-link');
        if (link) {
            e.preventDefault();
            fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    // Вырезаем только содержимое admin-table-container из ответа
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newTable = temp.querySelector('#admin-users-table');
                    if (newTable) {
                        tableContainer.innerHTML = newTable.innerHTML;
                        initAdminUsersPage();
                    }
                });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    sendDeviceInfo();
    window.addEventListener('resize', function() {
        sendDeviceInfo();
    });
    handlePageChange();
    ajaxifyAdminUsersPagination();
});
window.addEventListener('popstate', handlePageChange);
