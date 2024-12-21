<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'


const page = usePage()
const user = computed(() => page.props.auth.user)
defineProps({ posts: Object })
</script>

<template>
<div class="text-red-500">
    {{ JSON.stringify(posts) }}
</div>
</template>

<style scoped>

</style>
