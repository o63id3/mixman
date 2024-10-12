<script setup lang="ts">
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
  FormDescription,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
import Textarea from '@/Components/ui/textarea/Textarea.vue'

defineProps<{
  disabled?: boolean
}>()
</script>

<template>
  <FormField v-slot="{ componentField }" name="name">
    <FormItem class="col-span-full md:col-span-1">
      <FormLabel>الاسم</FormLabel>
      <FormControl>
        <Input type="text" v-bind="componentField" :disabled="disabled" />
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
