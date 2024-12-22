<script setup>
import {computed} from 'vue'
import {usePage} from '@inertiajs/vue3'
import Layout from "@/Layouts/layout.vue";
import PostFeedCard from "@/Components/Post/PostFeedCard.vue";

const page = usePage()
const user = computed(() => page.props.auth.user)
defineProps({posts: Object})
defineOptions({layout: Layout})
</script>

<template>
    <div>
        <div class="max-w-xl mx-auto bg-white border border-gray-300 rounded-lg shadow-md p-4">
            <div class="flex items-center space-x-3">
                <img src="https://via.placeholder.com/40" alt="Profile Picture" class="w-10 h-10 rounded-full">
                <div
                    class="cursor-pointer flex-grow p-2 border text-left bg-gray-300 border-gray-300 rounded-full outline-none">
                    <div class="flex">
                        <span class="text-sm font-normal text-gray-500">Who you want to recognize?, {{user.name}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="content gap-3">
            <div v-for="post in posts?.data">
                <PostFeedCard :key="post.id" :post="post"/>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
