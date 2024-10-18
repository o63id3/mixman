<script setup lang="ts">
import UpdateFormLayout from '@/Layouts/UpdateFormLayout.vue'

import { Input } from '@/Components/ui/input'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { CardDescription, CardTitle } from '@/Components/ui/card'

import { usePage } from '@inertiajs/vue3'

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

const user = usePage().props.auth.user

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجب ان يكون حرفين على الاقل' }),
    username: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'اسم المستخدم يجب ان يكون حرفين على الاقل' }),
  }),
)

const initialValues = {
  name: user.name,
  username: user.username,
}
</script>

<template>
  <section>
    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('profile.update')"
    >
      <template #title>
        <CardTitle>معلومات الحساب</CardTitle>
        <CardDescription> حدث اسمك واسم المستخدم الخاص بك </CardDescription>
      </template>

      <FormField v-slot="{ componentField }" name="name">
        <FormItem class="col-span-full max-w-xl">
          <FormLabel>اسمك</FormLabel>
          <FormControl>
            <Input type="text" v-bind="componentField" />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>
      <FormField v-slot="{ componentField }" name="username">
        <FormItem class="col-span-full max-w-xl">
          <FormLabel>اسم المستخدم</FormLabel>
          <FormControl>
            <Input type="text" v-bind="componentField" />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>
    </UpdateFormLayout>
  </section>
</template>
