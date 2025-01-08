<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import GiphyModal from "@/Components/Post/GiphyModal.vue";
import _ from "lodash";

const page = usePage()

const props = defineProps({modalVisible: Boolean})
const emit = defineEmits([]);

const employees = ref([]);
const points = ref([
    {
        name: '3 coins',
        value: 3
    },
    {
        name: '5 coins',
        value: 5
    },
    {
        name: '10 coins',
        value: 10
    }
])

const isGifLoaded = ref(false);
const showGiphyModal = ref(false);

const formState = useForm({
    content: undefined,
    points: undefined,
    employees: [],
    gif: undefined,
    reset: () => {
        this.employees = [];
        this.points = undefined;
        this.content = undefined;
        this.gif = undefined;
    }
})

// function for handling the content max length per points
const maxLengthPerPoints = computed(() => {
    switch (formState.points?.value) {
        case 5:
            return 310;
        case 10:
            return 480;
        default:
            return 210;
    }
})

// Function to be called when the GIF has loaded
const onGifLoad = () => {
    isGifLoaded.value = true; // Set to true once the GIF is loaded
};

const onGifRemoved = () => {
    isGifLoaded.value = false;
    formState.gif = null;
}

// Handle GIF selection from Giphy modal
const selectGifFromGiphy = (gifUrl) => {
    formState.gif = gifUrl;
    isGifLoaded.value = false; // Reset loaded state
}

const selectGif = () => {
    showGiphyModal.value = true;
}

const validateForm = () => {
    let isValid = true;
    formState.clearErrors()
    if (formState.employees.length === 0) {
        formState.setError('employees', 'Employee field is required');
        isValid = false;
    }
    if (formState.content?.length === 0 || formState.content === undefined || formState.content === null) {
        formState.setError('content', 'Content field is required');
        isValid = false;
    }
    if (formState.points === undefined || formState.points === null) {
        formState.setError('points', 'Points field is required');
        isValid = false;
    }
    return isValid;
}

const createPost = () => {
    const isFormValid = validateForm();
    if (!isFormValid) return;
    const data = new FormData();
    formState.processing = true;
    data.append("post_content", formState.content);
    data.append("points", formState.points?.value);
    if (formState.gif !== undefined && formState.gif !== null) {
        data.append("attachment_type", 'gif')
        data.append("gif_url", formState.gif);
    }
    data.append('type', 'user')
    for (let i = 0; i < formState.employees.length; i++) {
        data.append(`recipient_id[${i}]`, formState.employees[i]?.id)
    }

    NinshikiApp.request().post(route('feeds.create-post'), data).then((data) => {
        NinshikiApp.success("🎉 Your recognition post for your colleague has been successfully posted! 🌟", "Successfully Posted")
        NinshikiApp.$emit('post-created')
        // Clear the form value
        formState.reset()
        // close modal
        emit('update:visible', false)
    }).catch(({response}) => {
        if (response.status === 429) {
            NinshikiApp.warning(response.data.error.message, response.statusText)
        }
        if (response.status === 422) {
            NinshikiApp.warning(response.data.error.message, response.statusText)
            // show error message per field
            const errorFields = response.data.error.errors
        }
    }).finally(() => {
        formState.processing = false;
    })
}


// Fetch trending Employee list when the modal is opened
watch(
    () => props.modalVisible,
    (newVal) => {
        if (newVal) {
            NinshikiApp.request().get(route('employees')).then((response) => {
                const data = response.data;
                // remove current user info from the list
                _.remove(data, (data) => data.id === page.props.auth.user.id);
                employees.value = data;
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
            :style="{ width: '40rem' }">
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
            <div class="flex w-full flex-row gap-3">
                <div class="flex flex-col gap-1">
                    <FloatLabel class="w-full md:w-80">
                        <MultiSelect id="employees" v-model="formState.employees" :options="employees"
                                     optionLabel="name"
                                     filter class="w-full" :show-toggle-all="false"
                                     :max-selected-labels=3
                                     display="chip"
                                     required
                                     :invalid="!!formState.errors?.employees"
                        >
                            <template #option="slotProps">
                                <div class="flex items-center">
                                    <Avatar :alt="slotProps.option.name"
                                            :image="slotProps.option.avatar ?? $ninshiki.uiAvatar(slotProps.option.name)"
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
                        <label for="employees">Who you want to recognize?</label>
                    </FloatLabel>
                    <Message v-if="formState.errors?.employees" severity="error" size="small" variant="simple">
                        {{ formState.errors?.employees }}
                    </Message>
                </div>

                <div class="flex flex-col gap-1">
                    <FloatLabel class="w-full md:w-56">
                        <Select :showClear="true" :checkmark="true" v-model="formState.points" inputId="points"
                                :invalid="!!formState.errors?.points"
                                :options="points" optionLabel="name" class="w-full"/>
                        <label for="points">Points you want to reward?</label>
                    </FloatLabel>
                    <Message v-if="formState.errors?.points" severity="error" size="small" variant="simple">
                        {{ formState.errors?.points }}
                    </Message>
                </div>
            </div>

            <!-- Post Input -->
            <div class="mb-4">
                <Textarea
                    rows="4"
                    autoResize
                    class="w-full"
                    placeholder="Recognize someone or your team?"
                    v-model="formState.content"
                    :maxlength="maxLengthPerPoints"
                    required
                    :invalid="!!formState.errors?.content"
                />
                <!-- Character Counter -->
                <div class="flex flex-row w-full">
                    <div class="text-left text-sm mr-auto">
                        <Message v-if="formState.errors?.content" severity="error" size="small" variant="simple">
                            {{ formState.errors?.content }}
                        </Message>
                    </div>
                    <div class="text-right text-sm ml-auto text-gray-500">
                        {{ formState.content?.length ?? 0 }} / {{ maxLengthPerPoints }}
                    </div>
                </div>
            </div>


            <!-- Placeholder for GIF -->
            <div v-if="formState.gif" class="mt-4 mx-auto relative  w-[300px] h-[200px]">
                <!-- GIF Image -->
                <div class="relative inline-block group">
                    <Image
                        :src="formState.gif"
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
        </div>


        <template #footer>
            <div class="flex flex-row w-full">
                <!-- Media Option -->
                <Button
                    label="Add GIF"
                    icon="pi pi-gift mr-auto"
                    class="p-button-text text-gray-600 hover:text-blue-500"
                    @click="selectGif"
                    v-if="!formState.gif"
                />
                <Button label="Post" class="ml-auto" icon="pi pi-send" iconPos="right" @click="createPost"
                        :disabled="formState.processing" :loading="formState.processing"/>
            </div>

        </template>
    </Dialog>
</template>

<style scoped>

</style>
