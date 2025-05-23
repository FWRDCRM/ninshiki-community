<script setup>
import RecognizedDialog from '@/Components/Post/RecognizedDialog.vue';
import { usePage } from '@inertiajs/vue3';
import linkifyHtml from 'linkify-html';
import 'linkify-plugin-hashtag';
import 'linkify-plugin-mention';
import _ from 'lodash';
import Image from 'primevue/image';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    post: Object,
});

const page = usePage();

const showRecognizedUserModal = ref(false);

const employees = ref([]);

const options = {
    formatHref: {
        hashtag: () => `#`,
        mention: (href) => route('employees.list'),
    },
    className: () => 'text-blue-400',
    tagName: {
        hashtag: 'span',
    },
    render: ({ tagName, attributes, content }) => {
        let _attributes = '';
        for (const attr in attributes) {
            _attributes += ` ${attr}=${attributes[attr]}`;
        }
        return `<${tagName}${_attributes} >${content}</${tagName}>`;
    },
};

function onComment() {
    NinshikiApp.info('The functionality you clicked is not yet implemented.', 'Sorry');
}

function onShare() {
    NinshikiApp.info('The functionality you clicked is not yet implemented.', 'Sorry');
}

async function onLike() {
    const resp = await NinshikiApp.request().patch(route('feeds.like-unlike', { id: props.post.id }));
    const user = page.props.auth.user;
    if (resp.status === 200 && resp.data.success) {
        if (props.post.is_liked) {
            props.post.is_liked = false;
            const index = _.findIndex(props.post.liked_by, function (o) {
                return o.id === user.id;
            });
            props.post.liked_by.splice(index, 1);
        } else {
            props.post.is_liked = true;
            props.post.liked_by.push({
                id: user.id,
                avatar: user.avatar,
                name: user.name,
            });
        }
    }
}
</script>

<template>
    <div class="md:min-w-xl mx-auto mb-2 max-w-sm rounded-lg border border-gray-300 bg-white p-4 shadow-md md:max-w-xl">
        <!-- Post Recognized user -->
        <RecognizedDialog v-model:visible="showRecognizedUserModal" :users="props.post.recipients" />
        <!-- Post Header -->
        <div class="flex items-center gap-3">
            <Avatar
                :image="props.post.posted_by?.avatar ?? $ninshiki.uiAvatar(props.post.posted_by?.name)"
                shape="circle"
                size="large"
                alt="Profile Picture"
                class="h-10 w-10 rounded-full"
            />
            <div>
                <div class="flex flex-row items-center gap-3">
                    <h3 class="text-sm font-semibold">{{ post.posted_by.name }}</h3>
                    <i class="pi pi-sparkles" />
                    <AvatarGroup @click.stop="showRecognizedUserModal = true" class="cursor-pointer">
                        <Avatar
                            v-for="(_user, index) in post.recipients"
                            :key="index"
                            :image="_user.avatar ?? $ninshiki.uiAvatar(_user?.name)"
                            :alt="_user.name"
                            shape="circle"
                            class="object-cover text-sm"
                        />
                    </AvatarGroup>
                </div>
                <p class="text-xs text-gray-500">{{ post.created_at_formatted }}</p>
            </div>
        </div>

        <!-- Post Content -->
        <div class="mt-4">
            <span class="whitespace-pre-wrap text-sm font-normal text-gray-700" v-html="linkifyHtml(post.content, options)"> </span>
        </div>

        <!-- Post Image (Optional) -->
        <div class="mt-4">
            <!-- 600x300 -->
            <Image
                :src="post.attachment_url"
                v-if="post.attachment_url"
                alt="Post Image"
                width="600"
                height="300"
                class="w-full rounded-lg object-cover"
            />
        </div>

        <!-- Post Statistics -->
        <div v-show="post.liked_by.length > 0" class="mt-4 flex items-center justify-between pt-2">
            <!-- Like statistics on the left -->
            <div class="flex items-center space-x-2 text-gray-600">
                <i class="pi pi-heart-fill h-5 w-5 text-red-500"></i>
                <span v-if="post.liked_by.length === 1" class="text-sm">{{ post.liked_by.length }} like</span>
                <span v-else class="text-sm">{{ post.liked_by.length }} likes</span>
            </div>
            <!-- Liked Users Avatars as Group on the right -->
            <div class="relative flex items-center">
                <AvatarGroup>
                    <Avatar
                        v-for="(_user, index) in post.liked_by"
                        :key="index"
                        :image="_user.avatar ?? $ninshiki.uiAvatar(_user?.name)"
                        alt="Liked User Avatar"
                        shape="circle"
                        class="object-cover"
                    />
                </AvatarGroup>
            </div>
        </div>

        <!-- Post Actions -->
        <div class="mt-4 flex items-center justify-between space-x-2 border-t border-gray-200 pt-2 text-gray-500">
            <!-- Show the liked button if the user liked the post  -->
            <button v-if="post.is_liked" @click.stop="onLike" class="flex items-center space-x-1 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                    />
                </svg>
                <span>Liked</span>
            </button>

            <button v-else @click.stop="onLike" class="flex items-center space-x-1 hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                    />
                </svg>
                <span>Like</span>
            </button>

            <button @click.stop="onComment" class="flex items-center space-x-1 hover:text-blue-500">
                <i class="pi pi-comment"></i>
                <span>Comment</span>
            </button>

            <button @click.stop="onShare" class="flex items-center space-x-1 hover:text-blue-500">
                <i class="pi pi-share-alt"></i>
                <span>Share</span>
            </button>
        </div>
    </div>
</template>

<style scoped></style>
