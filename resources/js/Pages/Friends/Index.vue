<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useFriendsApi } from "@/Composables/useFriendsApi.js";
import { useNotifications } from "@/Composables/useNotifications.js";
import FriendListItem from '@/Components/Friends/FriendListItem.vue';
import FriendRequestListItem from '@/Components/Friends/FriendRequestListItem.vue';

const notify = useNotifications();
// Получаем начальные данные, переданные из контроллера
const props = defineProps({
    friends: Object,
    friendRequests: Object,
    currentUser: Object,
});

const page = usePage();
const api = useFriendsApi();

const addFriendForm = ref({
    friend_code: '',
    processing: false,
});

const handleAddFriend = async () => {
    if (!addFriendForm.value.friend_code) return;
    addFriendForm.value.processing = true;
    try {
        await api.addFriend(addFriendForm.value.friend_code);
        notify.add('Запрос в друзья отправлен!', 'success');
        addFriendForm.value.friend_code = ''; // Очищаем поле
        await refetchFriendRequests();
    } catch (error) {
        notify.add(error.response?.data?.message || 'Не удалось отправить запрос.', 'error');
    } finally {
        addFriendForm.value.processing = false;
    }
};

const handleRegenerateCode = async () => {
    try {
        const response = await api.regenerateCode();
        onCodeRegenerated(response.data.data.friend_code);
        notify.add('Код дружбы обновлен!', 'info');
    } catch {
        notify.add('Не удалось обновить код.', 'error');
    }
};


// Реактивные переменные для состояния
const localFriends = ref(props.friends.data);
const localFriendRequests = ref(props.friendRequests.data);
const localCurrentUser = ref(props.currentUser);
const activeTab = ref('friends'); // 'friends' или 'requests'

// --- Методы для обновления состояния ---
const refetchFriendRequests = async () => {
    try {
        const response = await axios.get(route('api.friends.requests.index'));
        localFriendRequests.value = response.data.data;
    } catch (error) {
        console.error("Ошибка при обновлении списка запросов:", error);
    }
};

const refetchFriends = async () => {
    try {
        const response = await axios.get(route('api.friends.index'));
        localFriends.value = response.data.data;
    } catch (error) {
        console.error("Ошибка при обновлении списка друзей:", error);
    }
};

const onFriendRemoved = (friendId) => {
    localFriends.value = localFriends.value.filter(f => f.id !== friendId);
};

const onRequestHandled = () => {
    // Просто обновляем оба списка для простоты
    refetchFriendRequests();
    refetchFriends();
};

const onCodeRegenerated = (newCode) => {
    localCurrentUser.value.data.friend_code = newCode;
};

const copyFriendCode = async () => {
    try {
        await navigator.clipboard.writeText(localCurrentUser.value.data.friend_code);
        notify.add('Код дружбы скопирован!', 'success', 3000); // Уведомление на 3 секунды
    } catch (err) {
        notify.add('Не удалось скопировать код.', 'error');
        console.error('Failed to copy: ', err);
    }
};

</script>

<template>
    <Head title="Друзья" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Левая колонка: Списки -->
                    <div class="md:col-span-2 bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <!-- Табы -->
                        <div class="border-b border-gray-700 mb-4">
                            <nav class="-mb-px flex space-x-6">
                                <button @click="activeTab = 'friends'" :class="['whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm', activeTab === 'friends' ? 'border-indigo-400 text-indigo-300' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-500']">
                                    Мои друзья ({{ localFriends.length }})
                                </button>
                                <button @click="activeTab = 'requests'" :class="['whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm', activeTab === 'requests' ? 'border-indigo-400 text-indigo-300' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-500']">
                                    Запросы ({{ localFriendRequests.length }})
                                </button>
                            </nav>
                        </div>

                        <!-- Контент табов -->
                        <div v-if="activeTab === 'friends'">
                            <div v-if="localFriends.length > 0" class="space-y-2">
                                <FriendListItem v-for="friend in localFriends" :key="friend.id" :friend="friend" @removed="onFriendRemoved"/>
                            </div>
                            <p v-else class="text-gray-400">У вас пока нет друзей.</p>
                        </div>

                        <!-- Внутри таба "Запросы" -->
                        <div v-if="activeTab === 'requests'">
                            <div v-if="localFriendRequests.length > 0" class="space-y-2">
                                <!-- Просто используем компонент по имени -->
                                <FriendRequestListItem v-for="request in localFriendRequests" :key="request.id" :request="request" @handled="onRequestHandled" />
                            </div>
                            <p v-else class="text-gray-400">Нет активных запросов.</p>
                        </div>
                    </div>

                    <!-- Правая колонка: Инструменты -->
                    <div class="space-y-6">
                        <!-- Добавить друга -->
                        <div class="bg-gray-800 shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-3">Добавить друга</h3>
                            <!-- Используем @submit.prevent="handleAddFriend" -->
                            <form @submit.prevent="handleAddFriend">
                                <label for="friend_code" class="block text-sm font-medium text-gray-300">Код дружбы</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <!-- Привязываем поле к нашей реактивной переменной с помощью v-model -->
                                    <input
                                        v-model="addFriendForm.friend_code"
                                        type="text"
                                        name="friend_code"
                                        id="friend_code"
                                        class="flex-1 block w-full rounded-none rounded-l-md bg-gray-900 border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="ABC123XYZ"
                                        :disabled="addFriendForm.processing"
                                    >
                                    <!-- Блокируем кнопку на время запроса -->
                                    <button
                                        type="submit"
                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-700 bg-gray-600 text-gray-200 hover:bg-gray-500 disabled:opacity-50"
                                        :disabled="addFriendForm.processing"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Мой код дружбы -->
                        <div class="bg-gray-800 shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-3">Мой код дружбы</h3>
                            <div class="flex items-center justify-between bg-gray-900 p-3 rounded-md">
                                <span class="text-xl font-mono tracking-widest">{{ localCurrentUser.data.friend_code }}</span>

                                <!-- Блок с двумя новыми кнопками-иконками -->
                                <div class="flex items-center space-x-3">
                                    <!-- Кнопка "Копировать" -->
                                    <button @click="copyFriendCode" title="Копировать в буфер обмена" class="text-gray-400 hover:text-white transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>

                                    <!-- Кнопка "Сгенерировать новый" -->
                                    <button @click="handleRegenerateCode" title="Сгенерировать новый код" class="text-gray-400 hover:text-white transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 4l5 5M20 20l-5-5" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
