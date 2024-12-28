<script setup>
import {ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import Menu from 'primevue/menu';
import LogoutDialog from "@/Components/Auth/LogoutDialog.vue";
import {useConfirm} from "primevue/useconfirm";
import ScrollPanel from 'primevue/scrollpanel';
import Toast from 'primevue/toast';

const confirm = useConfirm();
const page = usePage()

const requireConfirmation = () => {
    confirm.require({
        group: 'headless',
        header: 'Logout',
        message: 'Are you sure you want to logout?',
        accept: () => {
            router.post(route('logout'))
        },
        reject: () => {

        }
    });
};

const items = ref([
    {
        separator: true
    },
    {
        label: '',
        items: [
            {
                label: 'Feed',
                icon: 'pi pi-home',
                command: () => {
                    route().current('feed') ?
                        router.reload({only: ['posts']}) : router.push(route('feed'));

                }
            },
            {
                label: 'Logout',
                icon: 'pi pi-sign-out',
                command: () => requireConfirmation()
            }
        ]
    },
    {
        separator: true
    }
]);

</script>

<template>
    <div class="flex w-full justify-center">
        <LogoutDialog/>
        <Toast position="bottom-right" group="br"/>
        <div class="flex pt-10">
            <div class="h-screen sticky top-9">
                <!-- Fixed Sidebar -->
                <div class="sidebar">
                    <Menu :model="items" class="w-full md:w-60 ">
                        <template #start>
                    <span class="inline-flex items-center gap-1 px-2 py-2">
                        <span class="text-xl font-semibold text-primary">
                            {{ page.props.app.name }}
                        </span>
                    </span>
                        </template>
                        <template #submenulabel="{ item }">
                            <span class="text-primary font-bold">{{ item.label }}</span>
                        </template>
                        <template #item="{ item, props }">
                            <a v-ripple class="flex items-center" v-bind="props.action">
                                <span :class="item.icon"/>
                                <span>{{ item.label }}</span>
                                <Badge v-if="item.badge" class="ml-auto" :value="item.badge"/>
                                <span v-if="item.shortcut"
                                      class="ml-auto border border-surface rounded bg-emphasis text-muted-color text-xs p-1">
                            {{ item.shortcut }}
                        </span>
                            </a>
                        </template>
                        <template #end>
                            <button v-ripple
                                    class="relative overflow-hidden w-full border-0 bg-transparent flex items-start p-2 pl-4 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-none cursor-pointer transition-colors duration-200">
                                <Avatar
                                    :image="page.props.auth.user.avatar ?? `https://avatar.iran.liara.run/username?username=${page.props.auth.user.name}`"
                                    class="mr-2" size="large" shape="circle"/>
                                <span class="inline-flex flex-col items-start">
                            <span class="text-balance font-bold">{{ page.props.auth.user.name }}</span>
                            <span class="text-xs font-normal italic text-gray-400">{{
                                    page.props.auth.user.email
                                }}</span>
                        </span>
                            </button>
                            <p class="flex flex-row-reverse italic w-full p-2 font-normal text-sm text-gray-300">
                                {{ Ninshiki.version() }}
                            </p>
                        </template>
                    </Menu>
                </div>
            </div>

            <div class="flex w-full">
                <ScrollPanel class="pl-5 flex w-full">
                    <slot/>
                </ScrollPanel>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
