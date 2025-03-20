<script setup>
import Layout from "@/Layouts/layout.vue";
import {computed, ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Select from 'primevue/select';

defineOptions({layout: Layout})


const page = usePage();
const user = computed(() => page.props.auth.user)
const employees = ref(page.props.employees);
const isGiftFeatureEnabled = computed(() => page.props.gift_feature_enable);
const giftType = ref(page.props.gift_type)
const exchange_rate = computed(() => page.props.gift_exchange_rate);


const isGiftModalOpen = ref(false)

const toggleDialog = (employeeId) => {
    isGiftModalOpen.value = !!isGiftModalOpen
    giftForm.receiver = employeeId
}

const giftForm = useForm({
    type: undefined,
    amount: undefined,
    receiver: undefined,
})

const onSubmit = () => {
    giftForm.processing = true
    // clear the error state
    giftForm.clearErrors()
    if(giftForm.type === 'shop'){
        giftForm.setError('type', 'Sending Gift via Shop is not available at this moment.')
        return;
    }

    NinshikiApp.request().post(route('gift.send'),{
        type: giftForm.type,
        amount: giftForm.amount,
        receiver: giftForm.receiver
    }).then((resp) => {
        giftForm.processing = false
        console.info(resp)
    }).catch(resp => {
        giftForm.processing = false
        console.error(resp)
    })


}

watch(() => giftForm.type, (val) => {
    if(giftForm.type !== val?.type){
        giftForm.clearErrors()
    }
})

watch(() => giftForm.amount, (val) => {
    if(giftForm.amount !== val?.amount){
        giftForm.clearErrors()
    }
})


</script>

<template>
    <div class="max-w-screen-lg mx-auto mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 w-full">
        <Dialog v-model:visible="isGiftModalOpen" :block-scroll="true" :closable="!giftForm.processing" position="center" modal header="Send Gift" :style="{ width: '20rem' }">
            <Message severity="info" class="w-full my-4"><i class="text-xs text-balance">Current exchange rate for sending gift coins: {{ exchange_rate }}</i></Message>
            <div class="flex flex-col gap-4 mb-4">
                <label for="gift_type" class="font-semibold w-auto">Select Type of Gift</label>
                <Select v-model="giftForm.type" :options="giftType" placeholder="Select type of gift" class="w-ful"
                        :pt="{
                   optionLabel: {
                       class: 'capitalize'
                   },
                   label: {
                     class: 'capitalize'
                   }
                }" required/>
                <Message v-if="giftForm.errors?.type" severity="error" size="small" variant="simple">
                    {{ giftForm.errors?.type }}
                </Message>
            </div>
            <div v-if="giftForm?.type === 'coins' " class="flex flex-col gap-4 mb-8">
                <label for="amount" class="font-semibold w-24">Amount</label>
                <InputNumber v-model="giftForm.amount" id="amount" :min="0" class="flex-auto" autocomplete="off"
                             :useGrouping="false" required/>
                <Message v-if="giftForm.errors?.amount" severity="error" size="small" variant="simple">
                    {{ giftForm.errors?.amount }}
                </Message>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" :disabled="giftForm.processing" @click="isGiftModalOpen = false"></Button>
                <Button type="button" icon="pi pi-send" label="Send" :disabled="giftForm.processing" :loading="giftForm.processing" @click.prevent="onSubmit"></Button>
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
                        <Button v-if="isGiftFeatureEnabled && user?.id !== employee.id" icon="pi pi-gift" variant="text"
                                rounded aria-label="Gift"
                                v-tooltip.bottom="'Send Gift'" @click="toggleDialog(employee.id)"/>
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<style scoped>

</style>
