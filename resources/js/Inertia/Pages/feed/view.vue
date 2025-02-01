<script setup>
import {router, usePage} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import PostFeedCard from "@/Components/Post/PostFeedCard.vue";
import Layout from "@/Layouts/layout.vue";

defineOptions({layout: Layout})

const props = defineProps({post: Object})
const page = usePage()

const goBack = () => {
    router.visit(route('feed'), {only: ['posts'], preserveScroll: true})
}

</script>

<template>
    <div class="w-full sm:min-w-[600px]">
        <div class="flex flex-col items-center py-12">
            <div class="flex w-full max-w-md flex-col gap-12">
                <!-- BACK BUTTON -->
                <a @click.stop="goBack"
                   class="flex dark:text-slate-400 text-slate-500 cursor-pointer max-w-fit">
                    <span class="pi pi-chevron-left h-6 w-6"/>
                    <span>Back</span>
                </a>
                <div>
                    <PostFeedCard :post="props.post"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
