<script setup lang="ts">
import { Button } from '@/Components/ui/button'

defineProps<{
  canUpdate?: boolean
  canDelete?: boolean
}>()
</script>

<template>
  <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
      <form class="space-y-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <slot />
        </div>
        <div class="flex gap-2">
          <Button v-if="canUpdate" type="submit"> تعديل </Button>
          <div v-if="canDelete">
            <slot name="deleteBtn" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
