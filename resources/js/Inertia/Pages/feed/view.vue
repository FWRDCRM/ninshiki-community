<script setup>
import {usePage, Link} from "@inertiajs/vue3";
import PostFeedCard from "@/Components/Post/PostFeedCard.vue";

const props = defineProps({post: Object})
const page = usePage()

</script>

<template>
    <div class="w-full sm:min-w-[600px]">
        <div class="flex flex-col items-center py-12">
            <div class="flex w-full max-w-md flex-col gap-12">
                <!-- BACK BUTTON -->
                <Link :href="route('feed')" :only="['posts']"
                      class="flex flex dark:text-slate-400 text-slate-500 cursor-pointer">
                    <span class="pi pi-chevron-left h-6 w-6"/>
                    <span>Back</span>
                </Link>
                <div>
                    <PostFeedCard :post="props.post"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
