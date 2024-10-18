<script setup lang="ts">
import { onMounted, ref } from 'vue'

import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'

defineProps<{
  disabled?: boolean
}>()

const input = ref<InstanceType<typeof Input> | null>(null)
onMounted(() => {
  input.value?.inputElement?.focus()
})
</script>

<template>
  <FormField v-slot="{ componentField }" name="name">
    <FormItem class="col-span-full md:col-span-1">
      <FormLabel>الاسم</FormLabel>
      <FormControl>
        <Input
          ref="input"
          type="text"
          v-bind="componentField"
          :disabled="disabled"
        />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="internet_price_per_week">
    <FormItem class="col-span-full md:col-span-1">
      <FormLabel>سعر الإنترنت أسبوعيا</FormLabel>
      <FormControl>
        <Input type="number" v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
