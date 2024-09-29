<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue'
import {
  SelectIcon,
  SelectTrigger,
  type SelectTriggerProps,
  useForwardProps,
} from 'radix-vue'
import { CaretSortIcon } from '@radix-icons/vue'
import { cn } from '@/lib/utils'

const props = defineProps<
  SelectTriggerProps & { class?: HTMLAttributes['class'] }
>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <SelectTrigger
    v-bind="forwardedProps"
    :class="
      cn(
        '[&>span]:rtl:truncate-rtl flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&>span]:ltr:truncate',
        'ltr:text-left rtl:text-right',
        props.class,
      )
    "
  >
    <SelectIcon :as-child="true">
      <CaretSortIcon
        :class="
          cn(
            'h-4 w-4 shrink-0 opacity-50',
            'ltr:me-2 ltr:ms-auto rtl:me-auto rtl:ms-2',
          )
        "
      />
    </SelectIcon>
    <div class="flex-1">
      <slot />
    </div>
  </SelectTrigger>
</template>
