<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { useForm } from '@inertiajs/vue3';
    import FormSection from '@/Components/FormSection.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import TextAreaInput from '@/Components/TextAreaInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { computed, ref } from 'vue';
    import vueFilePond from 'vue-filepond';
    import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
    import 'filepond/dist/filepond.min.css';
    import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

    const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);

    const props = defineProps({

    })

    const filesPending = computed(() => !form.audio_file || !form.sequence_file || !(form.youtube_preview || form.video_preview))

    const filepondServerEndpoint = {
        process: route('bg-uploads.process'),
        revert: route('bg-uploads.revert'),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    }

    const handleAudioFileUploaded = (error, file) => {
        form.audio_file = JSON.parse(file.serverId).files['audio_file'];
    }
    
    const handleAudioFileReverted = (error, file) => {
        form.audio_file = null;
    }

    const validateFseqFile = (file, type) => {
        const reader = new FileReader();

        reader.readAsArrayBuffer(file);

        return new Promise((resolve, reject) => {
            reader.onload = () => {
                const buffer = reader.result;

                const dataView = new DataView(buffer);

                const identifier = String.fromCharCode(dataView.getUint8(0), dataView.getUint8(1), dataView.getUint8(2), dataView.getUint8(3));

                if(['FSEQ','PSEQ'].indexOf(identifier) == 1)
                    resolve('custom/fseq');
                else
                    reject(type)
            }

            reader.onerror = () => {
                reject(type);
            };
        })
    }

    const handleAudioFileInit = (error, file) => {
        if(!error)
        {
            form.title = form.title ? form.title : file.filenameWithoutExtension
        }
    }
    
    const handleSequenceFileInit = (error, file) => {
        if(!error)
        {
            form.title = form.title ? form.title : file.filenameWithoutExtension

            getFseqDuration(file.file).then(duration => {
                form.duration = duration;
            })
        }
    }
    
    const handleSequenceFileUploaded = (error, file) => {
        form.sequence_file = JSON.parse(file.serverId).files['sequence_file'];
    }
    
    const handleSequenceFileReverted = (error, file) => {
        form.sequence_file = null;
    }
    
    const handleVideoFileInit = (error, file) => {
        if(!error)
        {
            form.title = form.title ? form.title : file.filenameWithoutExtension
        }
    }
    
    const handleVideoFileUploaded = (error, file) => {
        form.video_preview = JSON.parse(file.serverId).files['video_preview'];
    }
    
    const handleVideoFileReverted = (error, file) => {
        form.video_preview = null;
    }

    const sequenceFileInput = ref(null);
    const audioFileInput = ref(null);
    const videoFileInput = ref(null);

    const form = useForm({
        title: '',
        description: '',
        duration: '',
        sequence_file: '',
        audio_file: '',
        youtube_preview: '',
        video_preview: '',
    })

    const uploadForm = useForm({
        audio_file: '',
        video_preview: '',
        sequence_file: '',
    })

    const addModel = () => {
        form.post(route('light-shows.store'));
    }

    const getFseqDuration = (file) => {
        const reader = new FileReader();
        reader.readAsArrayBuffer(file);

        return new Promise((resolve, reject) => {
            reader.onload = () => {
                const buffer = reader.result;

                const dataView = new DataView(buffer);

                const frameCount = dataView.getUint32(14, true);

                const stepTime = dataView.getUint8(18);

                const durationS = (frameCount * stepTime) / 1000;

                resolve(new Date(durationS * 1000).toISOString().substr(15, 4));
            };

            reader.onerror = () => {
                reject(new Error('Failed to read file'));
            };
        });
    }
</script>
<template>
    <AppLayout title="Submit a new Light Show">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Submit a new Light Show
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="addModel">
                    <template #title>
                        Submit a new Light Show
                    </template>
                    <template #description>
                        Built a new Light Show? Yay! Show it to the world here!
                    </template>
                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="sequence_file" value="FSEQ File (*.fseq)" />
                            <file-pond
                                name="sequence_file"
                                ref="sequenceFileInput"
                                label-idle="Click to select or drop file here..."
                                v-bind:files="uploadForm.sequence_file"
                                class="mt-1 block w-full" 
                                :credits="false"
                                :server="filepondServerEndpoint"
                                @processfile="handleSequenceFileUploaded"
                                @addfile="handleSequenceFileInit"
                                accepted-file-types="custom/fseq"
                                :fileValidateTypeDetectType="validateFseqFile"
                                maxFileSize="2MB"
                                @removefile="handleSequenceFileReverted"
                            />
                            <InputError :message="form.errors.sequence_file" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="duration" value="Duration" />
                            <TextInput
                                mask="#:##"
                                id="duration"
                                v-model="form.duration"
                                type="text"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-smst"
                                placeholder="m:ss"
                            />
                            <InputError :message="form.errors.duration" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="audio_file" value="Audio File (*.wav or *mp3)" />
                            <file-pond
                                name="audio_file"
                                ref="audioFileInput"
                                label-idle="Click to select or drop file here..."
                                accepted-file-types="audio/wav, audio/mpeg"
                                v-bind:files="uploadForm.audio_file"
                                class="mt-1 block w-full" 
                                :credits="false"
                                maxFileSize="100MB"
                                :server="filepondServerEndpoint"
                                @processfile="handleAudioFileUploaded"
                                @removefile="handleAudioFileReverted"
                                @addfile="handleAudioFileInit"
                            />
                            <InputError :message="form.errors.audio_file" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="youtube_preview" value="YouTube Preview (YouTube URL)" />
                            <TextInput 
                                class="mt-1 block w-full" 
                                id="youtube_preview" 
                                type="url"
                                v-model="form.youtube_preview"
                                placeholder="https://www.youtube.com/watch?v=ABCD"
                            />
                            <InputError :message="form.errors.youtube_preview" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="video_preview" value="OR Video Preview (MP4 File)" />
                            <file-pond
                                name="video_preview"
                                ref="videoFileInput"
                                label-idle="Click to select or drop file here..."
                                accepted-file-types="video/mp4"
                                v-bind:files="uploadForm.video_preview"
                                class="mt-1 block w-full" 
                                :credits="false"
                                maxFileSize="30MB"
                                :server="filepondServerEndpoint"
                                @processfile="handleVideoFileUploaded"
                                @removefile="handleVideoFileReverted"
                                @addfile="handleVideoFileInit"
                            />
                            <InputError :message="form.errors.video_preview" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="title" value="Title" />
                            <TextInput
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                            />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="title" value="Description" />
                            <TextAreaInput
                                id="description"
                                v-model="form.description"
                                type="text"
                                class="mt-1 block w-full"
                                rows="5"
                            />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>
                    </template>
                    <template #actions>
                        <PrimaryButton :disabled="form.processing || filesPending" :class="{ 'opacity-25': form.processing || filesPending }">Submit</PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
</template>
<style>
    .filepond--panel-root {
        @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm
    }

    .filepond--drip-blob {
        @apply dark:bg-gray-200 bg-gray-200
    }
</style>