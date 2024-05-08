<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { Link } from '@inertiajs/vue3';
    import { DateTime } from 'luxon';

    const props = defineProps({
        light_shows: Array,
    })
</script>
<template>
    <AppLayout title="My Light Shows">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Light Shows
            </h2>
        </template>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="container mx-auto p-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <Link :href="route('light-shows.create', {returnTo: route('light-shows.my')})">
                        <div class="text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mx-auto">
                                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </Link>
                    <div v-for="light_show in props.light_shows.data" class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <Link :href="route('light-shows.show', {light_show: light_show.hash_id})">
                            <iframe width="100%" height="160px" :src="light_show.video_embed_url" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            <div class="p-5">
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-xs">By: {{ light_show.user.name }} / {{ DateTime.fromISO(light_show.created_at).toLocaleString(DateTime.DATETIME_MED) }}</p>
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ light_show.title }}</h5>
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Downloads: {{ light_show.downloads }} | Duration {{ light_show.duration }}</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>