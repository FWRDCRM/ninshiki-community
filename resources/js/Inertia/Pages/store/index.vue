<script setup>
import Layout from '@/Layouts/layout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: Layout });

const page = usePage();
const products = ref(page.props.products);
</script>

<template>
    <div v-for="product in products" :key="product.id" class="flex w-full">
        <Card class="min-[120px] w-full">
            <template #header>
                <div class="mt-4 flex flex-wrap items-center justify-center">
                    <Image
                        :alt="product.name"
                        src="https://d1r3vc4fck3z1b.cloudfront.net/images/1643763414_web_variance_hKVR7MB7.jpeg"
                        width="180"
                        height="210"
                    />
                </div>
            </template>
            <template #title>
                <div class="text-balance text-center">{{ product.product.name }}</div>
            </template>
            <template #subtitle>
                <div class="flex flex-col gap-3">
                    <div>
                        <Chip :label="`${product.product.stock} Available Stock`" v-if="product.product.stock > 0" class="bg-green-500 text-white" />
                        <Chip label="Out of Stock" v-else class="bg-red-600 text-white" />
                    </div>
                    <div class="space-x-3 whitespace-pre text-center">
                        <div v-if="product.product.description !== product.product.name">
                            {{ product.product.description }}
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex flex-row-reverse gap-2">
                    <Button label="Purchase" severity="secondary" />
                    <Button icon="pi pi-heart" severity="help" variant="text" rounded aria-label="Favorite" v-tooltip.bottom="'Add to Wishlist'" />
                </div>
            </template>
        </Card>
    </div>
</template>

<style scoped></style>
