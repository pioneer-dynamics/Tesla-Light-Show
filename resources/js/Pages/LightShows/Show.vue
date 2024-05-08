<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { Link } from '@inertiajs/vue3';
    import { DateTime } from "luxon";
    import PrimaryButton from '@/Components/PrimaryButton.vue';

    const props = defineProps({
        light_show: Object,
    })
</script>
<template>
    <AppLayout :title="props.light_show.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ props.light_show.title }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="max-h-100 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <iframe width="100%" height="100%" :src="light_show.video_embed_url" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    <div class="p-5">
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-xs">By: <Link :href="route('light-shows.show', {light_show: light_show.user.hash_id})">{{ light_show.user.name }}</Link> / {{ DateTime.fromISO(light_show.created_at).toLocaleString(DateTime.DATETIME_MED) }}</p>
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ light_show.title }}</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ light_show.description }}</p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Downloads: {{ light_show.downloads }} | Duration {{ light_show.duration }}</p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                            <a class="inline-flex items-center bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" :href="route('light-shows.audio', {light_show: light_show.hash_id, filename: light_show.title.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''), ext: light_show.audio_file.split('.').pop()})">Download Audio File</a>
                            <a class="inline-flex items-center bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" :href="route('light-shows.seq', {light_show: light_show.hash_id, filename: light_show.title.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')})">Download FSEQ file</a>
                            <a class="inline-flex items-center bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" :href="route('light-shows.zip', {light_show: light_show.hash_id, filename: light_show.title.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')})">Download ZIP file</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
