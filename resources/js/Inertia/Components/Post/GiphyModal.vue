<script setup>
import giphyAttributeImage from '@assets/image/attribution/giphy/PoweredBy_200px-White_HorizText.png';
import _ from 'lodash';
import { ref, watch } from 'vue';

const props = defineProps({ isVisible: Boolean });
const emit = defineEmits(['gifSelected']);

const giphySearch = ref('');
const giphyResults = ref([]);
const giphyOffset = ref(0);

// Fetch trending GIFs as default
const fetchTrendingGifs = async () => {
    try {
        const response = await NinshikiApp.request().get(route('gif.trending'));
        giphyResults.value = response.data.data;
    } catch (error) {
        NinshikiApp.debug('Error fetching trending GIFs:', error);
    }
};

// Search GIFs
const searchGiphy = async () => {
    if (_.trim(giphySearch.value) === '') {
        await fetchTrendingGifs(); // If search is cleared, load trending GIFs
        return;
    }

    try {
        const response = await NinshikiApp.request().get(
            route('gif.search', {
                search: giphySearch.value,
                offset: giphyOffset.value,
            }),
        );
        giphyResults.value = response.data.data;
    } catch (error) {
        NinshikiApp.debug('Error searching GIFs:', error);
    }
};

// Clear search input
const clearSearch = () => {
    giphySearch.value = '';
    fetchTrendingGifs(); // Reset to trending GIFs
};

// Close modal
const closeModal = () => {
    emit('update:visible', false);
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
    },
);
</script>

<template>
    <Dialog modal :draggable="false" :blockScroll="true" :closeOnEscape="true" position="center" :style="{ width: '35rem' }">
        <!-- Header Slot -->
        <template #header>
            <div></div>
        </template>

        <!-- Modal Content -->
        <div class="bg-white p-4 text-gray-900">
            <!-- Sticky Search Input -->
            <div class="sticky top-0 z-10 bg-white p-2">
                <div class="relative w-full">
                    <!-- Search Input -->
                    <InputText
                        v-model="giphySearch"
                        placeholder="Search GIFs..."
                        class="w-full border-gray-300 bg-white pr-10 text-gray-900"
                        @keydown.enter="searchGiphy"
                    />
                    <!-- Clear Button Inside Input -->
                    <Button
                        icon="pi pi-times"
                        v-if="giphySearch.trim()"
                        class="p-button-text p-button-rounded absolute right-10 top-1/2 -translate-y-1/2"
                        @click="clearSearch"
                        aria-label="Clear Search"
                    />
                    <!-- Search Button Inside Input -->
                    <Button
                        icon="pi pi-search"
                        class="p-button-text p-button-rounded absolute right-2 top-1/2 -translate-y-1/2"
                        @click="searchGiphy"
                        aria-label="Search GIFs"
                    />
                </div>
            </div>

            <!-- GIF Grid -->
            <div class="grid grid-cols-3 gap-4 overflow-y-auto" style="height: 20rem">
                <Image
                    v-for="gif in giphyResults"
                    :key="gif.id"
                    :src="gif.images.fixed_width.url"
                    alt="GIF"
                    class="cursor-pointer rounded-lg hover:opacity-75"
                    @click="selectGif(gif.images.fixed_width.url)"
                />
            </div>
        </div>

        <template #footer>
            <div class="flex h-full w-full justify-center py-2">
                <a href="https://giphy.com" target="_blank" rel="noopener noreferrer" class="flex h-full">
                    <Image :src="giphyAttributeImage" />
                </a>
            </div>
        </template>
    </Dialog>
</template>

<style scoped></style>
