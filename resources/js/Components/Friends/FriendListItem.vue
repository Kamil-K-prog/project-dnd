<script setup>
import { useFriendsApi } from "@/Composables/useFriendsApi.js";
import { useNotifications } from "@/Composables/useNotifications.js";

const props = defineProps({
    friend: Object,
});

const emit = defineEmits(['removed']);

const api = useFriendsApi();
const notify = useNotifications();

const handleRemove = async () => {
    if (!confirm(`Вы уверены, что хотите удалить ${props.friend.name} из друзей?`)) return;
    try {
        await api.removeFriend(props.friend.id);
        notify.add(`${props.friend.name} удален(а) из друзей.`, 'info');
        emit('removed', props.friend.id);
    } catch (error) {
        notify.add('Не удалось удалить друга. Попробуйте снова.', 'error');
        console.error(error);
    }
};
</script>

<template>
    <div class="flex items-center justify-between p-3 hover:bg-gray-700 rounded-lg transition">
        <span class="font-medium">{{ friend.name }}</span>
        <button @click="handleRemove" class="text-sm text-red-400 hover:text-red-300">Удалить</button>
    </div>
</template>
