<script setup>
import { usePage } from '@inertiajs/vue3';
import ConfirmDialog from 'primevue/confirmdialog';

const page = usePage();
</script>

<template>
    <ConfirmDialog group="headless">
        <template #container="{ message, acceptCallback, rejectCallback }">
            <div class="flex flex-col items-center rounded bg-surface-0 p-8 dark:bg-surface-900">
                <Avatar
                    :image="
                        page.props.auth.user.avatar ?? `https://ui-avatars.com/api/?name=${page.props.auth.user.name}&rounded=true&background=random`
                    "
                    class="-mt-20 mr-2 inline-flex h-24 w-24 rounded-full"
                    shape="circle"
                    size="xlarge"
                />
                <span class="mb-2 mt-6 block text-2xl font-bold">{{ message.header }}</span>
                <p class="mb-0">{{ message.message }}</p>
                <div class="mt-6 flex items-center gap-2">
                    <Button label="Cancel" severity="secondary" @click="rejectCallback" class="w-32"></Button>
                    <Button label="Logout" severity="danger" @click="acceptCallback" class="w-32"></Button>
                </div>
            </div>
        </template>
    </ConfirmDialog>
</template>

<style scoped></style>
