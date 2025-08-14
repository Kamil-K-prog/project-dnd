// resources/js/Composables/useFriendsApi.js
import axios from 'axios';

export function useFriendsApi() {
    const addFriend = (friendCode) => {
        return axios.post(route('api.friends.requests.store'), { friend_code: friendCode });
    };

    const acceptRequest = (requestId) => {
        return axios.post(route('api.friends.requests.accept', { friendRequest: requestId }));
    };

    const declineRequest = (requestId) => {
        return axios.post(route('api.friends.requests.decline', { friendRequest: requestId }));
    };

    const cancelRequest = (requestId) => {
        return axios.delete(route('api.friends.requests.destroy', { friendRequest: requestId }));
    };

    const removeFriend = (friendId) => {
        return axios.delete(route('api.friends.destroy', { user: friendId }));
    };

    const regenerateCode = () => {
        return axios.post(route('api.user.regenerate-friend-code'));
    };

    return {
        addFriend,
        acceptRequest,
        declineRequest,
        cancelRequest,
        removeFriend,
        regenerateCode,
    };
}
