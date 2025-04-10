<script setup>
import PostFeedCard from '@/Components/Post/PostFeedCard.vue';
import Layout from '@/Layouts/layout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineOptions({ layout: Layout });

const props = defineProps({ post: Object });
const page = usePage();

const goBack = () => {
    router.visit(route('feed'), { only: ['posts'], preserveScroll: true });
};
</script>

<template>
    <div class="w-full sm:min-w-[600px]">
        <div class="flex flex-col items-center py-12">
            <div class="flex w-full max-w-md flex-col gap-12">
                <!-- BACK BUTTON -->
                <a @click.stop="goBack" class="flex max-w-fit cursor-pointer text-slate-500 dark:text-slate-400">
                    <span class="pi pi-chevron-left h-6 w-6" />
                    <span>Back</span>
                </a>
                <div>
                    <PostFeedCard :post="props.post" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
