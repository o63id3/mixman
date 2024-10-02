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
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()
</script>

<template>
  <FormField v-slot="{ componentField }" name="description">
    <FormItem class="col-span-full">
      <FormLabel>الوصف</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="network_id">
    <FormItem>
      <FormLabel>الشبكة</FormLabel>
      <FormControl>
        <Select v-bind="componentField">
          <SelectTrigger>
            <SelectValue placeholder="اختر  الشبكة" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem
                v-for="network in networks"
                :key="network.id"
                :value="String(network.id)"
              >
                {{ network.name }}
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </FormControl>
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
</template>
