<script setup lang="ts">
import { PlusCircle, X } from 'lucide-vue-next'
import { Button } from '../ui/button'

const model = defineModel<boolean>()
</script>

<template>
  <Button
    size="sm"
    class="h-7 gap-1 text-xs tracking-wide"
    :variant="model ? 'outline' : 'default'"
  >
    <template v-if="!model">
      <PlusCircle class="h-3.5 w-3.5" />
      <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
        <slot>إنشاء</slot>
      </span>
    </template>
    <template v-else>
      <X class="w-3 text-red-500" />
    </template>
  </Button>
</template>
