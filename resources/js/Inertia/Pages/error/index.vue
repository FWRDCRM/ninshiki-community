<script setup>
import {computed} from 'vue'
import {Head} from "@inertiajs/vue3";

const props = defineProps({status: Number, redirect: String | null, message: String | null})
const title = computed(() => {
    return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
        422: '422: Unprocessable entity',
        401: '401: Unauthorized',
    }[props.status]
})

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
        401: 'Whoops, it seems like you\'re session has been already expired. Please login and try again.',
    }[props.status]
})
</script>

<template>
    <div class="container flex min-h-dvh min-w-full">
        <div class="flex items-center justify-center w-full">
            <div class="flex gap-3 flex-col max-w-lg">
                <p class="font-bold text-3xl text-gray-600">{{ title }}</p>
                <p class="font-normal">{{ description }}</p>
                <div class="flex max-w-60">
                    <Button class="mt-8" :label="status === 503 ? 'Try Again' : 'Go Back'" as="a" :href="redirect ?? '/'"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
