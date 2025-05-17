<script setup>
import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { useToast } from 'primevue';

const notifications = ref([]);
const isFetchingNotifications = ref(true);
const toast = useToast();

const fetchNotifications = () => {
    isFetchingNotifications.value = true;
    NinshikiApp.request()
        .get(route('notification.index'))
        .then((resp) => {
            console.log(resp);
            notifications.value.push(resp.data.data);
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
                toast.add({ severity: 'success', summary: 'Success', detail: 'All notifications has been marked as read.', life: 3000 });
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
    <div>
        <div class="mb-4 mt-1 flex justify-end" v-if="notifications.length > 0">
            <Button label="Mark All as Read" variant="text" size="small" />
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
        <div v-else></div>
    </div>
</template>
