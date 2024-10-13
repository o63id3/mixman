<script lang="ts" setup>
import type { AreaHTMLAttributes, HTMLAttributes } from 'vue'
import { Primitive, type PrimitiveProps } from 'radix-vue'
import { cn } from '@/lib/utils'
import { Link } from '@inertiajs/vue3'

const props = withDefaults(
  defineProps<
    PrimitiveProps & {
      class?: HTMLAttributes['class']
    } & {
      href?: AreaHTMLAttributes['href']
    }
  >(),
  {
    as: 'a',
  },
)
</script>

<template>
  <Link v-if="props.href" :href="props.href">
    <Primitive
      :as="as"
      :as-child="asChild"
      :class="cn('transition-colors hover:text-foreground', props.class)"
    >
      <slot />
    </Primitive>
  </Link>
  <Primitive
    v-else
    :as="as"
    :as-child="asChild"
    :class="cn('transition-colors hover:text-foreground', props.class)"
  >
    <slot />
  </Primitive>
</template>
