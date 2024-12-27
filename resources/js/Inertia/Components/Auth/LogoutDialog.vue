<script setup>
import ConfirmDialog from 'primevue/confirmdialog';
import {usePage} from "@inertiajs/vue3";

const page = usePage()
</script>

<template>
    <ConfirmDialog group="headless">
        <template #container="{ message, acceptCallback, rejectCallback }">
            <div class="flex flex-col items-center p-8 bg-surface-0 dark:bg-surface-900 rounded">
                <Avatar
                    :image="page.props.auth.user.avatar ?? `https://avatar.iran.liara.run/username?username=${page.props.auth.user.email}`"
                    class="mr-2 rounded-full  h-24 w-24 -mt-20 inline-flex" shape="circle" size="xlarge"/>
                <span class="font-bold text-2xl block mb-2 mt-6">{{ message.header }}</span>
                <p class="mb-0">{{ message.message }}</p>
                <div class="flex items-center gap-2 mt-6">
                    <Button label="Cancel" severity="secondary" @click="rejectCallback" class="w-32"></Button>
                    <Button label="Logout" severity="danger" @click="acceptCallback" class="w-32"></Button>
                </div>
            </div>
        </template>
    </ConfirmDialog>
</template>

<style scoped>

</style>
