<script setup>
import {computed, onMounted, onUnmounted, ref, watch} from 'vue'
import {router, usePage} from '@inertiajs/vue3'
import {route} from "ziggy-js";
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

const hasRealtimeNewPosts = ref(false)
const realtimeNewPosts = ref([])

function refreshPostFeed() {
    window.scrollTo(0, 0)
    NinshikiApp.$router.reload({
        only: ['posts'],
        onSuccess: () => {
            NinshikiApp.log('Feeds Refreshed.')
            hasRealtimeNewPosts.value = false
        }
    })
}

onMounted(() => {
    if (ws) {
        ws.private(wsChannel)
            .listen('.new.post', (event) => {
                console.log(event)
                hasRealtimeNewPosts.value = true
                realtimeNewPosts.value.push(event.meta.post_by)
            })
    } else {
        NinshikiApp.log('Websocket has disabled by system Administrator')
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
             :class="{ 'mb-1': hasRealtimeNewPosts, 'mb-2': !hasRealtimeNewPosts}">
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
        <!--  New Realtime post      -->
        <div v-if="hasRealtimeNewPosts" class="flex w-full mb-1 sticky top-1.5 pt-1 z-[250]"
             v-tooltip.bottom="'New Posts'">
            <div class="flex items-center w-full justify-center">
                <Button size="small" severity="info" raised rounded aria-label="new posts" @click="refreshPostFeed">
                    <i class="pi pi-arrow-circle-up"/>
                    <AvatarGroup>
                        <Avatar v-for="(_user, index) in realtimeNewPosts" :key="index"
                                :image="_user.avatar ?? $ninshiki.uiAvatar(_user?.name)" class="object-cover"
                                shape="circle"/>
                    </AvatarGroup>
                </Button>
            </div>
        </div>
        <div class="content gap-3">
            <PostFeedCard v-for="post in postsState" :key="post.id" :post="post"
                          class="hover:bg-slate-50/30 cursor-pointer transition-colors  duration-100 ease-in-out"
                          @click.stop="$ninshiki.$router.visit(route('feed.show', post.id), {
            only: ['post'],
            preserveState: true
        })"
            />
            <NoPostsAvailable v-if="postsState.length === 0"/>
            <!-- Load More Data   -->
            <div ref="target"></div>
        </div>
    </div>
</template>
<style scoped>

</style>
