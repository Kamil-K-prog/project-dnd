import { ref, readonly } from 'vue';
import { v4 as uuid } from 'uuid';

// Глобальное, реактивное состояние для всех уведомлений
const notifications = ref([]);

// Функция для добавления уведомления
const addNotification = (message, type = 'info', duration = 5000) => {
    const id = uuid();

    notifications.value.push({
        id,
        message,
        type, // 'info', 'success', 'warning', 'error'
        duration,
    });

    // Устанавливаем таймер на удаление уведомления
    setTimeout(() => removeNotification(id), duration);
};

// Функция для удаления уведомления
const removeNotification = (id) => {
    notifications.value = notifications.value.filter(n => n.id !== id);
};

// Экспортируем composable-функцию
export function useNotifications() {
    return {
        notifications: readonly(notifications), // readonly, чтобы компоненты не могли менять массив напрямую
        add: addNotification,
        remove: removeNotification,
    };
}
