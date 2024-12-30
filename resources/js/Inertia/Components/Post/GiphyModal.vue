<script setup>

import {ref, watch} from "vue";

const props = defineProps({isVisible: Boolean})
const emit = defineEmits(['update:isVisible', 'gifSelected'])

const giphySearch = ref('');
const giphyResults = ref([]);


// Fetch trending GIFs as default
const fetchTrendingGifs = async () => {
    try {
        const response = await NinshikiApp.request().get('https://api.giphy.com/v1/gifs/trending', {
            params: {
                api_key: apiKey,
                limit: 30, // Limit results to 30
            },
        });
        giphyResults.value = response.data.data;
    } catch (error) {
        console.error('Error fetching trending GIFs:', error);
    }
};

// Search GIFs
const searchGiphy = async () => {
    if (giphySearch.value.trim() === '') {
        fetchTrendingGifs(); // If search is cleared, load trending GIFs
        return;
    }

    try {
        const response = await NinshikiApp.request().get('https://api.giphy.com/v1/gifs/search', {
            params: {
                api_key: apiKey,
                q: giphySearch.value,
                limit: 30, // Limit results to 30
            },
        });
        giphyResults.value = response.data.data;
    } catch (error) {
        console.error('Error searching GIFs:', error);
    }
};

// Clear search input
const clearSearch = () => {
    giphySearch.value = '';
    fetchTrendingGifs(); // Reset to trending GIFs
};

// Close modal
const closeModal = () => {
    emit('update:isVisible', false);
};

// Emit selected GIF URL back to the parent
const selectGif = (gifUrl) => {
    emit('gifSelected', gifUrl);
    closeModal();
};

// Fetch trending GIFs when the modal is opened
watch(
    () => props.isVisible,
    (newVal) => {
        if (newVal) fetchTrendingGifs();
    }
);

</script>

<template>
    <Dialog
        modal
        :draggable="false"
        :blockScroll="true"
        :closeOnEscape="true"
        position="center"
        :style="{ width: '35rem', height: '30rem' }"
    >
        <!-- Header Slot -->
        <template #header>
            <div class="flex items-center justify-between w-full">
                <span>Search Giphy</span>
            </div>
        </template>

        <!-- Modal Content -->
        <div class="p-4 bg-white text-gray-900">
            <!-- Sticky Search Input -->
            <div class="sticky top-0 z-10 p-2 bg-white">
                <div class="relative w-full">
                    <!-- Search Input -->
                    <InputText
                        v-model="giphySearch"
                        placeholder="Search GIFs..."
                        class="w-full pr-10bg-white text-gray-900 border-gray-300"
                        @input="searchGiphy"
                    />
                    <!-- Clear Button Inside Input -->
                    <Button
                        icon="pi pi-times"
                        v-if="giphySearch.trim()"
                        class="p-button-text p-button-rounded absolute top-1/2 right-2 -translate-y-1/2"
                        @click="clearSearch"
                        aria-label="Clear Search"
                    />
                </div>
            </div>

            <!-- GIF Grid -->
            <div class="grid grid-cols-3 gap-4 overflow-y-auto" style="height: 20rem;">
                <img
                    v-for="gif in giphyResults"
                    :key="gif.id"
                    :src="gif.images.fixed_width.url"
                    alt="GIF"
                    class="cursor-pointer rounded-lg hover:opacity-75"
                    @click="selectGif(gif.images.fixed_width.url)"
                />
            </div>
        </div>

    </Dialog>
</template>

<style scoped>

</style>
