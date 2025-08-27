<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useFriendsApi } from "@/Composables/useFriendsApi.js";

const props = defineProps({
    request: Object,
});

const emit = defineEmits(['handled']);

const page = usePage();
const api = useFriendsApi();

const currentUserId = page.props.auth.user.id;
const isIncoming = computed(() => props.request.recipient.id === currentUserId);

const handleAccept = async () => {
    await api.acceptRequest(props.request.id);
    emit('handled');
};
const handleDecline = async () => {
    await api.declineRequest(props.request.id);
    emit('handled');
};
const handleCancel = async () => {
    await api.cancelRequest(props.request.id);
    emit('handled');
};
</script>

<template>
    <div class="flex items-center justify-between p-3 hover:bg-gray-700 rounded-lg transition">
        <div>
            <span v-if="isIncoming">Запрос от: <strong>{{ request.sender.name }}</strong></span>
            <span v-else>Запрос для: <strong>{{ request.recipient.name }}</strong></span>
        </div>
        <div class="flex items-center space-x-2">
            <template v-if="isIncoming">
                <button @click="handleAccept" class="text-sm px-3 py-1 bg-green-600 hover:bg-green-500 rounded">Принять</button>
                <button @click="handleDecline" class="text-sm px-3 py-1 bg-red-600 hover:bg-red-500 rounded">Отклонить</button>
            </template>
            <template v-else>
                <button @click="handleCancel" class="text-sm text-gray-400 hover:text-gray-300">Отменить</button>
            </template>
        </div>
    </div>
</template>
