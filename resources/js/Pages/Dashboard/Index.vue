<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Breeze/Inertia автоматически передает данные об авторизованном пользователе в props
const props = defineProps({
    auth: Object
});
</script>

<template>
    <Head title="Дашборд" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-2xl font-bold">Добро пожаловать, {{ auth.user.name }}!</h3>
                        <p class="mt-2">Это твоя главная панель. Отсюда ты сможешь управлять своими играми, персонажами и многим другим.</p>
                    </div>
                </div>

                <!-- Блоки-плейсхолдеры для будущего функционала -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <!-- Активные игры -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Активные игры</h4>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Здесь будет список игр, в которых ты сейчас участвуешь.
                            </p>
                        </div>
                    </div>

                    <!-- Игры на паузе -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Игры на паузе</h4>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Здесь будут отображаться игры, которые ожидают твоего возвращения.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
