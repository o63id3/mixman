<script setup lang="ts">
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
</script>

<template>
  <FormField v-slot="{ componentField }" name="name">
    <FormItem class="col-span-full">
      <FormLabel>اسم المنطقة</FormLabel>
      <FormControl>
        <Input type="text" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
