<script setup lang="ts">
import axios, { AxiosProgressEvent } from 'axios'
import { onMounted, ref } from 'vue'
import { Trash2 } from 'lucide-vue-next'

const emit = defineEmits(['uploaded', 'delete'])

const props = defineProps<{
  file: File
}>()

const progress = ref<number>(0)

upload()

function returnFileSize() {
  if (props.file.size < 1e3) {
    return `${props.file.size} bytes`
  } else if (props.file.size >= 1e3 && props.file.size < 1e6) {
    return `${(props.file.size / 1e3).toFixed(1)} KB`
  } else {
    return `${(props.file.size / 1e6).toFixed(1)} MB`
  }
}

async function upload() {
  const response = await axios.post(
    '/upload',
    {
      file: props.file,
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
</script>

<template>
  <div>
    <!-- Uploading File Content -->
    <div class="mb-2 flex items-center justify-between">
      <div class="flex items-center gap-x-3">
        <span
          class="flex size-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500"
        >
          <svg
            class="size-5 shrink-0"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" x2="12" y1="3" y2="15"></line>
          </svg>
        </span>
        <div>
          <p class="text-sm font-medium text-gray-800">
            {{ file.name }}
          </p>
          <p dir="ltr" class="text-xs text-gray-500">
            {{ returnFileSize() }}
          </p>
        </div>
      </div>
      <div class="inline-flex items-center gap-x-2">
        <span v-if="progress === 100" class="relative">
          <svg
            class="size-4 shrink-0 text-teal-500"
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            viewBox="0 0 16 16"
          >
            <path
              d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
            ></path>
          </svg>
          <span class="sr-only">Success</span>
        </span>
        <button
          type="button"
          class="relative text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-none disabled:pointer-events-none disabled:opacity-50"
          @click="emit('delete')"
        >
          <Trash2 class="size-4 shrink-0" />
          <span class="sr-only">Delete</span>
        </button>
      </div>
    </div>
    <!-- End Uploading File Content -->

    <!-- Progress Bar -->
    <div
      class="flex h-2 w-full overflow-hidden rounded-full bg-gray-200"
      role="progressbar"
      aria-valuenow="100"
      aria-valuemin="0"
      aria-valuemax="100"
    >
      <div
        class="flex flex-col justify-center overflow-hidden whitespace-nowrap rounded-full text-center text-xs text-white transition duration-500"
        :class="[progress < 100 ? 'bg-blue-600' : 'bg-teal-500']"
        :style="`width: ${progress}%`"
      ></div>
    </div>
    <!-- End Progress Bar -->
  </div>
</template>
