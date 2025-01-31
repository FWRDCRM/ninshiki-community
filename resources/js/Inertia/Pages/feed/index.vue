<script setup>
import {computed, onMounted, onUnmounted, ref, watch} from 'vue'
import {usePage} from '@inertiajs/vue3'
import Layout from "@/Layouts/layout.vue";
import PostFeedCard from "@/Components/Post/PostFeedCard.vue";
import {useIntersectionObserver} from '@vueuse/core'
import PostCreateModal from "@/Components/Post/PostCreateModal.vue";
import NoPostsAvailable from "@/Components/Post/NoPostsAvailable.vue";

defineOptions({layout: Layout})
const props = defineProps({posts: Object})

const target = ref(null)
const showCreateModal = ref(false)

const page = usePage()
const user = computed(() => page.props.auth.user)

const postsState = ref(props.posts.data)
const postsCurrentPage = ref(props.posts.meta.current_page)
const postsLastPage = ref(props.posts.meta.last_page)

const wsChannel = 'server.post.new'
const ws = NinshikiApp.$echo()

const hasRealtimeNewPosts = ref(true)

function refreshPostFeed() {
    window.scrollTo(0, 0)
    NinshikiApp.$router.reload({
        only: ['posts']
    })
}

onMounted(() => {
    if (ws) {
        ws.private(wsChannel)
            .listen('.new.post', (event) => {
                console.log(event)
            })
    } else {
        NinshikiApp.log('Websocket is disabled by system Administrator')
    }
})

onUnmounted(() => {
    if (ws) ws.disconnect();
})


NinshikiApp.$on('post-created', () => {
    NinshikiApp.$router.reload({
        only: ['posts'],
    });
})

watch(
    () => props.posts,
    (newPosts) => {
        postsState.value = [...newPosts.data];
        postsLastPage.value = newPosts.meta.last_page;
        postsCurrentPage.value = newPosts.meta.current_page;
    },
    {deep: true, immediate: true}
);


useIntersectionObserver(target, ([{isIntersecting}]) => {
    if (!isIntersecting) {
        return
    }
    if (postsLastPage.value > postsCurrentPage.value) {
        NinshikiApp.request().get(route('feed', {page: postsCurrentPage.value + 1})).then(response => {
            postsCurrentPage.value = response.data.meta.current_page
            postsLastPage.value = response.data.meta.last_page
            postsState.value = [...postsState.value, ...response.data.data]
        }).catch(error => {
            NinshikiApp.debug(error.message)
        })
    }

})

</script>

<template>
    <div class="w-full sm:min-w-[600px]">
        <PostCreateModal v-model:visible="showCreateModal" :modal-visible="showCreateModal"/>
        <div class="flex max-w-[580px] mx-auto bg-white border border-gray-300 rounded-lg shadow-md p-4"
             :class="{ 'mb-1': hasRealtimeNewPosts, 'mb-4': !hasRealtimeNewPosts}">
            <div class="flex items-center space-x-3 w-full">
                <img
                    :src="user.avatar ?? $ninshiki.uiAvatar(user.name)"
                    alt="Profile Picture" class="w-10 h-10 rounded-full">
                <div
                    class="cursor-pointer flex-grow p-2 border text-left bg-gray-300 border-gray-300 rounded-full outline-none w-full flex"
                    @click="showCreateModal=true">
                    <div class="flex w-full">
                        <span
                            class="text-sm font-normal text-gray-500">Who you want to recognize?, {{ user.name }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="hasRealtimeNewPosts" class="flex w-full mb-1 sticky top-1.5 pt-1 z-250">
            <div class="flex items-center w-full justify-center">
                <Button severity="info" raised rounded aria-label="new posts" @click="refreshPostFeed">
                    <AvatarGroup>
                        <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" shape="circle"/>
                        <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/asiyajavayant.png" shape="circle"/>
                        <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/onyamalimba.png" shape="circle"/>
                        <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/ionibowcher.png" shape="circle"/>
                        <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/xuxuefeng.png" shape="circle"/>
                    </AvatarGroup>
                </Button>
            </div>
        </div>
        <div class="content gap-3">
            <PostFeedCard v-for="post in postsState" :key="post.id" :post="post"/>
            <NoPostsAvailable v-if="postsState.length === 0"/>
            <!-- Load More Data   -->
            <div ref="target"></div>
        </div>
    </div>
</template>
<style scoped>

</style>
