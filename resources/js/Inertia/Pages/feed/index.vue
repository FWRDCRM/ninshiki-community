<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Layout from '../../Layouts/layout.vue'

const page = usePage()
const user = computed(() => page.props.auth.user)
defineProps({ posts: Object })
defineOptions({ layout: Layout })
</script>

<template>
<div class="text-red-500">
    {{ JSON.stringify(posts) }}
</div>
</template>

<style scoped>

</style>
