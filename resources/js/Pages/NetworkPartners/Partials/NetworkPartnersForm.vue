<script setup lang="ts">
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Input } from '@/Components/ui/input'
import { User } from '@/types'

const emit = defineEmits(['select'])

defineProps<{
  disabled?: boolean
  partners: Array<User>
}>()
</script>

<template>
  <FormField v-slot="{ componentField }" name="user_id">
    <FormItem>
      <FormLabel>الشريك</FormLabel>
      <FormControl>
        <Select v-bind="componentField">
          <SelectTrigger>
            <SelectValue placeholder="اختر الشريك" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem
                v-for="partner in partners"
                :key="partner.id"
                :value="String(partner.id)"
              >
                {{ partner.name }}
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="share">
    <FormItem>
      <FormLabel>الحصة</FormLabel>
      <FormControl>
        <Input type="number" v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
