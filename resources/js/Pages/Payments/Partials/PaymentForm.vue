<script setup lang="ts">
import Combobox from '@/Components/combobox/Combobox.vue'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { User } from '@/types'

const emit = defineEmits(['select'])

defineProps<{
  sellers: Array<User>
  selected?: number
}>()
</script>

<template>
  <FormField name="user_id">
    <FormItem class="flex flex-col gap-2">
      <FormLabel>نقطة البيع</FormLabel>
      <Combobox
        :options="
          sellers.map((seller) => ({
            value: seller.id,
            label: seller.name,
          }))
        "
        choose-title="اختر بائع..."
        search-placeholder="ابحث عن نقطة بيع"
        :selected="selected"
        @select="(selected: any) => emit('select', selected.value)"
      />
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="amount">
    <FormItem>
      <FormLabel>المبلغ</FormLabel>
      <Input type="number" step="0.01" v-bind="componentField" />
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="notes">
    <FormItem class="md:col-span-2">
      <FormLabel>ملاحظات</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
