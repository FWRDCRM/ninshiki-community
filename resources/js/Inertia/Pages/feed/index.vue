<script setup>
import NoPostsAvailable from '@/Components/Post/NoPostsAvailable.vue';
import PostCreateModal from '@/Components/Post/PostCreateModal.vue';
import PostFeedCard from '@/Components/Post/PostFeedCard.vue';
import Layout from '@/Layouts/layout.vue';
import { usePage } from '@inertiajs/vue3';
import { useIntersectionObserver } from '@vueuse/core';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { route } from 'ziggy-js';

defineOptions({ layout: Layout });
const props = defineProps({ posts: Object });

const target = ref(null);
const showCreateModal = ref(false);

const page = usePage();
const user = computed(() => page.props.auth.user);

const postsState = ref(props.posts.data);
const postsCurrentPage = ref(props.posts.meta.current_page);
const postsLastPage = ref(props.posts.meta.last_page);

const wsChannel = 'server.post.new';
const ws = NinshikiApp.$echo();

const hasRealtimeNewPosts = ref(false);
const realtimeNewPosts = ref([]);

function refreshPostFeed() {
    window.scrollTo(0, 0);
    NinshikiApp.$router.reload({
        only: ['posts'],
        onSuccess: () => {
            NinshikiApp.log('Feeds Refreshed.');
            hasRealtimeNewPosts.value = false;
        },
    });
}

onMounted(() => {
    if (ws) {
        ws.private(wsChannel).listen('.new.post', (event) => {
            console.log(event);
            hasRealtimeNewPosts.value = true;
            realtimeNewPosts.value.push(event.meta.post_by);
        });
    } else {
        NinshikiApp.log('Websocket has disabled by system Administrator');
    }
});

onUnmounted(() => {
    if (ws) ws.disconnect();
});

NinshikiApp.$on('post-created', () => {
    NinshikiApp.$router.reload({
        only: ['posts', 'wallet_credit', 'wallet_earned'],
    });
});

watch(
    () => props.posts,
    (newPosts) => {
        postsState.value = [...newPosts.data];
        postsLastPage.value = newPosts.meta.last_page;
        postsCurrentPage.value = newPosts.meta.current_page;
    },
    { deep: true, immediate: true },
);

useIntersectionObserver(target, ([{ isIntersecting }]) => {
    if (!isIntersecting) {
        return;
    }
    if (postsLastPage.value > postsCurrentPage.value) {
        NinshikiApp.request()
            .get(route('feed', { page: postsCurrentPage.value + 1 }))
            .then((response) => {
                postsCurrentPage.value = response.data.meta.current_page;
                postsLastPage.value = response.data.meta.last_page;
                postsState.value = [...postsState.value, ...response.data.data];
            })
            .catch((error) => {
                NinshikiApp.debug(error.message);
            });
    }
});
</script>

<template>
    <div class="w-full sm:min-w-[600px]">
        <PostCreateModal v-model:visible="showCreateModal" :modal-visible="showCreateModal" />
        <div
            class="mx-auto flex max-w-[580px] rounded-lg border border-gray-300 bg-white p-4 shadow-md"
            :class="{ 'mb-1': hasRealtimeNewPosts, 'mb-2': !hasRealtimeNewPosts }"
        >
            <div class="flex w-full items-center space-x-3">
                <img :src="user.avatar ?? $ninshiki.uiAvatar(user.name)" alt="Profile Picture" class="h-10 w-10 rounded-full" />
                <div
                    class="flex w-full flex-grow cursor-pointer rounded-full border border-gray-300 bg-gray-300 p-2 text-left outline-none"
                    @click="showCreateModal = true"
                >
                    <div class="flex w-full">
                        <span class="text-sm font-normal text-gray-500">Who you want to recognize?, {{ user.name }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!--  New Realtime post      -->
        <div v-if="hasRealtimeNewPosts" class="sticky top-1.5 z-[250] mb-1 flex w-full pt-1" v-tooltip.bottom="'New Posts'">
            <div class="flex w-full items-center justify-center">
                <Button size="small" severity="info" raised rounded aria-label="new posts" @click="refreshPostFeed">
                    <i class="pi pi-arrow-circle-up" />
                    <AvatarGroup>
                        <Avatar
                            v-for="(_user, index) in realtimeNewPosts"
                            :key="index"
                            :image="_user.avatar ?? $ninshiki.uiAvatar(_user?.name)"
                            class="object-cover"
                            shape="circle"
                        />
                    </AvatarGroup>
                </Button>
            </div>
        </div>
        <div class="content gap-3">
            <PostFeedCard v-for="post in postsState" :key="post.id" :post="post" class="cursor-default transition-colors duration-100 ease-in-out" />
            <NoPostsAvailable v-if="postsState.length === 0" />
            <!-- Load More Data   -->
            <div ref="target"></div>
        </div>
    </div>
</template>
<style scoped></style>
