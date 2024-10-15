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
        'rounded-none border-y bg-card text-card-foreground shadow lg:rounded-xl lg:border-x',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
