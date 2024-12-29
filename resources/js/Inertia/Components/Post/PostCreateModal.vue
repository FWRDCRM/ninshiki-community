<script setup>
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({toggleCancelPost: Function})
const page = usePage()

const selectedGif = ref('https://picsum.photos/300/200')
const isGifLoaded = ref(false);
const postContent = ref('');

// Function to be called when the GIF has loaded
const onGifLoad = () => {
    isGifLoaded.value = true; // Set to true once the GIF is loaded
};

const onGifRemoved = () => {
    isGifLoaded.value = false;
    selectedGif.value = '';
}

</script>

<template>
    <Dialog modal :draggable="false"
            :blockScroll="true"
            :closeOnEscape="false"
            position="center"
            :style="{ width: '35rem' }">
        <template #header>
            <div class="flex items-center justify-center w-full">
                <span>Inspiring Recognition: Celebrate Success</span>
            </div>
        </template>

        <!-- Modal Content -->
        <div>
            <!-- User Info -->
            <div class="flex items-center mb-4">
                <Avatar
                    image="https://via.placeholder.com/40"
                    shape="circle"
                    size="large"
                />
                <span class="ml-3 font-medium">John Doe</span>
            </div>

            <!-- Post Input -->
            <div class="mb-4">
                <Textarea
                    rows="4"
                    autoResize
                    placeholder="Who you want to recognize?"
                    class="w-full"
                    v-model="postContent"
                />
                <!-- Character Counter -->
                <div class="text-right text-sm text-gray-500">
                    {{ postContent.length }} / 500
                </div>
            </div>


            <!-- Placeholder for GIF -->
            <!-- Placeholder for GIF -->
            <div v-if="selectedGif" class="mt-4 relative w-[300px] h-[200px] mx-auto group">
                <Image
                    :src="selectedGif"
                    alt="Selected GIF"
                    class="w-full h-full rounded-lg object-cover"
                    @load="onGifLoad"
                />
                <!-- Remove Button (hidden by default, shown on hover) -->
                <Button
                    icon="pi pi-times"
                    v-tooltip.top="'Remove GIF'"
                    class="remove-gif-btn p-button-rounded p-button-text text-white bg-red-500 absolute top-2 right-2 hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity"
                    @click="onGifRemoved"
                    v-if="isGifLoaded"
                />
            </div>

            <!-- Media Option -->
            <div v-else class="flex items-center mt-4">
                <Button
                    label="GIF"
                    icon="pi pi-gift"
                    class="p-button-text text-gray-600 hover:text-blue-500"
                    @click="selectGif"
                />
            </div>
        </div>



        <template #footer>
            <Button label="Post" icon="pi pi-send" iconPos="right" />
        </template>
    </Dialog>
</template>

<style scoped>

</style>
