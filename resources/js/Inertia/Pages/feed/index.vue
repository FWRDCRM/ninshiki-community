<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Layout from "@/Layouts/layout.vue";
import PostFeedCard from "@/Components/Post/PostFeedCard.vue";

const page = usePage()
const user = computed(() => page.props.auth.user)
defineProps({ posts: Object })
defineOptions({ layout: Layout })
</script>

<template>
<div>
    <div class="post card here">

    </div>
    <div class="content gap-3">
        <div v-for="post in posts?.data">
            <PostFeedCard  :key="post.id" :post="post"/>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>
