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
import { Textarea } from '@/Components/ui/textarea'

import { User } from '@/types'

const emit = defineEmits(['select'])

defineProps<{
  users: Array<User>
  selected?: number
}>()
</script>

<template>
  <FormField name="user_id">
    <FormItem class="flex flex-col gap-2">
      <FormLabel>المستفيد</FormLabel>
      <Combobox
        :options="
          users.map((seller) => ({
            value: seller.id,
            label: seller.name,
          }))
        "
        choose-title="اختر مستفيد..."
        search-placeholder="ابحث عن مستفيد"
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
