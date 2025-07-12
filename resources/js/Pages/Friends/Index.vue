<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    my_friend_code: String,
});

const friends = ref([]);
const isLoading = ref(true);
const myCode = ref(props.my_friend_code);

// Форма для добавления друга
const addFriendForm = useForm({
    friend_code: '',
});

// Получение списка друзей при загрузке страницы
const fetchFriends = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get('/api/friends');
        friends.value = response.data.data;
    } catch (error) {
        console.error("Ошибка при загрузке списка друзей:", error);
        // Здесь можно добавить обработку ошибок для пользователя
    } finally {
        isLoading.value = false;
    }
};

onMounted(fetchFriends);

// Добавление друга
const submitAddFriend = () => {
    addFriendForm.post(route('api.friends.store'), { // предполагаем, что у роута будет имя 'api.friends.store'
        onSuccess: () => {
            addFriendForm.reset();
            fetchFriends(); // Обновляем список
        },
        onError: (errors) => {
            // Ошибки будут автоматически обработаны Inertia
            console.error(errors);
        }
    });
};

// Удаление друга
const removeFriend = (friendId) => {
    if (confirm('Вы уверены, что хотите удалить этого друга?')) {
        axios.delete(`/api/friends/${friendId}`)
            .then(() => {
                fetchFriends(); // Обновляем список
            })
            .catch(error => {
                console.error("Ошибка при удалении друга:", error);
            });
    }
};

// Перегенерация кода
const regenerateCode = async () => {
    if (confirm('Вы уверены? Ваш старый код перестанет работать.')) {
        try {
            const response = await axios.patch('/api/user/friend-code');
            myCode.value = response.data.friend_code;
        } catch (error) {
            console.error("Ошибка при регенерации кода:", error);
        }
    }
};

</script>

<template>
    <Head title="Друзья" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Верхний блок управления -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Мой код дружбы -->
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ваш код дружбы</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Поделитесь этим кодом, чтобы другие могли добавить вас в друзья.
                        </p>
                        <div class="mt-4 flex items-center gap-4">
                            <input type="text" :value="myCode" readonly class="block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-center font-mono text-lg">
                            <button @click="regenerateCode" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md">Обновить</button>
                        </div>
                    </div>

                    <!-- Добавить друга по коду -->
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Добавить друга</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Введите код пользователя, которого хотите добавить.
                        </p>
                        <form @submit.prevent="submitAddFriend" class="mt-4 flex items-center gap-4">
                            <input v-model="addFriendForm.friend_code" type="text" placeholder="ABC-123" class="block w-full dark:bg-gray-900 border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                            <button type="submit" :disabled="addFriendForm.processing" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md disabled:opacity-50">Добавить</button>
                        </form>
                        <p v-if="addFriendForm.errors.friend_code" class="text-sm text-red-600 mt-2">{{ addFriendForm.errors.friend_code }}</p>
                    </div>
                </div>


                <!-- Список друзей -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Список друзей</h2>

                    <div v-if="isLoading" class="mt-4 text-gray-500">Загрузка...</div>

                    <div v-else-if="friends.length > 0" class="mt-4 space-y-4">
                        <div v-for="friend in friends" :key="friend.id" class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <!-- Placeholder for avatar -->
                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ friend.name }}</span>
                            </div>
                            <button @click="removeFriend(friend.id)" class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md">Удалить</button>
                        </div>
                    </div>

                    <div v-else class="mt-4 text-gray-500">
                        У вас пока нет друзей. Поделитесь своим кодом или добавьте друга по его коду.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
