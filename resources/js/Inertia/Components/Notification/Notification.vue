<script setup>
import { router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

dayjs.extend(relativeTime);

const notifications = ref([]);
const isFetchingNotifications = ref(true);
const toast = useToast();

const fetchNotifications = () => {
    isFetchingNotifications.value = true;
    NinshikiApp.request()
        .get(route('notification.index'))
        .then((resp) => {
            notifications.value = resp.data.data;
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => (isFetchingNotifications.value = false));
};

const markAllAsRead = () => {
    router.patch(
        route('notification.mark-all-as-read'),
        {},
        {
            onSuccess: () => {
                notifications.value = [];
                toast.add({ severity: 'success', summary: 'Success', detail: 'All notifications has been marked as read.', life: 3000 });
                fetchNotifications();
            },
        },
    );
};

const markAsRead = (id) => {
    router.patch(
        route('notification.mark-as-read', { id: id }),
        {},
        {
            onSuccess: () => {
                notifications.value = [];
                toast.add({ severity: 'success', summary: 'Success', detail: 'Notification has been marked as read.', life: 3000 });
                fetchNotifications();
            },
        },
    );
};

onMounted(() => {
    fetchNotifications();
});
</script>

<template>
    <Toast />
    <div>
        <div class="mb-3 mt-1 flex justify-end border-b border-gray-200" v-if="notifications.length > 0">
            <Button label="Mark All as Read" variant="text" size="small" @click.stop="markAllAsRead" />
        </div>
        <div v-if="isFetchingNotifications">
            <Skeleton class="mb-2"></Skeleton>
            <Skeleton width="10rem" class="mb-2"></Skeleton>
            <Skeleton width="5rem" class="mb-2"></Skeleton>
        </div>
        <div v-if="notifications.length === 0 && isFetchingNotifications === false">
            <div class="flex flex-wrap justify-center">
                <i class="text-balance text-lg">No unread notification.</i>
            </div>
        </div>
        <div v-else class="mt-4 divide-y divide-gray-200">
            <div v-for="notification in notifications" :key="notification.id" class="py-2 first:pt-0 last:pb-3">
                <div class="flex flex-col-reverse gap-1">
                    <div class="flex justify-end">
                        <span class="hover:cursor-pointer hover:underline" @click.stop="markAsRead(notification.id)">Mark as read</span>
                    </div>
                    <div class="flex flex-wrap" v-if="notification.type === 'post-recognized'">
                        <div class="flex w-full justify-between">
                            <div>
                                {{ notification.data.title }}
                            </div>
                            <div>
                                <span class="hover:cursor-pointer hover:underline">{{ dayjs(notification.created_at).fromNow() }}</span>
                            </div>
                        </div>
                        <div>
                            <span>{{ notification.data.message }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
