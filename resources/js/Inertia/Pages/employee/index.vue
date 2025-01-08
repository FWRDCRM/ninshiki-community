<script setup>
import Layout from "@/Layouts/layout.vue";

defineOptions({layout: Layout})



const {employees } = defineProps({ employees: Object });

</script>

<template>
<div class="max-w-screen-lg mx-auto mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 w-full">
    <div v-for="employee in employees" :key="employee.id" class="flex w-full">
        <Card class="min-w-48 w-full">
            <template #header>
                <div class="flex flex-wrap items-center justify-center mt-4">
                    <Avatar :alt="employee.name" :image="employee.avatar ?? $ninshiki.uiAvatar(employee.name)" shape="circle" size="xlarge"/>
                </div>
            </template>
            <template #title>
                <div class="text-balance text-center">{{employee.name}}</div>
            </template>
            <template #subtitle>
                <div class="text-center">
                    {{employee.email}}
                </div>
            </template>
        </Card>

    </div>
</div>
</template>

<style scoped>

</style>
