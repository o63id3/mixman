<script setup lang="ts">
import { ref } from 'vue'

import { Button } from '../ui/button'

import FileUploader from './FileUploader.vue'

const emit = defineEmits(['uploaded', 'delete'])

const files = ref<Array<File>>([])
const pending = ref<number>(0)

const change = (event: any) => {
  if (!event.target.files) return

  pending.value += event.target.files.length
  files.value.push(...event.target.files)
}

const deleteFile = (key: number) => {
  files.value.splice(key, 1)
  emit('delete', key)
}
const newUploadedFile = (file: string) => {
  pending.value--
  emit('uploaded', file)
}
</script>

<template>
  <div>
    <div class="flex justify-end">
      <Button
        class="relative overflow-hidden text-xs tracking-wide"
        size="xs"
        type="button"
      >
        <input
          type="file"
          class="absolute inset-0 file:border-0 file:bg-transparent file:text-transparent"
          @change="change"
          multiple
        />
        إضافة ملف
      </Button>
    </div>
    <div
      v-if="files.length"
      class="mt-2 flex flex-col rounded-xl border bg-white shadow-sm"
    >
      <div class="space-y-7 p-4 md:p-5">
        <FileUploader
          v-for="(file, key) in files"
          :key="key"
          :file="file"
          @delete="() => deleteFile(key)"
          @uploaded="(file: string) => newUploadedFile(file)"
        />
      </div>
      <div
        class="rounded-b-xl border-t border-gray-200 bg-gray-50 px-4 py-2 md:px-5"
      >
        <div class="flex flex-wrap items-center justify-between gap-x-3">
          <div>
            <span class="text-sm font-semibold text-gray-800">
              {{ pending }} متبقي
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
