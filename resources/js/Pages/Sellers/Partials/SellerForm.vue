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
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { Region } from '@/types'

defineProps<{
  regions: Array<Region>
}>()
</script>

<template>
  <FormField v-slot="{ componentField }" name="name">
    <FormItem>
      <FormLabel>اسم نقطة البيع</FormLabel>
      <FormControl>
        <Input type="text" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="region_id">
    <FormItem>
      <FormLabel>المنطقة</FormLabel>
      <FormControl>
        <Select v-bind="componentField">
          <SelectTrigger>
            <SelectValue placeholder="اختر منطقة" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectLabel>المناطق</SelectLabel>
              <SelectItem
                v-for="region in regions"
                :key="region.id"
                :value="String(region.id)"
              >
                {{ region.name }}
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="username">
    <FormItem>
      <FormLabel>اسم المستخدم</FormLabel>
      <FormControl>
        <Input type="text" v-bind="componentField" autocapitalize="none" />
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
        <Input type="password" placeholder="********" v-bind="componentField" />
      </FormControl>
      <FormDescription> سيتم استخدامها في عملية تسجيل الدخول. </FormDescription>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="seller_percentage">
    <FormItem class="col-span-full">
      <FormLabel>نسبة الربح (%)</FormLabel>
      <FormControl>
        <Input type="number" min="0" max="100" v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="contact_info">
    <FormItem class="col-span-full">
      <FormLabel>بيانات التواصل</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" />
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
