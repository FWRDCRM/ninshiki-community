<script setup></script>

<template>
    <div class="container flex min-h-dvh min-w-full">
        <div class="flex w-full items-center justify-center">
            <div class="flex flex-col gap-3">
                <p class="text-3xl font-bold text-gray-600">Login</p>
                <p class="font-normal">Enter your credentials to access your Ninshiki account.</p>
                <Button class="mt-8" label="Continue with Zoho" as="a" :href="route('login.requestProvider')">
                    <template #icon>
                        <img alt="zohoIcon" src="https://api.iconify.design/logos:zoho.svg" />
                    </template>
                </Button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
