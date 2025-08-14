<script setup>
import { computed, onMounted, ref } from 'vue';
import { useNotifications } from '@/Composables/useNotifications.js';

// --- Иконки для разных типов уведомлений (встроенные SVG) ---
const icons = {
    info: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
    success: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
    warning: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`,
    error: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
};

const props = defineProps({
    notification: {
        type: Object,
        required: true,
    },
});

const { remove } = useNotifications();

// --- Стилизация в зависимости от типа ---
const containerClasses = computed(() => {
    switch (props.notification.type) {
        case 'success': return 'bg-green-500 border-green-600';
        case 'warning': return 'bg-yellow-500 border-yellow-600';
        case 'error': return 'bg-red-500 border-red-600';
        default: return 'bg-blue-500 border-blue-600'; // info
    }
});
</script>

<template>
    <div
        :class="containerClasses"
        class="relative max-w-sm w-full text-white rounded-md shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden border-l-4"
    >
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0" v-html="icons[notification.type] || icons.info"></div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium">{{ notification.message }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="remove(notification.id)" class="inline-flex rounded-md text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Полоса-таймер -->
        <div class="absolute bottom-0 left-0 h-1 bg-black bg-opacity-30 animate-progress" :style="{ animationDuration: `${notification.duration}ms` }"></div>
    </div>
</template>

<style>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
.animate-progress {
    animation: progress linear forwards;
}
</style>
