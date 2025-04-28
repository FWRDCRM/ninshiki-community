<script setup>
import Layout from '@/Layouts/layout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: Layout });

const page = usePage();
const products = ref(page.props.products);
</script>

<template>
    <div class="min-w-[1000px] max-w-full">
        <div v-for="product in products" :key="product.id" class="flex max-w-[250px]">
            <Card class="w-full">
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
                        <div class="text-sm">
                            <Chip label="Out of Stock" v-if="product.product.stock === 0" class="bg-red-600 text-white" />
                        </div>
                        <div class="space-x-3 whitespace-pre text-center">
                            <div v-if="product.product.description !== product.product.name">
                                {{ product.product.description }}
                            </div>
                        </div>
                    </div>
                </template>
                <template #content>
                    <div class="flex flex-row flex-wrap justify-between text-sm">
                        <p class="text-sm">Stock: {{ product.product.stock }}</p>
                        <p class="text-sm">Price: {{ product.product.price }}</p>
                    </div>
                </template>
                <template #footer>
                    <div class="flex flex-row-reverse gap-2 pt-5">
                        <Button label="Purchase" severity="primary" />
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
</template>

<style scoped></style>
