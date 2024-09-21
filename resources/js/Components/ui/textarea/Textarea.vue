<script setup lang="ts">
import { ref, watch, nextTick, onMounted } from 'vue'
import type { HTMLAttributes } from 'vue'
import { useVModel } from '@vueuse/core'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
  defaultValue?: string | number
  modelValue?: string | number
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})

const textarea = ref<HTMLTextAreaElement | null>(null)

const resizeTextarea = () => {
  if (textarea.value) {
    textarea.value.style.height = 'auto'
    textarea.value.style.height = `${textarea.value.scrollHeight}px`
  }
}

watch(modelValue, () => {
  nextTick(resizeTextarea)
})

onMounted(() => {
  resizeTextarea()
})
</script>

<template>
  <textarea
    ref="textarea"
    v-model="modelValue"
    :class="
      cn(
        'flex min-h-[60px] w-full resize-none overflow-hidden rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 sm:text-sm',
        props.class,
      )
    "
    @input="resizeTextarea"
  />
</template>
