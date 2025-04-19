<script setup>
import Layout from '@/Layouts/layout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({ layout: Layout });

const page = usePage();
const user = computed(() => page.props.auth.user);

const items = ref([
    { route: '/dashboard', label: 'Dashboard', icon: 'pi pi-home' },
    { route: '/transactions', label: 'Transactions', icon: 'pi pi-chart-line' },
    { route: '/products', label: 'Products', icon: 'pi pi-list' },
    { route: '/messages', label: 'Messages', icon: 'pi pi-inbox' },
]);
</script>

<template>
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
                            <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" shape="circle" />
                            <span class="whitespace-nowrap font-bold">Amy Elsner</span>
                        </Tab>
                        <Tab value="1" as="div" class="flex items-center gap-2">
                            <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/onyamalimba.png" shape="circle" />
                            <span class="whitespace-nowrap font-bold">Onyama Limba</span>
                        </Tab>
                        <Tab v-slot="slotProps" value="2" asChild>
                            <div :class="['flex items-center gap-2', slotProps.class]" @click="slotProps.onClick" v-bind="slotProps.a11yAttrs">
                                <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/ionibowcher.png" shape="circle" />
                                <span class="whitespace-nowrap font-bold">Ioni Bowcher</span>
                                <Badge value="2" />
                            </div>
                        </Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel value="0" as="p" class="m-0">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis
                            aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                            cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </TabPanel>
                        <TabPanel value="1" as="p" class="m-0">
                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque
                            ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem
                            quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi
                            nesciunt. Consectetur, adipisci velit, sed quia non numquam eius modi.
                        </TabPanel>
                        <TabPanel v-slot="slotProps" value="2" asChild>
                            <div v-show="slotProps.active" :class="slotProps.class" v-bind="slotProps.a11yAttrs">
                                <p class="m-0">
                                    At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque
                                    corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                    qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita
                                    distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
                                </p>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </template>
        </Card>
    </div>
</template>

<style scoped></style>
