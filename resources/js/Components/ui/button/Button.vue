<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { Primitive, type PrimitiveProps } from 'radix-vue'
import { type ButtonVariants, buttonVariants } from '.'
import { cn } from '@/lib/utils'
import { Loader2Icon } from 'lucide-vue-next'

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
  loading: false,
})
</script>

<template>
  <Primitive
    :as="as"
    :as-child="asChild"
    :class="cn(buttonVariants({ variant, size }), props.class, 'relative')"
  >
    <!-- <div class="flex items-center" :class="[loading ? 'opacity-0' : '']"> -->
    <slot />
    <span v-if="loading" class="ltr:ml-2 rtl:mr-2" aria-hidden="true">
      <Loader2Icon class="h-4 w-4 animate-spin" />
    </span>
    <!-- </div> -->
    <!-- <span
      v-if="loading"
      class="absolute inset-0 flex items-center justify-center"
      aria-hidden="true"
    >
      <Loader2Icon class="h-4 w-4 animate-spin" />
    </span> -->
  </Primitive>
</template>
