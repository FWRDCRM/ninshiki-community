<script setup>
import Layout from '@/Layouts/layout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue';
import Password from 'primevue/password';
import { useConfirm } from 'primevue/useconfirm';
import { computed, ref } from 'vue';

defineOptions({ layout: Layout });

const page = usePage();
const auth = computed(() => page.props.auth.user);

const sessions = computed(() => page.props.devices);
const requirePassword = computed(() => page.props.logout_other_devices?.required_password);

const confirm = useConfirm();
const toast = useToast();

const password = ref();
const passwordError = ref();
const isSubmitting = ref(false);
const showConfirmationDialog = ref(false);

const confirmLogoutOtherDevices = (event) => {
    if (requirePassword) {
        router.post(
            route('profile.devices-other-logout'),
            {
                password: password.value,
            },
            {
                preserveScroll: true,
                errorBag: 'logoutOtherDevices',
                onError: (errors) => {
                    password.value = '';
                    passwordError.value = errors?.password;
                },
                onStart: () => {
                    isSubmitting.value = true;
                },
                onSuccess: () => {
                    showConfirmationDialog.value = false;
                    toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Other devices has been successfully logged out.', life: 3000 });
                    password.value = '';
                },
                onFinish: () => {
                    isSubmitting.value = false;
                },
            },
        );
    } else {
        confirm.require({
            target: event.currentTarget,
            message: 'Do you want to logout other devices?',
            icon: 'pi pi-question-circle',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true,
            },
            acceptProps: {
                label: 'Continue',
                severity: 'danger',
            },
            accept: () => {
                router.post(
                    route('profile.devices-other-logout'),
                    {},
                    {
                        preserveScroll: true,
                        onStart: () => {
                            isSubmitting.value = true;
                        },
                        onSuccess: () => {
                            toast.add({
                                severity: 'info',
                                summary: 'Confirmed',
                                detail: 'Other devices has been successfully logged out.',
                                life: 3000,
                            });
                        },
                        onFinish: () => {
                            isSubmitting.value = false;
                        },
                    },
                );
            },
        });
    }
};
</script>

<template>
    <div>
        <Toast />
        <Dialog v-if="requirePassword" v-model:visible="showConfirmationDialog" modal header="Logout Other Devices" :style="{ width: '25rem' }">
            <div class="flex w-full flex-col gap-2 p-4">
                <label for="password" class="w-full font-semibold">Confirm Password</label>
                <Password
                    :toggleMask="true"
                    :invalid="passwordError?.length > 0"
                    id="password"
                    class="flex w-full"
                    v-model="password"
                    :feedback="false"
                    fluid
                />
                <Message v-if="passwordError?.length > 0" severity="error" variant="simple" size="small">
                    {{ passwordError }}
                </Message>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" outlined @click="showConfirmationDialog = false"></Button>
                <Button type="button" label="Continue" :loading="isSubmitting" severity="danger" @click="confirmLogoutOtherDevices($event)"></Button>
            </div>
        </Dialog>
        <ConfirmPopup v-else></ConfirmPopup>
        <div class="flex w-full">
            <Card>
                <template #content class="flex w-full max-w-md">
                    <Tabs
                        value="0"
                        :pt="{
                            root: { class: 'flex w-[600px]' },
                        }"
                    >
                        <TabList>
                            <Tab value="0" as="div" class="flex items-center gap-2">
                                <i class="pi pi-user" />
                                <span class="whitespace-nowrap font-bold">Profile</span>
                            </Tab>
                            <Tab value="1" as="div" class="flex items-center gap-2">
                                <i class="pi pi-desktop" />
                                <span class="whitespace-nowrap font-bold">Devices</span>
                            </Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel value="0" as="p" class="m-0">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label for="name">Name</label>
                                        <InputText id="name" :model-value="auth?.name" aria-describedby="name-help" disabled />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="username">Username</label>
                                        <InputText id="username" :model-value="auth?.username" aria-describedby="username-help" disabled />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="email">Email Address</label>
                                        <InputText id="email" :model-value="auth?.email" aria-describedby="email-help" disabled />
                                    </div>
                                </div>
                            </TabPanel>
                            <TabPanel value="1" as="p" class="m-0">
                                <div class="space-y-5">
                                    <div>
                                        <Message size="large" severity="info" variant="simple"> Browser Sessions</Message>
                                        <Message size="small" severity="secondary" variant="simple">
                                            Manage and log out your active sessions on other browsers and devices.
                                        </Message>
                                    </div>
                                    <!-- Other Browser Sessions -->
                                    <div>
                                        <ScrollPanel style="width: 100%; height: 200px">
                                            <div v-if="sessions?.length > 0" class="mt-5 space-y-6">
                                                <div v-for="(session, i) in sessions" :key="i" class="flex items-center">
                                                    <div>
                                                        <svg
                                                            v-if="session?.is_desktop"
                                                            class="size-8 text-gray-500"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"
                                                            />
                                                        </svg>

                                                        <svg
                                                            v-else
                                                            class="size-8 text-gray-500"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
                                                            />
                                                        </svg>
                                                    </div>

                                                    <div class="ms-3">
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                                            {{ session?.platform ? session?.platform : 'Unknown' }} -
                                                            {{ session.browser ? session.browser : 'Unknown' }}
                                                        </div>

                                                        <div>
                                                            <div class="text-xs text-gray-500">
                                                                <span v-if="session.is_current_device" class="font-semibold text-green-500"
                                                                    >This device</span
                                                                >
                                                                <span v-else>Last active {{ session.last_active }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </ScrollPanel>
                                    </div>
                                    <div class="flex w-full flex-row-reverse">
                                        <Button
                                            @click="requirePassword ? (showConfirmationDialog = true) : confirmLogoutOtherDevices($event)"
                                            label="Log Out Other Browser Sessions"
                                            :loading="isSubmitting"
                                            severity="danger"
                                            size="small"
                                        />
                                    </div>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </template>
            </Card>
        </div>
    </div>
</template>

<style scoped></style>
