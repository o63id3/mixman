<script setup lang="ts">
import { Button } from '@/Components/ui/button'

defineProps<{
  loading?: boolean
  disabled?: boolean
  btnTitle?: string
}>()
</script>

<template>
  <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
      <form class="space-y-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <slot />
        </div>
        <Button
          type="submit"
          :loading="loading"
          :disabled="disabled || loading"
        >
          {{ btnTitle ?? 'إنشاء' }}
        </Button>
      </form>
    </div>
  </div>
</template>
