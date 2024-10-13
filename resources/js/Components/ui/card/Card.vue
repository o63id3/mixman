<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    :class="
      cn(
        'rounded-none border bg-card text-card-foreground shadow sm:rounded-xl',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
