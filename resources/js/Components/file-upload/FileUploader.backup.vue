<script setup lang="ts">
import Progress from '../ui/progress/Progress.vue'
import { Input } from '../ui/input'
import axios, { AxiosProgressEvent } from 'axios'
import { ref } from 'vue'
import { Trash2 } from 'lucide-vue-next'

const emit = defineEmits(['uploaded', 'delete'])

const file = ref<File>()
const progress = ref<number>(0)

async function upload() {
  const response = await axios.post(
    '/upload',
    {
      file: file.value,
    },
    {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      withCredentials: true,
      withXSRFToken: true,
      onUploadProgress: (uploadProgress: AxiosProgressEvent) => {
        if (!uploadProgress.progress) return
        progress.value = Math.round(uploadProgress.progress * 100)
      },
    },
  )

  emit('uploaded', response.data)
}

const change = (event: any) => {
  if (!event.target.files) return

  file.value = event.target?.files[0]
  upload()
}

const getSize = () => {
  if (!file.value) return ''

  return file.value?.size > 1024 * 1024
    ? `${(file.value?.size / (1024 * 1024)).toFixed(2)} MB`
    : `${(file.value?.size / 1024).toFixed(2)} KB`
}
</script>

<template>
  <div v-if="file" class="mb-2 flex items-center justify-between">
    <div class="flex items-center gap-x-3">
      <div
        class="flex size-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 dark:border-neutral-700 dark:text-neutral-500"
      >
        <span class="pi pi-file-pdf text-red-400" />
      </div>

      <div>
        <p dir="ltr" class="text-sm font-medium text-gray-800 dark:text-white">
          {{ file?.name }}
        </p>
        <p dir="ltr" class="text-xs text-gray-500 dark:text-neutral-500">
          {{ getSize() }}
        </p>
      </div>
    </div>
    <div class="inline-flex items-center gap-x-2">
      <button
        type="button"
        class="relative text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
        @click="emit('delete')"
      >
        <Trash2 class="size-4 shrink-0" />
        <span class="sr-only">Delete</span>
      </button>
    </div>
  </div>

  <div v-if="file" class="flex items-center gap-x-3 whitespace-nowrap">
    <Progress :model-value="progress" />
    <span class="text-sm text-gray-800 dark:text-white"> {{ progress }}% </span>
  </div>

  <Input type="file" @change="change" />
</template>
