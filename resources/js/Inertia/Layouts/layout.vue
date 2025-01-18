<script setup>
import {reactive, ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import Menu from 'primevue/menu';
import LogoutDialog from "@/Components/Auth/LogoutDialog.vue";
import {useConfirm} from "primevue/useconfirm";
import ScrollPanel from 'primevue/scrollpanel';
import Toast from 'primevue/toast';
import {route} from "ziggy-js";

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

const app = reactive({
    version: NinshikiApp.version,
    name: NinshikiApp.appName
})

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
                        router.reload({
                            only: ['posts'],
                            preserveState: false
                        }) : router.visit(route('feed'), {preserveState: false});

                }
            },
            {
                label: 'Employees',
                icon: 'pi pi-users',
                command: () => {
                    route().current('employees.list') ?
                        router.reload({
                            only: ['employees'],
                            preserveState: false
                        }) : router.visit(route('employees.list'), {preserveState: false});

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
    <div class="flex w-full justify-center h-fit">
        <LogoutDialog/>
        <Toast position="bottom-right" group="br"/>
        <div class="flex">
            <!-- Left Sidebar  -->
            <div class="h-fit sticky top-9">
                <!-- Fixed Sidebar -->
                <div class="sidebar">
                    <Menu :model="items" class="w-full md:w-60 ">
                        <template #start>
                    <span class="inline-flex items-center gap-1 px-2 py-2">
                        <span class="text-xl font-semibold text-primary">
                            {{ app.name }}
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
                                    :image="page.props.auth.user.avatar ?? $ninshiki.uiAvatar(page.props.auth.user.name)"
                                    class="mr-2" size="large" shape="circle"/>
                                <span class="inline-flex flex-col items-start">
                            <span class="text-balance font-bold">{{ page.props.auth.user.name }}</span>
                            <span class="text-xs font-normal italic text-gray-400">{{
                                    page.props.auth.user.email
                                }}</span>
                        </span>
                            </button>
                            <p class="flex flex-row-reverse italic w-full p-2 font-normal text-sm text-gray-300">
                                {{ app.version }}
                            </p>
                        </template>
                    </Menu>
                </div>
            </div>
            <!--  CONTENT  -->
            <div class="flex w-full relative top-9 h-fit">
                <ScrollPanel class="pl-5 flex w-full">
                    <Transition name="page" appear>
                        <slot name="default" class="flex w-full"/>
                    </Transition>
                </ScrollPanel>
            </div>
            <!--   Right Sidebar    -->
            <div v-if="route().current('feed')"  class="h-fit sticky top-9 w-full hidden lg:flex">
                <div class="flex lg:min-w-[200px]">
                    <Accordion :value="['0']" multiple :pt="{
                        root: {
                            class: 'w-[300px]'
                        }
                    }">
                        <AccordionPanel value="0">
                            <AccordionHeader>Wallets</AccordionHeader>
                            <AccordionContent>
                                <div class="flex gap-1 flex-col flex-wrap w-[200px] m-0">
                                    <div class="flex w-full space-x-2">
                                        <span class="text-sm text-secondary text-slate-400">Post Limit:</span>
                                        <span
                                            class="text-sm text-secondary text-slate-400">{{ page.props.wallet_credit.balance }} coins</span>
                                    </div>
                                    <div class="flex w-full space-x-2">
                                        <span class="text-sm text-secondary text-slate-400">Earned Coins:</span>
                                        <span
                                            class="text-sm text-secondary text-slate-400">{{ page.props.wallet_earned.balance }} coins</span>
                                    </div>
                                </div>
                            </AccordionContent>
                        </AccordionPanel>
                    </Accordion>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
