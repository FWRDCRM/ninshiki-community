<script setup>
import {usePage} from "@inertiajs/vue3";
import {reactive, ref, watch} from "vue";
import GiphyModal from "@/Components/Post/GiphyModal.vue";

const page = usePage()

const props = defineProps({modalVisible: Boolean})

const employees = ref([]);

const selectedGif = ref(null)
const isGifLoaded = ref(false);
const postContent = ref('');
const showGiphyModal = ref(false);

const formState = reactive({
    content: null,
    employees: [],
    isSubmitting: false,
})

// Function to be called when the GIF has loaded
const onGifLoad = () => {
    isGifLoaded.value = true; // Set to true once the GIF is loaded
};

const onGifRemoved = () => {
    isGifLoaded.value = false;
    selectedGif.value = null;
}

// Handle GIF selection from Giphy modal
const selectGifFromGiphy = (gifUrl) => {
    selectedGif.value = gifUrl;
    isGifLoaded.value = false; // Reset loaded state
}

const selectGif = () => {
    showGiphyModal.value = true;
}


// Fetch trending Employee list when the modal is opened
watch(
    () => props.modalVisible,
    (newVal) => {
        if (newVal) {
            NinshikiApp.request().get(route('employees')).then((response) => {
                employees.value = response.data.data;
            })
        }
    }
);

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
        <!-- Giphy Modal Component -->
        <GiphyModal
            v-model:visible="showGiphyModal"
            :is-visible="showGiphyModal"
            @gifSelected="selectGifFromGiphy"
        />

        <!-- Modal Content -->
        <div class="my-5 gap-3 space-y-3">
            <!-- User Info -->
            <div class="flex w-full gap-3">
                <FloatLabel class="w-full md:w-80">
                    <MultiSelect id="over_label" v-model="formState.employees" :options="employees" optionLabel="name"
                                 filter class="w-full" :show-toggle-all="false"
                                 :max-selected-labels=3
                                 display="chip"
                    >
                        <template #option="slotProps">
                            <div class="flex items-center">
                                <Avatar :alt="slotProps.option.name"
                                        :image="slotProps.option.avatar ?? `https://ui-avatars.com/api/?name=${slotProps.option.name}&rounded=true&background=random`"
                                        class="mr-2" style="width: 18px"/>
                                <div>{{ slotProps.option.name }}</div>
                            </div>
                        </template>
                        <template #dropdownicon>
                            <i class="pi pi-users"/>
                        </template>
                        <template #header>
                            <div class="font-medium px-3 py-2">Available Employees</div>
                        </template>
                    </MultiSelect>
                    <label for="over_label">Who you want to recognize?</label>
                </FloatLabel>
            </div>

            <!-- Post Input -->
            <div class="mb-4">
                <Textarea
                    rows="4"
                    autoResize
                    class="w-full"
                    v-model="postContent"
                />
                <!-- Character Counter -->
                <div class="text-right text-sm text-gray-500">
                    {{ postContent.length }} / 500
                </div>
            </div>


            <!-- Placeholder for GIF -->
            <div v-if="selectedGif" class="mt-4 mx-auto relative  w-[300px] h-[200px]">
                <!-- GIF Image -->
                <div class="relative inline-block group">
                    <Image
                        :src="selectedGif"
                        alt="Selected GIF"
                        class="rounded-lg object-cover "
                        @load="onGifLoad"
                    />

                    <!-- Remove Button (Now Sits Directly on GIF) -->
                    <Button
                        icon="pi pi-times"
                        v-tooltip.top="'Remove GIF'"
                        class="remove-gif-btn p-button-rounded p-button-text text-white bg-red-500 absolute top-2 right-2 hover:bg-red-600 z-10 opacity-0 group-hover:opacity-100 transition-opacity"
                        @click="onGifRemoved"
                        v-if="isGifLoaded"
                    />
                </div>
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
            <Button label="Post" icon="pi pi-send" iconPos="right"/>
        </template>
    </Dialog>
</template>

<style scoped>

</style>
