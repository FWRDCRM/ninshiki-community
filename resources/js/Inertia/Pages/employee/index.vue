<script setup>
import Layout from '@/Layouts/layout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Select from 'primevue/select';
import { computed, ref, watch } from 'vue';

defineOptions({ layout: Layout });

const page = usePage();
const user = computed(() => page.props.auth.user);
const employees = ref(page.props.employees);
const isGiftFeatureEnabled = computed(() => page.props.gift_feature_enable);
const giftType = ref(page.props.gift_type);
const exchange_rate = computed(() => page.props.gift_exchange_rate);

const isGiftModalOpen = ref(false);

const toggleDialog = (employeeId, name) => {
    isGiftModalOpen.value = !!isGiftModalOpen;
    giftForm.receiver.id = employeeId;
    giftForm.receiver.name = name;
};

const giftForm = useForm({
    type: undefined,
    amount: undefined,
    receiver: {
        id: undefined,
        name: undefined,
    },
});

const onSubmit = () => {
    giftForm.processing = true;
    // clear the error state
    giftForm.clearErrors();
    if (giftForm.type === 'shop') {
        giftForm.setError('type', 'Sending Gift via Shop is not available at this moment.');
        giftForm.processing = false;
        return;
    }

    NinshikiApp.request()
        .post(route('gift.send'), {
            type: giftForm.type,
            amount: giftForm.amount,
            receiver: giftForm.receiver?.id,
        })
        .then((resp) => {
            giftForm.processing = false;
            console.info(resp);
        })
        .catch((resp) => {
            giftForm.processing = false;
            if (resp.status === 422) {
                const amountError = resp.response.data.error.errors.amount[0];
                if (amountError) {
                    giftForm.setError('amount', amountError);
                }
            }
            console.error(resp);
        });
};

watch(
    () => giftForm.type,
    (val) => {
        if (giftForm.type !== val?.type) {
            giftForm.clearErrors();
        }
    },
);

watch(
    () => giftForm.amount,
    (val) => {
        if (giftForm.amount !== val?.amount) {
            giftForm.clearErrors();
        }
    },
);
</script>

<template>
    <div class="mx-auto mb-4 grid w-full max-w-screen-lg grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
        <Dialog
            v-model:visible="isGiftModalOpen"
            :block-scroll="true"
            :closable="!giftForm.processing"
            position="top"
            modal
            header="Send Gift"
            :style="{ width: '20rem' }"
        >
            <Message severity="info" class="my-4 w-full"
                ><i class="text-balance text-xs">Current exchange rate for sending gift coins: {{ exchange_rate }}</i></Message
            >
            <div class="mb-8 flex flex-col gap-4">
                <label for="amount" class="w-24 font-semibold">Recipient</label>
                <InputText class="flex-auto" autocomplete="off" v-model="giftForm.receiver.name" disabled />
            </div>
            <div class="mb-4 flex flex-col gap-4">
                <label for="gift_type" class="w-auto font-semibold">Select Type of Gift</label>
                <Select
                    v-model="giftForm.type"
                    :options="giftType"
                    placeholder="Select type of gift"
                    class="w-ful"
                    :pt="{
                        optionLabel: {
                            class: 'capitalize',
                        },
                        label: {
                            class: 'capitalize',
                        },
                    }"
                    required
                />
                <Message v-if="giftForm.errors?.type" severity="error" size="small" variant="simple">
                    {{ giftForm.errors?.type }}
                </Message>
            </div>
            <div v-if="giftForm?.type === 'coins'" class="mb-8 flex flex-col gap-4">
                <label for="amount" class="w-24 font-semibold">Amount</label>
                <InputNumber v-model="giftForm.amount" id="amount" :min="0" class="flex-auto" autocomplete="off" :useGrouping="false" required />
                <Message v-if="giftForm.errors?.amount" severity="error" size="small" variant="simple">
                    {{ giftForm.errors?.amount }}
                </Message>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" :disabled="giftForm.processing" @click="isGiftModalOpen = false"></Button>
                <Button
                    type="button"
                    icon="pi pi-send"
                    label="Send"
                    :disabled="giftForm.processing"
                    :loading="giftForm.processing"
                    @click.prevent="onSubmit"
                ></Button>
            </div>
        </Dialog>

        <div v-for="employee in employees" :key="employee.id" class="flex w-full">
            <Card class="w-full min-w-48">
                <template #header>
                    <div class="mt-4 flex flex-wrap items-center justify-center">
                        <Avatar :alt="employee.name" :image="employee.avatar ?? $ninshiki.uiAvatar(employee.name)" shape="circle" size="xlarge" />
                    </div>
                </template>
                <template #title>
                    <div class="text-balance text-center">{{ employee.name }}</div>
                </template>
                <template #subtitle>
                    <div class="space-x-3 text-center">
                        <div class="italic">@{{ employee.username }}</div>
                        <div>
                            {{ employee.email }}
                        </div>
                    </div>
                </template>
                <template #footer>
                    <div class="text-center">
                        <Button
                            v-if="isGiftFeatureEnabled && user?.id !== employee.id"
                            icon="pi pi-gift"
                            variant="text"
                            rounded
                            aria-label="Gift"
                            v-tooltip.bottom="'Send Gift'"
                            @click="toggleDialog(employee.id, employee.name)"
                        />
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<style scoped></style>
