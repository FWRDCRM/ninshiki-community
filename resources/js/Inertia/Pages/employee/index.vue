<script setup>
import Layout from "@/Layouts/layout.vue";
import {computed, ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Select from 'primevue/select';
import {useArrayMap} from "@vueuse/core";

defineOptions({layout: Layout})


const page = usePage();
const user = computed(() => page.props.auth.user)
const employees = ref(page.props.employees);
const isGiftFeatureEnabled = ref(page.props.gift_feature_enable);
const giftType = ref(page.props.gift_type)
const giftTypeOption = computed(() => useArrayMap(giftType, o => o.toUp))


const isGiftModalOpen = ref(false)


const giftForm = useForm({
    type: undefined,
    amount: undefined
})


</script>

<template>
    <div class="max-w-screen-lg mx-auto mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 w-full">
        <Dialog v-model:visible="isGiftModalOpen" modal header="Send Gift" :style="{ width: '20rem' }">
            <div class="flex flex-col gap-4 mb-4">
                <label for="gift_type" class="font-semibold w-auto">Select Type of Gift</label>
                <Select v-model="giftForm.type" :options="giftType" placeholder="Select type of gift" class="w-ful" :pt="{
                   optionLabel: {
                       class: 'capitalize'
                   },
                   label: {
                     class: 'capitalize'
                   }
                }" required/>
            </div>
            <div class="flex flex-col gap-4 mb-8">
                <label for="amount" class="font-semibold w-24">Amount</label>
                <InputNumber v-model="giftForm.amount" id="amount" :min="0" class="flex-auto" autocomplete="off" :useGrouping="false" required/>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="isGiftModalOpen = false"></Button>
                <Button type="button" icon="pi pi-send" label="Send" @click="isGiftModalOpen = false"></Button>
            </div>
        </Dialog>

        <div v-for="employee in employees" :key="employee.id" class="flex w-full">
            <Card class="min-w-48 w-full">
                <template #header>
                    <div class="flex flex-wrap items-center justify-center mt-4">
                        <Avatar :alt="employee.name" :image="employee.avatar ?? $ninshiki.uiAvatar(employee.name)"
                                shape="circle" size="xlarge"/>
                    </div>
                </template>
                <template #title>
                    <div class="text-balance text-center">{{ employee.name }}</div>
                </template>
                <template #subtitle>
                    <div class="text-center space-x-3">
                        <div class="italic">
                            @{{ employee.username }}
                        </div>
                        <div>
                            {{ employee.email }}
                        </div>
                    </div>
                </template>
                <template #footer>
                    <div class="text-center">
                        <Button v-if="isGiftFeatureEnabled && user?.id !== employee.id" icon="pi pi-gift" variant="text" rounded aria-label="Gift"
                                v-tooltip.bottom="'Send Gift'" @click="isGiftModalOpen = true" />
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<style scoped>

</style>
