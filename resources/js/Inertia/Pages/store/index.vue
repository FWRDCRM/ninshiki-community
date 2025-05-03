<script setup>
import Layout from '@/Layouts/layout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue';
import { useConfirm } from 'primevue/useconfirm';
import { ref } from 'vue';

defineOptions({ layout: Layout });

const page = usePage();
const toast = useToast();
const confirm = useConfirm();
const tabCurrentOpen = ref('shop');

const products = ref(page.props.products);
const redeemed = ref(page.props.redeemed);

const getSeverity = (product) => {
    switch (product.status) {
        case 'Redeemed':
            return 'success';

        case 'Approved':
            return 'success';

        case 'Declined':
            return 'danger';

        case 'Processing':
            return 'danger';

        case 'Waiting-Approval':
            return 'danger';

        case 'Canceled':
            return 'danger';

        default:
            return null;
    }
};

const redeemProduct = (event, shop) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Are you sure you want to proceed?',
        icon: 'pi pi-question-circle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Purchase',
        },
        accept: () => {
            router.post(
                route('store.redeem'),
                {
                    id: shop.id,
                },
                {
                    preserveScroll: true,
                    onError: (errors) => {
                        console.log(errors);
                        if (errors.hasOwnProperty('amount')) {
                            toast.add({ severity: 'error', summary: 'Insufficient Coins', detail: errors.amount, life: 5000 });
                        }
                        if (errors.hasOwnProperty('stock')) {
                            toast.add({ severity: 'error', summary: 'Insufficient Product Stock', detail: errors.stock, life: 5000 });
                        }
                        if (errors.hasOwnProperty('error')) {
                            toast.add({ severity: 'error', summary: 'Something went wrong!', detail: errors.error, life: 5000 });
                        }
                    },
                    onSuccess: () => {
                        toast.add({ severity: 'success', summary: 'Redeemed', detail: 'Shop Item has been successfully redeemed.', life: 5000 });
                        tabCurrentOpen.value = 'redeemed';
                    },
                },
            );
        },
    });
};
</script>

<template>
    <div>
        <Toast />
        <ConfirmPopup />
        <Tabs v-model:value="tabCurrentOpen">
            <TabList>
                <Tab value="shop" as="div" class="flex items-center gap-2">
                    <i class="pi pi-shopping-cart" style="font-size: 1.2rem" />
                    <span class="whitespace-nowrap font-bold">Shop</span>
                </Tab>
                <Tab value="redeemed" as="div" class="flex items-center gap-2">
                    <i class="pi pi-gift" style="font-size: 1.2rem" />
                    <span class="whitespace-nowrap font-bold">Redeemed</span>
                </Tab>
            </TabList>
            <TabPanels>
                <TabPanel value="shop">
                    <div class="w-full max-w-full md:min-w-[1000px]">
                        <div class="flex flex-row flex-wrap gap-3">
                            <div v-if="products.length === 0" class="flex h-full w-full">
                                <div class="flex min-h-[300px] min-w-full">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <div class="flex flex-col space-y-3">
                                            <span class="text-base italic text-slate-400"
                                                >No available product in the shop. Please check again later.</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-for="shop in products" :key="shop.id" class="flex max-w-[250px]">
                                <Card class="w-full">
                                    <template #header>
                                        <div class="mt-4 flex flex-wrap items-center justify-center">
                                            <Image
                                                :alt="shop.product?.name"
                                                :src="shop.product?.image"
                                                width="180"
                                                height="180"
                                                class="h-[180px] w-[180px] object-cover"
                                            />
                                        </div>
                                    </template>
                                    <template #title>
                                        <div class="text-balance text-center">{{ shop?.product?.name }}</div>
                                    </template>
                                    <template #subtitle>
                                        <div class="flex flex-col gap-3">
                                            <div class="text-sm">
                                                <Chip label="Out of Stock" v-if="shop?.product?.stock === 0" class="bg-red-600 text-white" />
                                            </div>
                                            <div class="space-x-3 whitespace-pre text-center">
                                                <div v-if="shop?.product?.description !== shop?.product?.name">
                                                    {{ shop?.product?.description }}
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template #content>
                                        <div class="flex flex-row flex-wrap justify-between text-sm">
                                            <p class="text-sm">Stock: {{ shop?.product?.stock }}</p>
                                            <p class="text-sm">Price: {{ shop?.product?.price }}</p>
                                        </div>
                                    </template>
                                    <template #footer>
                                        <div class="flex flex-row-reverse gap-2 pt-5">
                                            <Button label="Purchase" severity="primary" @click="redeemProduct($event, shop)" />
                                            <Button
                                                icon="pi pi-heart"
                                                severity="help"
                                                variant="text"
                                                rounded
                                                aria-label="Favorite"
                                                v-tooltip.bottom="'Add to Wishlist'"
                                            />
                                        </div>
                                    </template>
                                </Card>
                            </div>
                        </div>
                    </div>
                </TabPanel>
                <TabPanel value="redeemed">
                    <div class="w-full max-w-full md:min-w-[1000px]">
                        <div class="flex flex-row flex-wrap gap-3">
                            <div v-if="redeemed.length === 0" class="flex h-full w-full">
                                <div class="flex min-h-[300px] min-w-full">
                                    <div class="flex h-full w-full items-center justify-center">
                                        <div class="flex flex-col space-y-3">
                                            <span class="text-base italic text-slate-400">Redeemed item will shown here.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <DataTable v-else :value="redeemed" tableStyle="min-width: 50rem" class="w-full">
                                <Column field="name" header="Name"></Column>
                                <Column header="Image">
                                    <template #body="slotProps">
                                        <img :src="slotProps.data.product.image" :alt="slotProps.data.product.image" class="w-24 rounded" />
                                    </template>
                                </Column>
                                <Column field="price" header="Price">
                                    <template #body="slotProps">
                                        {{ slotProps.data.product.price }}
                                    </template>
                                </Column>
                                <Column header="Status">
                                    <template #body="slotProps">
                                        <Tag :value="slotProps.data.status" :severity="getSeverity(slotProps.data)" />
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>
</template>

<style scoped></style>
