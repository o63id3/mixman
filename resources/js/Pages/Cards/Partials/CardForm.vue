<script setup lang="ts">
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
</script>

<template>
  <FormField v-slot="{ componentField }" name="name">
    <FormItem class="col-span-full">
      <FormLabel>اسم الكرت</FormLabel>
      <FormControl>
        <Input type="text" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="price_for_consumer">
    <FormItem class="col-span-full">
      <FormLabel>السعر للمستهلك</FormLabel>
      <FormControl>
        <Input type="number" step="0.01" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <!-- <FormField v-slot="{ componentField }" name="price_for_seller">
    <FormItem>
      <FormLabel>السعر للبائع</FormLabel>
      <FormControl>
        <Input type="number" step="0.01" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField> -->
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
