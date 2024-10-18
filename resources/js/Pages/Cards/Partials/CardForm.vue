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
import { Textarea } from '@/Components/ui/textarea'

const input = ref<InstanceType<typeof Input> | null>(null)
onMounted(() => {
  input.value?.inputElement?.focus()
})
</script>

<template>
  <FormField v-slot="{ componentField }" name="name">
    <FormItem>
      <FormLabel>اسم الكرت</FormLabel>
      <FormControl>
        <Input ref="input" type="text" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="price_for_consumer">
    <FormItem>
      <FormLabel>السعر للمستهلك</FormLabel>
      <FormControl>
        <Input type="number" step="0.01" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="notes">
    <FormItem class="col-span-full">
      <FormLabel>ملاحظات</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
