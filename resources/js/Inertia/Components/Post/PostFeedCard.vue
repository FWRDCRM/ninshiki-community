<script setup>
import Image from 'primevue/image';
import {useToast} from "primevue/usetoast";


const props = defineProps({post: Object})
const toast = useToast();

function onComment() {
    toast.add({
        severity: 'info',
        summary: 'Sorry',
        detail: 'The functionality you clicked is not yet implemented.',
        group: 'br',
        life: 3000
    })
}

function onShare() {
    toast.add({
        severity: 'info',
        summary: 'Sorry',
        detail: 'The functionality you clicked is not yet implemented.',
        group: 'br',
        life: 3000
    })
}

function onLike() {
    toast.add({
        severity: 'info',
        summary: 'Sorry',
        detail: 'The functionality you clicked is not yet implemented.',
        group: 'br',
        life: 3000
    })
}

</script>

<template>
    <div class="max-w-sm md:max-w-xl md:min-w-xl mx-auto mb-2 bg-white border border-gray-300 rounded-lg shadow-md p-4">
        <!-- Post Header -->
        <div class="flex items-center gap-3">
            <Avatar
                :image="props?.post.posted_by?.avatar ?? `https://avatar.iran.liara.run/username?username=${props?.post.posted_by?.name}`"
                shape="circle" size="large" alt="Profile Picture"
                class="w-10 h-10 rounded-full"/>
            <div>
                <h3 class="text-sm font-semibold">{{ props?.post.posted_by.name }}</h3>
                <p class="text-xs text-gray-500">{{ props?.post.created_at_formatted }}</p>
            </div>
        </div>

        <!-- Post Content -->
        <div class="mt-4">
            <p class="text-gray-700 font-normal text-sm">
                {{ props.post.content }}
            </p>
        </div>

        <!-- Post Image (Optional) -->
        <div class="mt-4">
            <!-- 600x300 -->
            <Image :src="props.post.attachment_url" alt="Post Image" width="600" height="300"
                   class="w-full rounded-lg object-cover"/>
        </div>

        <!-- Post Statistics -->
        <div v-show="props.post.liked_by.length > 0" class="mt-4 pt-2 flex justify-between items-center">
            <!-- Like statistics on the left -->
            <div class="flex items-center space-x-2 text-gray-600">
                <i class="pi pi-heart-fill w-5 h-5 text-red-500"></i>
                <span v-if="props.post.liked_by.length === 1"
                      class="text-sm">{{ props.post.liked_by.length }} like</span>
                <span v-else class="text-sm">{{ props.post.liked_by.length }} likes</span>
            </div>
            <!-- Liked Users Avatars as Group on the right -->
            <div class="relative flex items-center">
                <AvatarGroup>
                    <Avatar v-for="(user, index) in props.post.liked_by" :key="index"
                            :image="user.avatar ?? `https://avatar.iran.liara.run/username?username=${user?.name}`"
                            alt="Liked User Avatar" shape="circle" class="object-cover"/>
                </AvatarGroup>
            </div>
        </div>

        <!-- Post Actions -->
        <div class="mt-4  border-t border-gray-200 space-x-2 pt-2  flex items-center justify-between text-gray-500">
            <!-- Show the liked button if the user liked the post  -->
            <button v-if="props.post.is_liked" @click="onLike" class="flex items-center space-x-1 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span>Liked</span>
            </button>

            <button v-else @click="onLike" class="flex items-center space-x-1 hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span>Like</span>
            </button>

            <button @click="onComment" class="flex items-center space-x-1 hover:text-blue-500">
                <i class="pi pi-comment"></i>
                <span>Comment</span>
            </button>

            <button @click="onShare" class="flex items-center space-x-1 hover:text-blue-500">
                <i class="pi pi-share-alt"></i>
                <span>Share</span>
            </button>
        </div>
    </div>


</template>

<style scoped>

</style>
