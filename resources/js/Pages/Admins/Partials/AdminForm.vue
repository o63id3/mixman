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
    <FormItem class="col-span-full">
      <FormLabel>الاسم</FormLabel>
      <FormControl>
        <Input type="text" v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="username">
    <FormItem>
      <FormLabel>اسم المستخدم</FormLabel>
      <FormControl>
        <Input
          type="text"
          v-bind="componentField"
          autocapitalize="none"
          :disabled="disabled"
        />
      </FormControl>
      <FormDescription>
        سيتم استخدام هذا الاسم في عملية تسجيل الدخول.
      </FormDescription>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="password">
    <FormItem>
      <FormLabel>كلمة المرور</FormLabel>
      <FormControl>
        <Input
          type="password"
          placeholder="********"
          v-bind="componentField"
          :disabled="disabled"
        />
      </FormControl>
      <FormDescription> سيتم استخدامها في عملية تسجيل الدخول. </FormDescription>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="contact_info">
    <FormItem class="md:col-span-2">
      <FormLabel>بيانات التواصل</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="notes">
    <FormItem class="md:col-span-2">
      <FormLabel>ملاحظات</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
